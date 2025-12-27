<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Models\AnneeAcademique;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ImportExportUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return back()->with('success', 'Importation réussie !');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return back()->with(
                'error',
                'Erreur ligne ' . $failures[0]->row() . ' : ' . $failures[0]->errors()[0]
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur technique : ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $filters = [
            'role'        => $request->role,
            'specialite'  => $request->specialite,
            'annee_id'    => $request->annee_id,
        ];

        $annee = $request->annee_id
            ? AnneeAcademique::find($request->annee_id)
            : AnneeAcademique::where('statut', true)->first();

        // ✅ EXPORT PDF
        if ($request->format === 'pdf') {

            $users = (new UsersExport($filters))->query()->get();

            $pdf = Pdf::loadView('pages.users.exports.pdf_template', [
                'users' => $users,
                'annee' => $annee,
                'role'  => $request->role ?? 'Utilisateurs'
            ])->setPaper('a4', 'landscape');

            return $pdf->download("Liste_{$annee->libelle}.pdf");
        }

        // ✅ EXPORT EXCEL
        return Excel::download(
            new UsersExport($filters),
            "Export_{$annee->libelle}.xlsx"
        );
    }
}
