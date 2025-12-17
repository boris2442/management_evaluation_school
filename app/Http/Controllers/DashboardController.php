<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Models\Inscription;
use Illuminate\Http\Request;
use App\Models\AnneeAcademique;

class DashboardController extends Controller
{
 public function index(Request $request)
    {
        // 1. Déterminer l'année à analyser
        $anneeActive = $this->getAnneeAnalyse($request);

        if (!$anneeActive) {
            return view('tableau-de-bord')->with('error', 'Aucune année académique trouvée.');
        }

        // 2. Appeler les fonctions spécifiques pour chaque bloc de données
        $statsGlobales = $this->getStatsGlobales($anneeActive->id);
        $performance   = $this->getPerformanceAcademique($anneeActive->id);
        $demographie   = $this->getDemographie($anneeActive->id);
        $repartition   = $this->getRepartitionParSpecialite($anneeActive->id);

        return view('tableau-de-bord', compact(
            'anneeActive',
            'statsGlobales',
            'performance',
            'demographie',
            'repartition'
        ));
    }

    // --- FONCTIONS DE CALCUL ---

    private function getAnneeAnalyse($request)
    {
        return $request->annee_id 
            ? AnneeAcademique::find($request->annee_id) 
            : AnneeAcademique::where('statut', true)->first();
    }

    private function getStatsGlobales($anneeId)
    {
        return [
            'total_etudiants' => Inscription::where('annee_academique_id', $anneeId)->count(),
            'total_enseignants' => User::where('role', 'Enseignant')->count(),
            'total_modules' => Module::count(),
        ];
    }

    private function getPerformanceAcademique($anneeId)
    {
        $inscriptions = Inscription::where('annee_academique_id', $anneeId)
            ->with('etudiant.evaluations.module')
            ->get();

        $admis = 0;
        $totalNotes = 0;
        $majors = [];

        foreach ($inscriptions as $ins) {
            $moyenne = $ins->etudiant->calculerNoteFinale($anneeId);
            $totalNotes += $moyenne;
            
            if ($moyenne >= 12) $admis++; // Seuil de réussite à 12/20

            // Préparation pour le Top 5
            $majors[] = [
                'nom' => $ins->etudiant->name,
                'moyenne' => round($moyenne, 2),
                'specialite' => $ins->specialite->nom_specialite
            ];
        }

        // Trier pour avoir les meilleurs en premier
        usort($majors, fn($a, $b) => $b['moyenne'] <=> $a['moyenne']);

        return [
            'taux_reussite' => $inscriptions->count() > 0 ? round(($admis / $inscriptions->count()) * 100, 2) : 0,
            'moyenne_generale' => $inscriptions->count() > 0 ? round($totalNotes / $inscriptions->count(), 2) : 0,
            'top_etudiants' => array_slice($majors, 0, 5) // On ne garde que les 5 premiers
        ];
    }

    private function getDemographie($anneeId)
    {
        return Inscription::where('annee_academique_id', $anneeId)
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->selectRaw('users.sexe, count(*) as total')
            ->groupBy('users.sexe')
            ->get();
    }

    private function getRepartitionParSpecialite($anneeId)
    {
        return Inscription::where('annee_academique_id', $anneeId)
            ->join('specialites', 'inscriptions.specialite_id', '=', 'specialites.id')
            ->selectRaw('specialites.nom_specialite as label, count(*) as total')
            ->groupBy('specialites.nom_specialite')
            ->get();
    }
}
