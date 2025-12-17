<?php

namespace App\Http\Controllers;

use App\Models\Specialite;
use Illuminate\Http\Request;
use App\Http\Requests\SpecialiteRequest;

class SpecialiteController extends Controller
{
    /**
     * Affiche la liste des spécialités.
     */
    public function index()
    {
        $specialites = Specialite::orderBy('nom_specialite')->get();
        return view('pages.specialites.index', compact('specialites'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        $specialite = null; // Pour le formulaire réutilisable
        return view('pages.specialites.create', compact('specialite'));
    }

    /**
     * Enregistre une nouvelle spécialité.
     */
    public function store(SpecialiteRequest $request)
    {
        Specialite::create($request->validated());

        return redirect()->route('specialites.index')
            ->with('success', 'La spécialité a été créée avec succès.');
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(Specialite $specialite)
    {
        return view('pages.specialites.create', compact('specialite'));
    }

    /**
     * Met à jour la spécialité.
     */
    public function update(SpecialiteRequest $request, Specialite $specialite)
    {
        $specialite->update($request->validated());

        return redirect()->route('specialites.index')
            ->with('success', 'La spécialité a été mise à jour avec succès.');
    }

    /**
     * Supprime la spécialité.
     */
    public function destroy(Specialite $specialite)
    {
        // Laravel empêchera automatiquement la suppression si des modules sont rattachés (Foreign Key Constraint)
        try {
            $specialite->delete();
            return redirect()->route('specialites.index')
                ->with('success', 'La spécialité a été supprimée.');
        } catch (\Exception $e) {
            return redirect()->route('specialites.index')
                ->with('error', 'Impossible de supprimer cette spécialité car elle contient des modules.');
        }
    }
}
