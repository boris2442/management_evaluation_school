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
        Schema::create('bilan_competences', function (Blueprint $table) {
      
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // FK vers l'Étudiant

            $table->foreignId('annee_academique_id')->constrained()->onDelete('cascade'); // FK vers Année Académique

            $table->decimal('moyenne_semestre1', 4, 2)->nullable();

            $table->decimal('moyenne_semestre2', 4, 2)->nullable();

            $table->decimal('moyenne_generale', 4, 2)->nullable();

            $table->decimal('moyenne_competences', 4, 2)->nullable();

            $table->text('observations')->nullable();

            // Clé Primaire Composée : Un seul bilan par étudiant et par année (Contrainte 7)

            $table->primary(['user_id', 'annee_academique_id'], 'pk_bilan_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilan_competences');
    }
};
