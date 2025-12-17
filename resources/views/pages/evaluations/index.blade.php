@extends('layouts.admin.layout-admin')

@section('content')
<div class="container mx-auto px-4 py-8 ml-0 md:ml-64 mt-16 dark:bg-neutral-900 min-h-screen">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Saisie des Notes - {{ $anneeActive->libelle }}</h1>

    {{-- Formulaire de FILTRAGE --}}
    <div class="bg-white dark:bg-neutral-800 p-6 rounded-xl shadow mb-8">
        <form action="{{ route('evaluations.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block mb-2 text-sm dark:text-gray-300">Spécialité</label>
                <select name="specialite_id" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 dark:bg-neutral-700 dark:text-white">
                    <option value="">Choisir...</option>
                    @foreach($specialites as $s)
                        <option value="{{ $s->id }}" {{ request('specialite_id') == $s->id ? 'selected' : '' }}>{{ $s->nom_specialite }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm dark:text-gray-300">Module</label>
                <select name="module_id" class="w-full rounded-lg border-gray-300 dark:bg-neutral-700 dark:text-white">
                    <option value="">Choisir...</option>
                    @foreach($modules as $m)
                        <option value="{{ $m->id }}" {{ request('module_id') == $m->id ? 'selected' : '' }}>{{ $m->nom_module }} (Coef: {{ $m->coef_module }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm dark:text-gray-300">Semestre</label>
                <select name="semestre" class="w-full rounded-lg border-gray-300 dark:bg-neutral-700 dark:text-white">
                    <option value="1" {{ request('semestre') == 1 ? 'selected' : '' }}>Semestre 1</option>
                    <option value="2" {{ request('semestre') == 2 ? 'selected' : '' }}>Semestre 2</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Afficher la liste</button>
        </form>
    </div>

    {{-- SECTION SAISIE DES NOTES --}}
    @if(count($etudiants) > 0)
    <form action="{{ route('evaluations.store') }}" method="POST">
        @csrf
        <input type="hidden" name="module_id" value="{{ request('module_id') }}">
        <input type="hidden" name="semestre" value="{{ request('semestre') }}">
        <input type="hidden" name="annee_academique_id" value="{{ $anneeActive->id }}">

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-neutral-700">
                    <tr>
                        <th class="p-4 dark:text-white">Matricule</th>
                        <th class="p-4 dark:text-white">Nom de l'étudiant</th>
                        <th class="p-4 dark:text-white w-32">Note / 20</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-neutral-700">
                    @foreach($etudiants as $etudiant)
                    <tr>
                        <td class="p-4 dark:text-gray-300">{{ $etudiant->matricule }}</td>
                        <td class="p-4 dark:text-gray-300">{{ $etudiant->name }}</td>
                        <td class="p-4">
                            <input type="number" 
                                   name="notes[{{ $etudiant->id }}]" 
                                   step="0.25" min="0" max="20"
                                   value="{{ $etudiant->evaluations->first()?->note }}"
                                   class="w-full rounded border-gray-300 dark:bg-neutral-700 dark:text-white focus:ring-blue-500">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-6 border-t dark:border-neutral-700 flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-10 py-3 rounded-lg font-bold shadow-lg hover:bg-green-700 transition">Enregistrer toutes les notes</button>
            </div>
        </div>
    </form>
    @elseif(request('module_id'))
        <div class="text-center p-10 bg-gray-100 dark:bg-neutral-800 rounded-xl">
            <p class="text-gray-500 italic">Aucun étudiant inscrit dans cette spécialité pour cette année.</p>
        </div>
    @endif
</div>
@endsection
