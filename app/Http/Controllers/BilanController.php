<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Models\Specialite;
use Illuminate\Http\Request;
use App\Models\AnneeAcademique;
use App\Http\Controllers\Controller;

class BilanController extends Controller
{
    /**
     * Affiche le grand tableau récapitulatif pour une spécialité
     */
    public function index(Request $request)
    {
        $anneeActive = AnneeAcademique::where('statut', true)->first();
        $specialite_id = $request->specialite_id;

        // On récupère les spécialités pour le filtre
        $specialites = Specialite::all();

        $etudiants = [];
        $modulesNormaux = [];
        $moduleBilan = null;

        if ($specialite_id) {
            // 1. Récupérer les modules de la spécialité (M1 à M10)
            $modulesNormaux = Module::where('specialite_id', $specialite_id)
                ->where('is_bilan', false)
                ->get();

            // 2. Récupérer le module de Bilan (celui à 70%)
            $moduleBilan = Module::where('specialite_id', $specialite_id)
                ->where('is_bilan', true)
                ->first();

            // 3. Récupérer les étudiants avec leurs évaluations
            $etudiants = User::whereHas('inscriptions', function ($q) use ($specialite_id, $anneeActive) {
                $q->where('specialite_id', $specialite_id)
                    ->where('annee_academique_id', $anneeActive->id);
            })->with(['evaluations' => function ($q) use ($anneeActive) {
                $q->where('annee_academique_id', $anneeActive->id);
            }])->get();
        }

        return view('pages.bilans.index', compact(
            'etudiants',
            'modulesNormaux',
            'moduleBilan',
            'specialites',
            'anneeActive'
        ));
    }

    /**
     * Affiche le bilan individuel d'un étudiant (optionnel)
     */
    public function show($id)
    {
        $etudiant = User::with(['evaluations.module', 'inscriptions.specialite'])->findOrFail($id);
        $anneeActive = AnneeAcademique::where('statut', true)->first();

        // On utilise les méthodes de calcul définies dans le modèle User
        $moyenneS1 = $etudiant->moyenneSemestre(1, $anneeActive->id);
        $moyenneS2 = $etudiant->moyenneSemestre(2, $anneeActive->id);
        $moyenneFinale = $etudiant->calculerNoteFinale($anneeActive->id);

        return view('pages.bilans.show', compact('etudiant', 'anneeActive', 'moyenneS1', 'moyenneS2', 'moyenneFinale'));
    }


    public function genererSynthese($specialite_id)
    {
        $anneeActive = AnneeAcademique::active();

        // Récupérer les étudiants de cette spécialité
        $etudiants = User::whereHas('inscriptions', function ($q) use ($specialite_id, $anneeActive) {
            $q->where('specialite_id', $specialite_id)
                ->where('annee_academique_id', $anneeActive->id);
        })->with('evaluations.module')->get();

        foreach ($etudiants as $etudiant) {
            // 1. Calcul Moyenne Semestre 1 (Modules M1-M5)
            $moyenneS1 = $etudiant->calculerMoyennePonderee(1);

            // 2. Calcul Moyenne Semestre 2 (Modules M6-M10)
            $moyenneS2 = $etudiant->calculerMoyennePonderee(2);

            // 3. Moyenne des évaluations (30%)
            $moyenneEvaluations = ($moyenneS1 + $moyenneS2) / 2;

            // 4. Note du Bilan (70%) - Supposons qu'on la récupère
            $noteBilan = $etudiant->evaluations()->where('is_bilan', true)->first()?->note ?? 0;

            // 5. MOYENNE GÉNÉRALE FINALE
            $moyenneFinale = ($moyenneEvaluations * 0.3) + ($noteBilan * 0.7);
        }
    }
}
