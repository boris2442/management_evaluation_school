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
    /**
     * Un utilisateur (étudiant) peut avoir plusieurs inscriptions 
     * (une par année académique).
     */
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    /**
     * Un étudiant a plusieurs notes (évaluations).
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
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


    /**
     * Calcule la moyenne d'un étudiant pour un semestre et une année donnée
     */
    public function moyenneSemestre($semestre, $anneeId)
    {
        $evaluations = $this->evaluations()
            ->where('annee_academique_id', $anneeId)
            ->where('semestre', $semestre)
            ->with('module')
            ->get();

        if ($evaluations->isEmpty()) return 0;

        $totalNotesPonderees = 0;
        $totalCoefficients = 0;

        foreach ($evaluations as $eval) {
            $totalNotesPonderees += ($eval->note * $eval->module->coef_module);
            $totalCoefficients += $eval->module->coef_module;
        }

        return $totalCoefficients > 0 ? ($totalNotesPonderees / $totalCoefficients) : 0;
    }

    public function calculerNoteFinale($anneeId)
    {
        // 1. Récupérer les notes des modules classiques (M1 à M10)
        $evalsModules = $this->evaluations()
            ->where('annee_academique_id', $anneeId)
            ->whereHas('module', function ($q) {
                $q->where('is_bilan', false);
            })->get();

        // Calcul de la moyenne pondérée des modules (30%)
        $totalPondere = 0;
        $totalCoef = 0;
        foreach ($evalsModules as $eval) {
            $totalPondere += ($eval->note * $eval->module->coef_module);
            $totalCoef += $eval->module->coef_module;
        }
        $moyenneModules = $totalCoef > 0 ? ($totalPondere / $totalCoef) : 0;

        // 2. Récupérer la note du Bilan des Compétences (70%)
        $noteBilan = $this->evaluations()
            ->where('annee_academique_id', $anneeId)
            ->whereHas('module', function ($q) {
                $q->where('is_bilan', true);
            })->first()?->note ?? 0;

        // 3. Formule Finale : (Modules * 0.3) + (Bilan * 0.7)
        return ($moyenneModules * 0.3) + ($noteBilan * 0.7);
    }
}
