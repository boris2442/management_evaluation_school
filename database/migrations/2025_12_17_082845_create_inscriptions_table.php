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
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            // L'étudiant (un user avec le rôle 'Etudiant')
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // La spécialité choisie
            $table->foreignId('specialite_id')->constrained('specialites')->onDelete('cascade');

            // L'année académique de l'inscription
            $table->foreignId('annee_academique_id')->constrained('annee_academiques')->onDelete('cascade');

            // Sécurité : Un étudiant ne peut pas s'inscrire deux fois la même année
            $table->unique(['user_id', 'annee_academique_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
