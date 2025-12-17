@extends('layouts.admin.layout-admin') 

{{-- Détection du mode (Création ou Édition) --}}
@php
    $specialite = $specialite ?? null; 
    
    $isEdit = isset($specialite);
    $title = $isEdit ? 'Modifier la Spécialité : ' . $specialite?->nom_specialite : 'Créer une Nouvelle Spécialité';
    $action = $isEdit ? route('specialites.update', $specialite) : route('specialites.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 shadow-2xl rounded-xl p-8">
        
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6 text-center">{{ $title }}</h1>
        
        <form action="{{ $action }}" method="POST">
            @csrf
            @method($method)

            {{-- Champ Nom de la Spécialité --}}
            <div class="mb-4">
                <label for="nom_specialite" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nom de la Spécialité *
                </label>
                <input type="text" id="nom_specialite" name="nom_specialite" 
                       value="{{ old('nom_specialite', $specialite?->nom_specialite) }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nom_specialite') border-red-500 @enderror"
                       placeholder="Ex: Licence Génie Logiciel"
                       required>
                @error('nom_specialite')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Champ Code Unique --}}
            <div class="mb-4">
                <label for="code_unique" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Code Unique (Court) *
                </label>
                <input type="text" id="code_unique" name="code_unique" 
                       value="{{ old('code_unique', $specialite?->code_unique) }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('code_unique') border-red-500 @enderror"
                       placeholder="Ex: LGL"
                       required>
                @error('code_unique')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Champ Description --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Description (Optionnelle)
                </label>
                <textarea id="description" name="description" rows="3"
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                          placeholder="Décrivez brièvement le contenu de cette spécialité.">{{ old('description', $specialite?->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                {{-- Bouton Annuler --}}
                <a href="{{ route('specialites.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Annuler
                </a>

                {{-- Bouton Soumettre --}}
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    {{ $isEdit ? 'Mettre à jour' : 'Enregistrer la Spécialité' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
