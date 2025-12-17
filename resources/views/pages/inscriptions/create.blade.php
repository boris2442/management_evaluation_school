@extends('layouts.admin.layout-admin')

@section('content')
<div class="container mx-auto px-4 py-8 ml-0 md:ml-64 mt-16 dark:bg-neutral-900 min-h-screen">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Nouvelle Inscription</h1>
            <p class="text-gray-600 dark:text-gray-400">Année Académique : 
                <span class="font-bold text-blue-600">{{ $anneeActive->libelle }}</span>
            </p>
        </div>
    </div>

    <form action="{{ route('inscriptions.store') }}" method="POST">
        @csrf
        <input type="hidden" name="annee_academique_id" value="{{ $anneeActive->id }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Colonne Gauche : Choix Spécialité --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-neutral-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-neutral-700">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 uppercase tracking-wider">
                        1. Choisir la Spécialité
                    </label>
                    <div class="space-y-3">
                        @foreach($specialites as $spec)
                            <label class="flex items-center p-3 rounded-lg border border-gray-100 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700 cursor-pointer transition">
                                <input type="radio" name="specialite_id" value="{{ $spec->id }}" required class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 font-medium text-gray-800 dark:text-gray-200">{{ $spec->nom_specialite }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Colonne Droite : Choix des Étudiants --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-md border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-neutral-700 flex justify-between items-center">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            2. Sélectionner les Étudiants
                        </label>
                        <span class="text-xs bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">
                            {{ $etudiants->count() }} disponible(s)
                        </span>
                    </div>

                    <div class="max-h-[500px] overflow-y-auto p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($etudiants as $etudiant)
                            <label class="relative flex items-center p-4 rounded-xl border border-gray-200 dark:border-neutral-700 hover:shadow-md transition cursor-pointer group">
                                <input type="checkbox" name="etudiant_ids[]" value="{{ $etudiant->id }}" class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                                <div class="ml-4">
                                    <p class="font-bold text-gray-900 dark:text-gray-100">{{ $etudiant->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 italic">{{ $etudiant->matricule }}</p>
                                </div>
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                    <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full uppercase">Étudiant</span>
                                </div>
                            </label>
                        @empty
                            <div class="col-span-2 py-10 text-center text-gray-500 italic">
                                Aucun étudiant disponible pour une nouvelle inscription cette année.
                            </div>
                        @endforelse
                    </div>

                    @if($etudiants->isNotEmpty())
                        <div class="p-6 bg-gray-50 dark:bg-neutral-700/50 border-t border-gray-200 dark:border-neutral-700 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-blue-500/30 transition duration-300">
                                Valider les inscriptions
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
