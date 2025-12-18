@extends('layouts.admin.layout-admin')

@section('content')
<section>
<div class="ml-0 md:ml-64 min-h-screen p-4 md:p-8 mt-16  dark:bg-neutral-950 transition-all">
    
    {{-- ENTÊTE OFFICIEL STYLE PAPIER --}}
    <div class="max-w-6xl mx-auto bg-white dark:bg-neutral-900 shadow-xl rounded-t-3xl border-t-8 border-blue-900 dark:border-blue-600 p-8">
        <div class="flex justify-between items-start border-b-2 border-gray-100 dark:border-neutral-800 pb-6 mb-8">
            <div class="text-center w-1/3 space-y-1">
                <p class="text-[10px] font-bold uppercase dark:text-gray-300">République du Cameroun</p>
                <p class="text-[9px] italic dark:text-gray-400">Paix - Travail - Patrie</p>
                <div class="h-1 w-10 bg-blue-900 mx-auto my-1"></div>
                <p class="text-[10px] font-bold uppercase dark:text-gray-300">Institut Supérieur de Technologie du Golfe</p>
                <p class="text-[9px] dark:text-gray-400">Autorisation N° 24/0056/MINESUP</p>
            </div>

            <div class="text-center w-1/3">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-2xl">
                    <h1 class="text-xl font-black text-blue-900 dark:text-blue-400 uppercase tracking-tighter">Tableau de Bord</h1>
                    {{-- <p class="text-xs font-bold text-gray-500 uppercase">{{ $anneeActive->nom_annee ?? '2024-2025' }}</p> --}}

                    <p class="text-xs font-bold text-gray-500 uppercase">
    Année Académique : 
    {{ \Carbon\Carbon::parse($anneeActive->date_debut)->format('Y') }}-{{ \Carbon\Carbon::parse($anneeActive->date_fin)->format('Y') }}
</p>
                </div>
            </div>

            <div class="text-center w-1/3 space-y-1 text-right">
                <p class="text-[10px] font-bold uppercase dark:text-gray-300">Republic of Cameroon</p>
                <p class="text-[9px] italic dark:text-gray-400">Peace - Work - Fatherland</p>
                <div class="h-1 w-10 bg-blue-900 ml-auto my-1"></div>
                <p class="text-[10px] font-bold uppercase dark:text-gray-300 text-blue-900 dark:text-blue-400">Student Results Portal</p>
            </div>
        </div>

        {{-- INFO ÉTUDIANT & RÉSUMÉ --}}
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div class="space-y-2">
                <p class="text-sm dark:text-gray-400">Étudiant: <span class="font-black text-lg text-black dark:text-white uppercase">{{ $student->name }}</span></p>
                <p class="text-xs bg-gray-100 dark:bg-neutral-800 px-3 py-1 rounded-full inline-block dark:text-gray-300 uppercase">
                    Spécialité: <strong>{{ $student->inscriptions->first()->specialite->nom_specialite ?? 'N/A' }}</strong>
                </p>
            </div>
            
            <div class="grid grid-cols-2 gap-4 w-full md:w-auto">
                <div class="bg-neutral-900 text-white p-4 rounded-2xl text-center min-w-[140px]">
                    <p class="text-[10px] uppercase font-bold text-gray-400">Moyenne Finale</p>
                    <p class="text-2xl font-black">{{ number_format($moyenneGenerale, 2) }}</p>
                </div>
                <div class="bg-blue-600 text-white p-2 rounded-2xl text-center max-w-[140px]">
                    <p class="text-[10px] uppercase font-bold text-blue-200">Décision</p>
                    <p class="text-xl font-black uppercase">{{ $moyenneGenerale >= 10 ? 'A' : 'R' }}</p>
                </div>
            </div>
        </div>

        {{-- LE TABLEAU COMPLEXE --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-neutral-800">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-neutral-800 text-[11px] uppercase font-bold text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-neutral-700">
                        <th class="p-4">Unités d'Enseignement (UE)</th>
                        <th class="p-4">Chargé du Cours</th>
                        <th class="p-4 text-center">Semestre</th>
                        <th class="p-4 text-center">Coeff</th>
                        <th class="p-4 text-center">Note/20</th>
                        <th class="p-4 text-right">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                    @foreach($student->evaluations->sortBy('module.semestre') as $eval)
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition group">
                        <td class="p-4">
                            <span class="block font-bold text-gray-800 dark:text-gray-200 uppercase text-xs">{{ $eval->module->nom_module }}</span>
                            <span class="text-[10px] text-blue-600 dark:text-blue-400 font-medium">UE Fondamentale</span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-gray-200 dark:bg-neutral-700 rounded-full flex items-center justify-center text-[10px] font-bold uppercase">
                                    {{ substr($eval->module->enseignants->first()->name ?? '?', 0, 1) }}
                                </div>
                                <span class="text-xs font-medium dark:text-gray-400">
                                    {{ $eval->module->enseignants->first()->name ?? 'Non assigné' }}
                                </span>
                            </div>
                        </td>
                        <td class="p-4 text-center text-xs dark:text-gray-500">Semestre {{ $eval->module->semestre }}</td>
                        <td class="p-4 text-center font-bold text-gray-600 dark:text-gray-400 text-xs">x{{ $eval->module->coef_module }}</td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-lg font-black text-sm {{ $eval->note >= 10 ? 'text-green-600 bg-green-50 dark:bg-green-900/20' : 'text-red-600 bg-red-50 dark:bg-red-900/20' }}">
                                {{ number_format($eval->note, 2) }}
                            </span>
                        </td>
                        <td class="p-4 text-right font-bold text-gray-900 dark:text-white text-xs">
                            {{ number_format($eval->note * $eval->module->coef_module, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- SIGNATURE & BAS DE PAGE --}}
        <div class="mt-8 pt-6 border-t border-dashed border-gray-200 dark:border-neutral-800 flex justify-between items-center">
            <p class="text-[9px] text-gray-400 italic italic uppercase">Généré le {{ date('d/m/Y à H:i') }} - Document authentique</p>
            <div class="text-right">
                <button onclick="window.print()" class="bg-blue-900 text-white px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-black transition no-print">
                    Télécharger le Relevé PDF
                </button>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
