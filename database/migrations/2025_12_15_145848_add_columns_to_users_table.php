<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('matricule', 15)->unique(); // Contrainte 5
        $table->enum('sexe', ['Masculin', 'Féminin']);
            $table->enum('role', ['Administrateur', 'Enseignant', 'Etudiant']); // Profil/Rôle
            $table->softDeletes(); // Pour la suppression logique
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
