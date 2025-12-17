@extends('layouts.admin.layout-admin')

@section('content')
<div class="container mx-auto px-4 py-8 ml-0 md:ml-64 mt-16 dark:bg-neutral-900 min-h-screen">
    
    {{-- Boutons d'action --}}
    <div class="max-w-4xl mx-auto mb-4 flex justify-between no-print">
        <a href="{{ route('bilan.index') }}" class="text-gray-600 hover:text-black flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Retour à la synthèse
        </a>
        <button onclick="window.print()" class="bg-green-700 text-white px-4 py-2 rounded shadow hover:bg-green-800 transition">
            Imprimer le Relevé
        </button>
    </div>

    {{-- LE RELEVÉ DE NOTES --}}
    <div class="bg-white p-8 shadow-2xl border border-gray-300 max-w-4xl mx-auto text-black" id="printable-area">
        
        {{-- En-tête officiel --}}
        <div class="flex justify-between items-center border-b-4 border-double border-black pb-4 mb-6">
            <div class="text-center w-1/3 text-[10px] uppercase leading-tight font-bold">
                République du Cameroun<br>Paix - Travail - Patrie<br>---<br>Ministère de l'Enseignement Supérieur
            </div>
            <div class="text-center w-1/3 font-black text-2xl">
                RELEVÉ DE NOTES
            </div>
            <div class="text-center w-1/3 text-[10px] uppercase leading-tight font-bold">
                Republic of Cameroon<br>Peace - Work - Fatherland<br>---<br>Ministry of Higher Education
            </div>
        </div>

        {{-- Informations Étudiant --}}
        <div class="grid grid-cols-2 gap-4 mb-6 text-sm uppercase">
            <div class="space-y-1">
                <p>Nom / Name: <span class="font-bold">{{ $etudiant->name }}</span></p>
                <p>Spécialité: <span class="font-bold">{{ $etudiant->inscriptions->first()->specialite->nom_specialite ?? 'N/A' }}</span></p>
            </div>
            <div class="space-y-1 text-right">
                <p>Année Académique: <span class="font-bold">{{ $anneeActive->libelle }}</span></p>
                <p>ID Étudiant: <span class="font-bold">{{ str_pad($etudiant->id, 5, '0', STR_PAD_LEFT) }}</span></p>
            </div>
        </div>

        {{-- TABLEAU DES COMPÉTENCES --}}
        <table class="w-full border-collapse border-2 border-black text-xs">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-black p-2 text-left w-1/2 uppercase">Unités d'Enseignement (UE) / Modules</th>
                    <th class="border border-black p-2 w-16">Semestre</th>
                    <th class="border border-black p-2 w-16">Coef</th>
                    <th class="border border-black p-2 w-20">Note / 20</th>
                    <th class="border border-black p-2 w-24 bg-gray-300 font-bold">Pondérée</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalPoints = 0; 
                    $totalCoefs = 0; 
                @endphp
                @foreach($etudiant->evaluations->sortBy('module.semestre') as $eval)
                    @php 
                        $points = $eval->note * $eval->module->coef_module;
                        if(!$eval->module->is_bilan) {
                            $totalPoints += $points;
                            $totalCoefs += $eval->module->coef_module;
                        }
                    @endphp
                    <tr class="{{ $eval->module->is_bilan ? 'bg-green-50 font-bold' : '' }}">
                        <td class="border border-black p-2 uppercase italic">{{ $eval->module->nom_module }}</td>
                        <td class="border border-black p-2 text-center">{{ $eval->module->semestre }}</td>
                        <td class="border border-black p-2 text-center">{{ $eval->module->coef_module }}</td>

                        {{-- <td class="border border-black p-2 text-center">{{ number_format($eval->note, 2) }}</td> --}}

{{-- Remplace la ligne 71 de ton code par celle-ci --}}
<td class="border border-black p-2 text-center">
    {{ isset($eval->note) ? number_format($eval->note, 2) : 'ABS' }}
</td>


                        <td class="border border-black p-2 text-center {{ $eval->module->is_bilan ? 'bg-green-100' : 'bg-gray-50' }}">
                            {{ number_format($points, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- RÉCAPITULATIF DES MOYENNES (Le style du tableau que tu aimes) --}}
        <div class="mt-8 flex justify-end">
            <table class="w-1/2 border-collapse border-2 border-black text-xs">
                <tr>
                    <td class="border border-black p-2 font-bold uppercase bg-gray-100">Moyenne Evaluations (30%)</td>
                    <td class="border border-black p-2 text-center font-bold">
                        {{ $totalCoefs > 0 ? number_format($totalPoints / $totalCoefs, 2) : '0.00' }}
                    </td>
                </tr>
                <tr>
                    <td class="border border-black p-2 font-bold uppercase bg-[#6aa84f] text-white">Bilan Compétences (70%)</td>
                    <td class="border border-black p-2 text-center font-bold bg-[#d9ead3]">
                        @php 
                            $noteBilan = $etudiant->evaluations->where('module.is_bilan', true)->first()?->note ?? 0;
                        @endphp
                        {{ number_format($noteBilan, 2) }}
                    </td>
                </tr>
                <tr class="bg-[#f4cccc]">
                    <td class="border border-black p-2 font-black uppercase text-red-700 text-sm">MOYENNE GÉNÉRALE</td>
                    <td class="border border-black p-2 text-center font-black text-red-700 text-sm italic">
                        {{ number_format($etudiant->calculerNoteFinale($anneeActive->id), 2) }} / 20
                    </td>
                </tr>
            </table>
        </div>

        {{-- Mention et Signatures --}}
        <div class="mt-12 flex justify-between items-start italic">
            <div class="text-center w-1/3">
                <p class="underline">L'Étudiant(e)</p>
                <div class="h-20"></div>
                <p class="text-[10px]">Lu et approuvé</p>
            </div>
            <div class="text-center w-1/3">
                <p class="font-bold uppercase">Décision:</p>
                <div class="mt-2 border-2 border-black p-2 font-black text-lg uppercase">
                    {{ $etudiant->calculerNoteFinale($anneeActive->id) >= 10 ? 'ADMIS(E)' : 'ÉCHEC' }}
                </div>
            </div>
            <div class="text-center w-1/3">
                <p class="underline">Le Directeur Académique</p>
                <div class="h-20"></div>
                <p class="text-[10px]">Fait à .................., le {{ date('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none; }
        body { background: white; margin: 0; }
        .container { max-width: 100%; margin: 0; padding: 0; }
        #printable-area { box-shadow: none; border: none; width: 100%; }
    }
</style>
@endsection
