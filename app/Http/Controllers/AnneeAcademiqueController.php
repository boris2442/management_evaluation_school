<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnneeAcademique;
use Illuminate\Validation\Rule;
use App\Http\Requests\AnneeAcademiqueRequest;

class AnneeAcademiqueController extends Controller
{
    /**
     * Affiche la liste des années académiques.
     */
    public function index()
    {
        // Récupère toutes les années académiques, triées par date de début décroissante
        $annees = AnneeAcademique::orderBy('date_debut', 'desc')->get();
        return view('pages.annee-academique.index', compact('annees'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        // On envoie null pour que isset($annee) dans la vue soit faux
        $annee = null;
        return view('pages.annee-academique.create', compact('annee'));
    }

    /**
     * Enregistre une nouvelle année académique.
     */
    public function store(AnneeAcademiqueRequest $request)
    {
        // La validation doit être extraite dans un FormRequest pour plus de propreté (voir Étape 5)


        AnneeAcademique::create([
            'libelle' => $request->libelle,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'statut' => false, // Par défaut, la nouvelle année n'est pas active
        ]);

        return redirect()->route('annee-academiques.index')
            ->with('success', 'L\'année académique a été créée avec succès.');
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(AnneeAcademique $anneeAcademique)
    {
        return view('annee_academiques.edit', compact('anneeAcademique'));
    }

    /**
     * Met à jour l'année académique.
     */
    public function update(Request $request, AnneeAcademique $anneeAcademique)
    {
        $request->validate([
            // La règle 'unique' doit ignorer l'enregistrement actuel lors de la modification
            'libelle' => ['required', 'string', Rule::unique('annee_academiques')->ignore($anneeAcademique->id)],
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $anneeAcademique->update($request->only('libelle', 'date_debut', 'date_fin'));

        return redirect()->route('annee-academiques.index')
            ->with('success', 'L\'année académique a été mise à jour avec succès.');
    }

    /**
     * Supprime l'année académique.
     */
    public function destroy(AnneeAcademique $anneeAcademique)
    {
        // Ajoutez une vérification pour ne pas supprimer l'année active (si vous en avez une)
        if ($anneeAcademique->statut) {
            return redirect()->route('annee-academiques.index')
                ->with('error', 'Impossible de supprimer l\'année académique active.');
        }

        $anneeAcademique->delete();

        return redirect()->route('annee-academiques.index')
            ->with('success', 'L\'année académique a été supprimée.');
    }

    /**
     * Méthode métier : Active ou désactive l'année académique.
     */
    public function toggleStatut(AnneeAcademique $anneeAcademique)
    {
        if (!$anneeAcademique->statut) {
            // 1. Désactiver toutes les autres années (Contrainte métier : une seule année active)
            AnneeAcademique::where('statut', true)->update(['statut' => false]);

            // 2. Activer l'année demandée
            $anneeAcademique->statut = true;
            $message = "L'année académique {$anneeAcademique->libelle} est maintenant **active**.";
        } else {
            // Si elle est active, nous la désactivons
            $anneeAcademique->statut = false;
            $message = "L'année académique {$anneeAcademique->libelle} a été désactivée.";
        }

        $anneeAcademique->save();

        return redirect()->route('annee-academiques.index')
            ->with('success', $message);
    }
}
