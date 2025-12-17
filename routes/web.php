<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BilanController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\SpecialiteController;
use App\Http\Controllers\AnneeAcademiqueController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InscriptionController;

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


Route::prefix('inscriptions')->name('inscriptions.')->group(function () {
    Route::get('/', [InscriptionController::class, 'index'])->name('index'); // Affiche tout (Create + Liste)
    Route::post('/store', [InscriptionController::class, 'store'])->name('store');
    Route::put('/{inscription}', [InscriptionController::class, 'update'])->name('update');
    Route::delete('/{inscription}', [InscriptionController::class, 'destroy'])->name('destroy');
});




Route::prefix('evaluations')->name('evaluations.')->group(function () {
    // Route pour afficher la page de saisie et filtrer
    Route::get('/', [EvaluationController::class, 'index'])->name('index');

    // Route pour enregistrer les notes en masse
    Route::post('/store', [EvaluationController::class, 'store'])->name('store');
});
Route::get('/bilan/etudiant/{id}', [BilanController::class, 'show'])->name('bilan.show');
Route::get('/bilan/etudiant/{id}', [BilanController::class, 'show'])->name('bilan.show');


// Route pour afficher le grand tableau récapitulatif
Route::get('/bilan-general', [BilanController::class, 'index'])->name('bilan.index');

use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    // Gestion classique
    Route::resource('users', UserController::class);
    
    // Gestion Corbeille
    Route::get('users-trash', [UserController::class, 'trash'])->name('users.trash');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
});
    Route::get('tableau-de-bord', [DashboardController::class, 'index'])->name('tableau-de-bord');
require __DIR__ . '/auth.php';
