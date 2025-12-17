<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Models\Specialite;
use Illuminate\Http\Request;
use App\Http\Requests\ModuleStoreRequest;
use App\Http\Requests\ModuleUpdateRequest;

class ModuleController extends Controller
{
    /**
     * Affiche la liste des modules.
     */
    public function index()
    {
        // Charge les relations nécessaires pour l'affichage (specialite et enseignants)
        $modules = Module::with(['specialite', 'enseignants'])
            ->orderBy('specialite_id')
            ->orderBy('ordre')
            ->get();

        return view('pages.modules.index', compact('modules'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau module.
     */
    public function create()
    {
        $specialites = Specialite::orderBy('nom_specialite')->get();
        // Optionnel : Récupérer uniquement les utilisateurs ayant le rôle 'enseignant'
        $enseignants = User::where('role', 'enseignant')->orderBy('name')->get();
        // Crée un nouvel objet Module vide (pour la logique d'édition)
        $module = new Module();
        return view('pages.modules.create', compact('specialites', 'enseignants', 'module'));
    }

    /**
     * Stocke un nouveau module dans la base de données.
     */
    public function store(ModuleStoreRequest $request)
    {
        $module = Module::create($request->validated());

        // Gérer l'affectation des enseignants (relation many-to-many)
        if ($request->has('enseignants')) {
            $module->enseignants()->attach($request->enseignants);
        }

        return redirect()->route('modules.index')->with('success', 'Le module ' . $module->nom_module . ' a été créé avec succès.');
    }

    /**
     * Affiche le formulaire de modification d'un module.
     */
    public function edit(Module $module)
    {
        $specialites = Specialite::orderBy('nom_specialite')->get();
        // Optionnel : Récupérer uniquement les utilisateurs ayant le rôle 'enseignant'
        $enseignants = User::where('role', 'enseignant')->orderBy('name')->get();

        // Récupérer les IDs des enseignants déjà assignés pour pré-cocher les cases
        $assignedEnseignantsIds = $module->enseignants->pluck('id')->toArray();

        return view('pages.modules.create', compact('module', 'specialites', 'enseignants', 'assignedEnseignantsIds'));
    }

    /**
     * Met à jour le module spécifié.
     */
    public function update(ModuleUpdateRequest $request, Module $module)
    {
        $module->update($request->validated());

        // Gérer la synchronisation des enseignants (détache les anciens, attache les nouveaux)
        $module->enseignants()->sync($request->enseignants ?? []);

        return redirect()->route('modules.index')->with('success', 'Le module ' . $module->nom_module . ' a été mis à jour avec succès.');
    }

    /**
     * Supprime le module spécifié.
     */
    public function destroy(Module $module)
    {
        // Laravel gère l'effacement des liaisons dans la table pivot grâce au `onDelete('cascade')` dans la migration

        try {
            $module->delete();
            return redirect()->route('modules.index')->with('success', 'Le module a été supprimé.');
        } catch (\Exception $e) {
            // Dans le cas où il y aurait d'autres contraintes (non visible ici, comme des notes), on peut avoir un message d'erreur.
            return redirect()->route('modules.index')->with('error', 'Impossible de supprimer ce module car il est utilisé ailleurs.');
        }
    }
}
