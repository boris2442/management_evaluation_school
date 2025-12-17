@extends('layouts.admin.layout-admin')

@section('content')
<div class="container mx-auto px-4 py-8 ml-0 md:ml-64 mt-16 dark:bg-neutral-900 min-h-screen">
    
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Gestion des Inscriptions - {{ $anneeActive->libelle }}</h1>

    {{-- SECTION 1 : FORMULAIRE DE CRÉATION --}}
    <div class="bg-white dark:bg-neutral-800 p-6 rounded-xl shadow mb-8">
        <h2 class="text-lg font-semibold mb-4 text-blue-600">Nouvelle Inscription</h2>
        <form action="{{ route('inscriptions.store') }}" method="POST">
            @csrf
            {{-- AJOUTE CETTE LIGNE ICI --}}
        <input type="hidden" name="annee_academique_id" value="{{ $anneeActive->id }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 dark:text-gray-300">Spécialité</label>
                    <select name="specialite_id" class="w-full rounded-lg border-gray-300 dark:bg-neutral-700 dark:text-white" required>
                        @foreach($specialites as $s)
                            <option value="{{ $s->id }}">{{ $s->nom_specialite }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-2 dark:text-gray-300">Étudiants disponibles</label>
                    <select name="etudiant_ids[]" class="w-full rounded-lg border-gray-300 dark:bg-neutral-700 dark:text-white" multiple required>
                        @foreach($etudiantsDisponibles as $e)
                            <option value="{{ $e->id }}">{{ $e->name }} ({{ $e->matricule }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl pour en sélectionner plusieurs</p>
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg">Inscrire</button>
        </form>
    </div>

    {{-- SECTION 2 : LISTE ET SUPPRESSION --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
        <h2 class="p-6 text-lg font-semibold border-b dark:border-neutral-700 dark:text-white">Inscriptions Actuelles</h2>
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-neutral-700 dark:text-gray-300">
                <tr>
                    <th class="p-4">Matricule</th>
                    <th class="p-4">Étudiant</th>
                    <th class="p-4">Spécialité</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-neutral-700">
                @foreach($inscriptionsActuelles as $ins)
                <tr class="dark:text-gray-200">
                    <td class="p-4">{{ $ins->etudiant->matricule }}</td>
                    <td class="p-4 font-medium">{{ $ins->etudiant->name }}</td>
                    <td class="p-4">
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold">
                            {{ $ins->specialite->nom_specialite }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <form action="{{ route('inscriptions.destroy', $ins->id) }}" method="POST" onsubmit="return confirm('Annuler cette inscription ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
