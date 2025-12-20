<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserController extends Controller
{
    use SoftDeletes; // 2. IMPORTANT : L'utilisation du trait

    protected $dates = ['deleted_at']; // Optionnel mais recommandé pour Carbon

    // Liste des utilisateurs actifs
    // public function index(Request $request)
    // {
    //     $query = User::query();

    //     // Logique de recherche (Maquette recherche)
    //     if ($request->filled('search')) {
    //         $query->where('name', 'like', '%' . $request->search . '%')
    //               ->orWhere('email', 'like', '%' . $request->search . '%');
    //     }

    //     $users = $query->latest()->paginate(10);
    //     return view('pages.users.index', compact('users'));
    // }



    public function index(Request $request)
    {
        $query = User::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->latest()->paginate(10)->appends($request->only('search'));

        // RÉCUPÉRATION DYNAMIQUE DES VALEURS ENUM
        // Remplace DB::select(DB::raw(...)) par DB::select(...)

        // 1. Pour les Rôles
        $columnInfo = DB::select("SHOW COLUMNS FROM users WHERE Field = 'role'");
        $roles = [];

        if (!empty($columnInfo)) {
            $type = $columnInfo[0]->Type; // ex: enum('Admin','Etudiant')
            preg_match('/^enum\((.*)\)$/', $type, $matches);
            foreach (explode(',', $matches[1]) as $value) {
                $roles[] = trim($value, "'");
            }
        }

        // 2. Pour le Sexe
        $columnSexe = DB::select("SHOW COLUMNS FROM users WHERE Field = 'sexe'");
        $genres = [];

        if (!empty($columnSexe)) {
            $typeSexe = $columnSexe[0]->Type;
            preg_match('/^enum\((.*)\)$/', $typeSexe, $matchesSexe);
            foreach (explode(',', $matchesSexe[1]) as $value) {
                $genres[] = trim($value, "'");
            }
        }
        return view('pages.users.index', compact('users', 'roles', 'genres'));
    }

    // Mise à jour (Sexe, Email, Rôle)
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'sexe' => 'required',
            'role' => 'required|string',
        ]);

        $user->update($validated);
        return back()->with('success', 'Utilisateur mis à jour avec succès');
    }

    // Suppression douce (Soft Delete)
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('info', 'Utilisateur déplacé vers la corbeille');
    }

    // Vue de la Corbeille
    public function trash()
    {
        $deletedUsers = User::onlyTrashed()->get();
        return view('pages.users.trash.trash', compact('deletedUsers'));
    }

    // Restauration
    public function restore($id)
    {
        User::withTrashed()->where('id', $id)->restore();
        return back()->with('success', 'Utilisateur restauré');
    }
    // Ajoute ceci à la fin de ton UserController
public function bulkDestroy(Request $request)
{
    $ids = $request->input('ids', []);
    if (!empty($ids)) {
        User::whereIn('id', $ids)->delete();
        return redirect()->route('users.index')->with('success', count($ids) . ' utilisateurs déplacés vers la corbeille');
    }
    return back()->with('error', 'Aucun utilisateur sélectionné');
}

// Suppression définitive d'un seul utilisateur
public function forceDelete($id)
{
    User::withTrashed()->findOrFail($id)->forceDelete();
    return back()->with('success', 'Utilisateur supprimé définitivement.');
}

// Restauration groupée
public function bulkRestore(Request $request)
{
    $ids = $request->input('ids', []);
    if (!empty($ids)) {
        User::withTrashed()->whereIn('id', $ids)->restore();
        return back()->with('success', count($ids) . ' utilisateurs restaurés.');
    }
    return back();
}

// Suppression définitive groupée
public function bulkForceDelete(Request $request)
{
    $ids = $request->input('ids', []);
    if (!empty($ids)) {
        User::withTrashed()->whereIn('id', $ids)->forceDelete();
        return back()->with('success', count($ids) . ' utilisateurs supprimés définitivement.');
    }
    return back();
}
}
