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
        Schema::create('evaluations', function (Blueprint $table) {
          
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // FK vers l'Étudiant (un user avec le role 'Etudiant')

            $table->foreignId('module_id')->constrained()->onDelete('cascade'); // FK vers Module

            $table->foreignId('annee_academique_id')->constrained()->onDelete('cascade'); // FK vers Année Académique



            $table->decimal('note', 4, 2); // Note sur 20.00 (Contrainte 8)

            $table->unsignedTinyInteger('semestre'); // Semestre 1 ou 2

            // Clé Primaire Composée : Unicité de l'évaluation par étudiant, module, année et semestre (Contrainte 6)

            $table->primary(['user_id', 'module_id', 'annee_academique_id', 'semestre'], 'pk_evaluation_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
