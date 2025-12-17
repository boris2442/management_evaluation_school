<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserController extends Controller
{
use SoftDeletes; // 2. IMPORTANT : L'utilisation du trait

    protected $dates = ['deleted_at']; // Optionnel mais recommandé pour Carbon

// Liste des utilisateurs actifs
    public function index(Request $request)
    {
        $query = User::query();

        // Logique de recherche (Maquette recherche)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    // Mise à jour (Sexe, Email, Rôle)
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'sexe' => 'required|in:M,F',
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
}
