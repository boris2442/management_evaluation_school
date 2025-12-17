<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }




    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->matricule)) {
                // 1. Déterminer le préfixe selon le rôle
                $prefix = match ($user->role) {
                    'Administrateur' => 'A',
                    'Enseignant'     => 'P', // P pour Professeur
                    'Etudiant'       => 'E',
                    default          => 'U',
                };

                // 2. Récupérer l'année en cours
                $year = date('Y');

                // 3. Trouver le dernier matricule similaire pour incrémenter
                $lastUser = self::where('role', $user->role)
                    ->where('matricule', 'like', $prefix . $year . '%')
                    ->orderBy('matricule', 'desc')
                    ->first();

                if ($lastUser) {
                    // On extrait les 4 derniers chiffres et on ajoute 1
                    $number = intval(substr($lastUser->matricule, -4)) + 1;
                } else {
                    $number = 1;
                }

                // 4. Assembler le tout (ex: E + 2025 + 0001)
                $user->matricule = $prefix . $year . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
