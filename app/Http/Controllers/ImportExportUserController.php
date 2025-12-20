<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportUserController extends Controller
{
    /**
     * Gère l'importation massive des utilisateurs.
     */
   public function store(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv' // On garde ça simple
    ]);

    try {
        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\UsersImport, $request->file('file'));
        return back()->with('success', 'Importation réussie !');
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        // On récupère la toute première erreur de validation pour comprendre
        return back()->with('error', 'Erreur ligne ' . $failures[0]->row() . ' : ' . $failures[0]->errors()[0]);
    } catch (\Exception $e) {
        // C'est ici qu'on verra si c'est un problème de base de données
        return back()->with('error', 'Erreur technique : ' . $e->getMessage());
    }
}
}
