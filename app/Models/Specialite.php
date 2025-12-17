<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specialite extends Model
{
 use HasFactory;

    protected $fillable = [
        'code_unique',  // Correspond à la migration
        'nom_specialite', // Correspond à la migration
        'description', // Ajouté car présent dans la migration
    ];

    /**
     * Une Spécialité peut avoir plusieurs Modules.
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
