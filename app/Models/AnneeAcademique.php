<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnneeAcademique extends Model
{
 use HasFactory;

    // 1. Définition des champs qui peuvent être mass-assignés (Création/Mise à jour)
    protected $fillable = [
        'libelle',
        'date_debut',
        'date_fin',
        'statut', // Pour gérer l'année active
    ];

    // 2. Définition des types de données pour la conversion automatique
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'statut' => 'boolean',
    ];
    
    // 3. Définition des relations (pour plus tard)
    
    // Une année académique peut avoir plusieurs évaluations
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class); // Nécessite la création du modèle Evaluation
    }

    // Une année académique peut avoir plusieurs bilans de compétences
    public function bilans()
    {
        return $this->hasMany(BilanCompetence::class); // Nécessite la création du modèle BilanCompetence
    }
}
