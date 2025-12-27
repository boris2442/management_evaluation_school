<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     return User::all();
    // }


    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = User::query();

        // Filtre par Rôle
        if (!empty($this->filters['role'])) {
            $query->where('role', $this->filters['role']);
        }

        // Filtre par Spécialité
        if (!empty($this->filters['specialite'])) {
            $query->where('specialite', $this->filters['specialite']);
        }

        // Filtre par Année Académique (si stockée sur l'user ou via relation)
        if (!empty($this->filters['annee_id'])) {
            $query->where('annee_academique_id', $this->filters['annee_id']);
        }

        return $query->orderBy('name', 'asc');
    }

    public function headings(): array
    {
        return ['MATRICULE', 'NOM COMPLET', 'EMAIL', 'GENRE', 'RÔLE', 'SPÉCIALITÉ'];
    }

    public function map($user): array
    {
        return [
            $user->matricule,
            strtoupper($user->name),
            $user->email,
            $user->sexe,
            $user->role,
            $user->specialite ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E40AF']]
            ],
        ];
    }
}
