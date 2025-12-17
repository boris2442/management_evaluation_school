<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
  use HasFactory;

    protected $fillable = [
        'specialite_id',
        'code_module', // Doit rester fillable pour que le système puisse l'assigner
        'nom_module',
        'coef_module',
        'ordre',
    ];

    // Relations (assumées)
    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }
    
    // Relation Many-to-Many avec les enseignants (utilisateurs)
    public function enseignants()
    {
        return $this->belongsToMany(User::class, 'module_enseignant');
    }

    /**
     * Boot the model.
     * Logique de génération automatique du code unique M1, M2, etc.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($module) {
            // 1. Trouver le plus grand code existant (ex: 'M12')
            $lastModule = self::query()
                ->selectRaw('MAX(CAST(SUBSTRING(code_module, 2) AS SIGNED)) as max_number')
                ->where('code_module', 'like', 'M%')
                ->first();

            $nextNumber = 1;

            if ($lastModule && $lastModule->max_number !== null) {
                // 2. Incrémenter ce nombre (ex: 12 + 1 = 13)
                $nextNumber = $lastModule->max_number + 1;
            }

            // 3. Assigner le nouveau code (ex: 'M13')
            $module->code_module = 'M' . $nextNumber;
        });
    }
}
