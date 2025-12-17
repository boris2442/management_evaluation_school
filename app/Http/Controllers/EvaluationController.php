<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Models\Evaluation;
use App\Models\Specialite;
use Illuminate\Http\Request;
use App\Models\AnneeAcademique;
use App\Http\Controllers\Controller;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $anneeActive = AnneeAcademique::where('statut', true)->first();
        $specialites = Specialite::all();

        // On récupère les filtres de la requête
        $specialite_id = $request->specialite_id;
        $module_id = $request->module_id;
        $semestre = $request->semestre;

        $modules = [];
        $etudiants = [];

        // Si une spécialité est choisie, on charge ses modules
        if ($specialite_id) {
            $modules = Module::where('specialite_id', $specialite_id)->get();
        }

        // Si tout est choisi, on récupère les étudiants et leurs notes existantes
        if ($specialite_id && $module_id && $semestre) {
            $etudiants = User::whereHas('inscriptions', function ($q) use ($specialite_id, $anneeActive) {
                $q->where('specialite_id', $specialite_id)
                    ->where('annee_academique_id', $anneeActive->id);
            })->with(['evaluations' => function ($q) use ($module_id, $semestre, $anneeActive) {
                $q->where('module_id', $module_id)
                    ->where('semestre', $semestre)
                    ->where('annee_academique_id', $anneeActive->id);
            }])->get();
        }

        return view('pages.evaluations.index', compact('specialites', 'modules', 'etudiants', 'anneeActive'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'notes' => 'required|array',
            'notes.*' => 'nullable|numeric|min:0|max:20', // Contrainte 8
        ]);

        foreach ($request->notes as $etudiant_id => $note) {
            if ($note !== null) {
                Evaluation::updateOrCreate(
                    [
                        'user_id' => $etudiant_id,
                        'module_id' => $request->module_id,
                        'annee_academique_id' => $request->annee_academique_id,
                        'semestre' => $request->semestre,
                    ],
                    ['note' => $note]
                );
            }
        }

        return redirect()->back()->with('success', 'Notes enregistrées avec succès.');
    }
}
