<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnneeAcademique;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
public function index()
    {
        $student = Auth::user();
        $anneeActive = AnneeAcademique::where('statut', true)->first();

        // Récupérer toutes les évaluations de l'étudiant pour l'année en cours
        $evaluations = $student->evaluations()
            ->where('annee_academique_id', $anneeActive->id)
            ->with('module')
            ->get();

        // Séparer les notes (S1, S2 et Bilan)
        $notesS1 = $evaluations->where('semestre', 1)->where('module.is_bilan', false);
        $notesS2 = $evaluations->where('semestre', 2)->where('module.is_bilan', false);
        $noteBilan = $evaluations->where('module.is_bilan', true)->first();

        // Calcul des moyennes
        $moyS1 = $notesS1->avg('note') ?? 0;
        $moyS2 = $notesS2->avg('note') ?? 0;
        $moyModules = ($moyS1 + $moyS2) / 2;
        
        $valeurBilan = $noteBilan ? $noteBilan->note : 0;
        
        // Calcul Final : (Moyenne Modules * 30%) + (Bilan * 70%)
        $moyenneGenerale = ($moyModules * 0.3) + ($valeurBilan * 0.7);

        return view('pages.student.notes', compact(
            'student', 'notesS1', 'notesS2', 'noteBilan', 
            'moyS1', 'moyS2', 'moyenneGenerale', 'anneeActive'
        ));
    }
}
