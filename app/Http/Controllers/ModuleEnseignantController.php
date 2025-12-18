<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModuleEnseignantController extends Controller
{
    public function index()
    {
        // On récupère uniquement les utilisateurs qui ont le rôle 'Enseignant'
        $enseignants = User::where('role', 'Enseignant')->get();
        $modules = Module::all();

        return view('pages.admin.affectations.index', compact('enseignants', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'module_ids' => 'required|array',
            'module_ids.*' => 'exists:modules,id',
        ]);

        $enseignant = User::find($request->user_id);

        // La méthode sync() est magique : elle ajoute les nouveaux liens 
        // et supprime les anciens qui ne sont plus cochés.
        $enseignant->modulesEnseignes()->sync($request->module_ids);

        return redirect()->back()->with('success', 'Affectations mises à jour avec succès.');
    }


    public function destroy($id)
{
    $enseignant = User::findOrFail($id);
    // On détache tous les modules liés à cet enseignant
    $enseignant->modulesEnseignes()->detach();

    return redirect()->back()->with('success', 'Toutes les affectations ont été retirées pour cet enseignant.');
}
}
