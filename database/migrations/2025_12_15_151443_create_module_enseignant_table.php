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
        Schema::create('module_enseignant', function (Blueprint $table) {
        
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');



            // Clé primaire composée pour éviter les doublons

            $table->primary(['user_id', 'module_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_enseignant');
    }
};
