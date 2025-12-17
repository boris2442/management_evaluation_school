<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $fillable = ['user_id', 'specialite_id', 'annee_academique_id'];

    public function etudiant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }
}
