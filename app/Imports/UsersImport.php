<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // <--- AJOUTÉ
use Maatwebsite\Excel\Concerns\WithUpserts;    // <--- AJOUTÉ
use Maatwebsite\Excel\Concerns\WithValidation; // <--- AJOUTÉ
use Maatwebsite\Excel\Concerns\OnEachRow;      // <--- On garde celle-ci
class UsersImport implements OnEachRow, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    // public function model(array $row)
    // {

    //     $sexe = strtolower($row['sexe'] ?? '');
    //     $sexeFinal = (str_starts_with($sexe, 'm')) ? 'Masculin' : 'Féminin';

    //     return new User([

    //         'matricule' => $row['matricule'] ?? null,
    //         'name'      => $row['name'],
    //         'email'     => $row['email'],
    //         'sexe'      => $sexeFinal,
    //         'role'      => $row['role'] ?? 'Etudiant',
    //         'password'  => Hash::make('Pass1234'),
    //         'matricule' => $row[4] ?? $row['matricule'] ?? 'TEMP-' . uniqid(),

    //     ]);
    // }

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        // On cherche si l'utilisateur existe déjà pour éviter les doublons (Upsert manuel)
        $user = User::where('email', $data['email'])->first() ?? new User();

        $sexe = strtolower(trim($data['sexe'] ?? ''));

        $user->fill([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'sexe'     => str_starts_with($sexe, 'm') ? 'Masculin' : 'Féminin',
            'role'     => $data['role'] ?? 'Etudiant',
            'password' => $user->exists ? $user->password : Hash::make('Pass1234'),
        ]);

        // IMPORTANT : Si c'est un nouvel utilisateur, on lui donne un matricule temporaire 
        // pour passer la sécurité MySQL, ton static::boot() fera le reste.
        if (!$user->exists) {
            $user->matricule = 'TEMP-' . now()->timestamp . '-' . $row->getIndex();
        }

        $user->save();
    }



    /**
     * Définit la colonne unique pour éviter les doublons lors de l'import.
     * Si l'email existe déjà, Laravel Excel fera un UPDATE au lieu d'un INSERT.
     */
    public function uniqueBy()
    {
        return 'email';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email'       => 'required|email', // Pas de 'unique' ici car WithUpserts s'en charge
            'sexe'        => 'required',
        ];
    }
}
