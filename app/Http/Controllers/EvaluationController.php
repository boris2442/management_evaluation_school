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

    // public function index(Request $request)
    // {
    //     $anneeActive = AnneeAcademique::where('statut', true)->first();
    //     $specialites = Specialite::all();

    //     // On récupère les filtres de la requête
    //     $specialite_id = $request->specialite_id;
    //     $module_id = $request->module_id;
    //     $semestre = $request->semestre;

    //     $modules = [];
    //     $etudiants = [];

    //     // Si une spécialité est choisie, on charge ses modules
    //     if ($specialite_id) {
    //         $modules = Module::where('specialite_id', $specialite_id)->get();
    //     }

    //     // Si tout est choisi, on récupère les étudiants et leurs notes existantes
    //     if ($specialite_id && $module_id && $semestre) {
    //         $etudiants = User::whereHas('inscriptions', function ($q) use ($specialite_id, $anneeActive) {
    //             $q->where('specialite_id', $specialite_id)
    //                 ->where('annee_academique_id', $anneeActive->id);
    //         })->with(['evaluations' => function ($q) use ($module_id, $semestre, $anneeActive) {
    //             $q->where('module_id', $module_id)
    //                 ->where('semestre', $semestre)
    //                 ->where('annee_academique_id', $anneeActive->id);
    //         }])->get();
    //     }

    //     return view('pages.evaluations.index', compact('specialites', 'modules', 'etudiants', 'anneeActive'));
    // }



    public function index(Request $request)

    {

        $user = auth()->user();

        $anneeActive = AnneeAcademique::where('statut', true)->first();



        // 1. Filtrage des Spécialités

        if ($user->role === 'Enseignant') {

            // Le prof ne voit que les spécialités où il enseigne au moins un module

            $specialites = Specialite::whereHas('modules', function ($q) use ($user) {

                $q->whereIn('modules.id', $user->modulesEnseignes->pluck('id'));
            })->get();
        } else {

            // L'admin voit tout

            $specialites = Specialite::all();
        }



        // Récupération des filtres

        $specialite_id = $request->specialite_id;

        $module_id = $request->module_id;

        $semestre = $request->semestre;



        $modules = [];

        $etudiants = [];



        // 2. Chargement des modules filtrés par spécialité ET par attribution (si prof)

        if ($specialite_id) {

            $query = Module::where('specialite_id', $specialite_id);



            if ($user->role === 'Enseignant') {

                // On ne montre que SES modules au sein de cette spécialité

                $query->whereIn('id', $user->modulesEnseignes->pluck('id'));
            }



            $modules = $query->get();
        }



        // 3. Récupération des étudiants (Ta logique originale sécurisée)

        if ($specialite_id && $module_id && $semestre) {



            // Sécurité supplémentaire : Empêcher un prof de forcer l'ID d'un module d'un autre via l'URL

            if ($user->role === 'Enseignant' && !$user->modulesEnseignes->contains($module_id)) {

                abort(403, "Vous n'enseignez pas ce module.");
            }



            $etudiants = User::whereHas('inscriptions', function ($q) use ($specialite_id, $anneeActive) {

                $q->where('specialite_id', $specialite_id)

                    ->where('annee_academique_id', $anneeActive->id);
            })->with(['evaluations' => function ($q) use ($module_id, $semestre, $anneeActive) {

                $q->where('module_id', $module_id)

                    ->where('semestre', $semestre)

                    ->where('annee_academique_id', $anneeActive->id);
            }])->get();
        }
        //dd($specialites, $modules); // Retire les // au début pour tester


        return view('pages.evaluations.index', compact('specialites', 'modules', 'etudiants', 'anneeActive'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'notes' => 'required|array',
    //         'notes.*' => 'nullable|numeric|min:0|max:20', // Contrainte 8
    //     ]);

    //     foreach ($request->notes as $etudiant_id => $note) {
    //         if ($note !== null) {
    //             Evaluation::updateOrCreate(
    //                 [
    //                     'user_id' => $etudiant_id,
    //                     'module_id' => $request->module_id,
    //                     'annee_academique_id' => $request->annee_academique_id,
    //                     'semestre' => $request->semestre,
    //                 ],
    //                 ['note' => $note]
    //             );
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Notes enregistrées avec succès.');
    // }



    public function store(Request $request)
    {
        $user = auth()->user();

        // SÉCURITÉ : Empêcher un prof de tricher à l'enregistrement
        if ($user->role === 'Enseignant' && !$user->modulesEnseignes->contains($request->module_id)) {
            return redirect()->back()->with('error', "Action non autorisée : ce module ne vous est pas attribué.");
        }

        $request->validate([
            'notes' => 'required|array',
            'notes.*' => 'nullable|numeric|min:0|max:20',
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
