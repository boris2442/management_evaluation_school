<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpecialiteController;
use App\Http\Controllers\AnneeAcademiqueController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ... Assurez-vous que l'utilisateur est authentifié pour accéder à ces routes
// Vous devriez wraper ces routes dans un middleware 'auth'

// Route::group(function () {
//     // Route Resource pour les opérations CRUD standard (index, create, store, edit, update, destroy)
//     Route::resource('annee-academiques', AnneeAcademiqueController::class);

//     // Route spécifique pour l'activation/désactivation de l'année
//     Route::put('annee-academiques/{annee_academique}/toggle-statut', [AnneeAcademiqueController::class, 'toggleStatut'])
//         ->name('annee-academiques.toggle-statut');
// });

Route::group(['middleware' => ['web']], function () {
    Route::resource('annee-academiques', AnneeAcademiqueController::class);

    Route::put('annee-academiques/{annee_academique}/toggle-statut', [AnneeAcademiqueController::class, 'toggleStatut'])
        ->name('annee-academiques.toggle-statut');
});

// Route Resource pour la gestion des Spécialités
    Route::resource('specialites', SpecialiteController::class);
// Routes pour la gestion des Modules
    Route::resource('modules', ModuleController::class);

require __DIR__ . '/auth.php';
