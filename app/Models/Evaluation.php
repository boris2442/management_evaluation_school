<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
  protected $fillable = ['user_id', 'module_id', 'annee_academique_id', 'note', 'semestre'];

// Relation vers l'étudiant
public function etudiant() {
    return $this->belongsTo(User::class, 'user_id');
}

// Relation pour récupérer le module
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
