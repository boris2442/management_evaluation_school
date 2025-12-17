@extends('layouts.admin.layout-admin')

@section('content')
<div class="container mx-auto px-4 py-8 ml-0 md:ml-64 mt-16 dark:bg-neutral-900 min-h-screen">
    
    {{-- En-tête de la page --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white uppercase">Synthèse Annuelle des Résultats</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Année Académique : <span class="font-bold underline">{{ $anneeActive->libelle }}</span></p>
        </div>
        
        {{-- Bouton d'impression (Optionnel pour plus de professionnalisme) --}}
        <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-black transition no-print">
            Imprimer le Bilan
        </button>
    </div>

    {{-- Filtre par Spécialité --}}
    <div class="bg-white dark:bg-neutral-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-neutral-700 mb-8 no-print">
        <form action="{{ route('bilan.index') }}" method="GET" class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block mb-2 text-xs font-bold uppercase text-gray-500">Filtrer par Spécialité</label>
                <select name="specialite_id" class="w-full rounded-lg border-gray-300 dark:bg-neutral-700 dark:text-white focus:ring-green-500" onchange="this.form.submit()">
                    <option value="">Sélectionnez une spécialité...</option>
                    @foreach($specialites as $s)
                        <option value="{{ $s->id }}" {{ request('specialite_id') == $s->id ? 'selected' : '' }}>{{ $s->nom_specialite }}</option>
                    @endforeach
                </select>
            </div>
            <noscript><button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrer</button></noscript>
        </form>
    </div>

    @if(request('specialite_id') && count($etudiants) > 0)
    {{-- TABLEAU STYLE PROFESSIONNEL --}}
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-xl overflow-x-auto border border-green-800">
        <table class="w-full border-collapse text-[11px] uppercase tracking-tighter">
            <thead>
                {{-- Ligne 1 : Les Grands Titres --}}
                <tr class="bg-[#d9ead3] text-gray-800 border-b border-green-800">
                    <th rowspan="2" class="border border-green-800 p-2 w-8">N°</th>
                    <th rowspan="2" class="border border-green-800 p-2 min-w-[200px]">Nom et prénoms / names and first names</th>
                    <th colspan="{{ $modulesNormaux->where('semestre', 1)->count() + 1 }}" class="border border-green-800 p-1 text-center">Évaluations semestrielles / semester evaluations (30%)</th>
                    <th colspan="{{ $modulesNormaux->where('semestre', 2)->count() + 1 }}" class="border border-green-800 p-1 text-center">Semestre 2 / semestres</th>
                    <th rowspan="2" class="border border-green-800 bg-[#6aa84f] text-white p-2 w-24">MOY/20 Bilan des compétences (70%) skills assessment</th>
                    <th rowspan="2" class="border border-green-800 bg-[#f4cccc] text-red-700 p-2 w-24">MOY. GEN. (100%) en (rouge)</th>
                </tr>
                {{-- Ligne 2 : Sous-titres Semestres et Codes Modules --}}
                <tr class="bg-[#d9ead3] text-gray-700 border-b border-green-800">
                    {{-- Semestre 1 --}}
                    @foreach($modulesNormaux->where('semestre', 1) as $mod)
                        <th class="border border-green-800 p-1 text-center w-10" title="{{ $mod->nom_module }}">{{ $mod->code_module ?? 'M'.$loop->iteration }}</th>
                    @endforeach
                    <th class="border border-green-800 p-1 text-center bg-[#b6d7a8] font-bold italic">MOY/20 EVAL1</th>

                    {{-- Semestre 2 --}}
                    @foreach($modulesNormaux->where('semestre', 2) as $mod)
                        <th class="border border-green-800 p-1 text-center w-10" title="{{ $mod->nom_module }}">{{ $mod->code_module ?? 'M'.($loop->iteration + $modulesNormaux->where('semestre', 1)->count()) }}</th>
                    @endforeach
                    <th class="border border-green-800 p-1 text-center bg-[#b6d7a8] font-bold italic">MOY/20 EVAL2</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-green-800">
                @foreach($etudiants as $index => $etudiant)
                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700 transition">
                    <td class="border border-green-800 p-2 text-center font-bold">{{ $index + 1 }}</td>
                    {{-- <td class="border border-green-800 p-2 font-bold text-left">{{ $etudiant->name }}</td> --}}

<td class="border border-green-800 p-2 font-bold text-left">
    <a href="{{ route('bilan.show', $etudiant->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">
        {{ $etudiant->name }}
    </a>
</td>


                    {{-- Notes Semestre 1 --}}
                    @php $sommeS1 = 0; $coefS1 = 0; @endphp
                    @foreach($modulesNormaux->where('semestre', 1) as $mod)
                        @php 
                            $note = $etudiant->evaluations->where('module_id', $mod->id)->first()?->note;
                            if($note) { $sommeS1 += ($note * $mod->coef_module); $coefS1 += $mod->coef_module; }
                        @endphp
                        <td class="border border-green-800 p-2 text-center">{{ $note ?? '' }}</td>
                    @endforeach
                    <td class="border border-green-800 p-2 text-center font-bold bg-[#edf2f7]">
                        {{ $coefS1 > 0 ? number_format($sommeS1 / $coefS1, 2) : '0.00' }}
                    </td>

                    {{-- Notes Semestre 2 --}}
                    @php $sommeS2 = 0; $coefS2 = 0; @endphp
                    @foreach($modulesNormaux->where('semestre', 2) as $mod)
                        @php 
                            $note = $etudiant->evaluations->where('module_id', $mod->id)->first()?->note;
                            if($note) { $sommeS2 += ($note * $mod->coef_module); $coefS2 += $mod->coef_module; }
                        @endphp
                        <td class="border border-green-800 p-2 text-center">{{ $note ?? '' }}</td>
                    @endforeach
                    <td class="border border-green-800 p-2 text-center font-bold bg-[#edf2f7]">
                        {{ $coefS2 > 0 ? number_format($sommeS2 / $coefS2, 2) : '0.00' }}
                    </td>

                    {{-- Note Bilan des Compétences (70%) --}}
                    <td class="border border-green-800 p-2 text-center font-bold bg-[#6aa84f] text-white">
                        {{ $etudiant->evaluations->where('module.is_bilan', true)->first()?->note ?? '0.00' }}
                    </td>

                    {{-- Moyenne Générale Finale --}}
                    <td class="border border-green-800 p-2 text-center font-bold text-red-600 bg-[#f4cccc] text-sm">
                        {{ number_format($etudiant->calculerNoteFinale($anneeActive->id), 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{-- Légende --}}
    <div class="mt-4 text-[10px] text-gray-500 italic">
        * Ce document est une synthèse automatique. Les calculs respectent la pondération (30% évaluations modules, 70% bilan de compétences).
    </div>

    @elseif(request('specialite_id'))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-10">
            <p class="text-yellow-700 italic">Aucun étudiant inscrit ou aucune note disponible pour cette spécialité.</p>
        </div>
    @else
        <div class="flex flex-col items-center justify-center mt-20 opacity-30">
            <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <p class="text-xl font-bold uppercase">Sélectionnez une spécialité pour générer le bilan</p>
        </div>
    @endif
</div>

{{-- CSS pour l'impression --}}
<style>
    @media print {
        .no-print { display: none; }
        body { background: white; }
        .container { margin: 0; width: 100%; max-width: 100%; padding: 0; }
        table { font-size: 9px; }
    }
</style>
@endsection
