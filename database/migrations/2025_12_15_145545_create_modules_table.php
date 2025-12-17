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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialite_id')->constrained()->onDelete('cascade'); // FK vers Specialite
            $table->string('code_module', 10);
            $table->string('nom_module');
            $table->unsignedSmallInteger('coef_module')->default(1); // Coefficient (Contrainte 9)
            $table->unsignedSmallInteger('ordre'); // Ordre/séquence (Contrainte 10)
          

            // Clé d'unicité composée (AK) : Un code de module doit être unique au sein d'une spécialité (Contrainte 3)
            $table->unique(['specialite_id', 'code_module']);

            // Contrainte d'unicité de l'ordre au sein de la spécialité (Contrainte 10)
            $table->unique(['specialite_id', 'ordre']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
