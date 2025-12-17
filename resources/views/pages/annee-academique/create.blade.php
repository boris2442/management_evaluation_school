@extends('layouts.admin.layout-admin') 
{{-- Assurez-vous d'étendre votre layout principal --}}

@php
    // S'assurer que $annee est défini à null si le contrôleur ne l'a pas envoyé (création)
    $annee = $annee ?? null; 
    
    $isEdit = isset($annee); 
    // Utiliser ?-> pour éviter l'erreur si $annee est null
    $title = $isEdit ? 'Modifier l\'Année Académique : ' . $annee?->libelle : 'Créer une Nouvelle Année Académique';
    $action = $isEdit ? route('annee-academiques.update ', $annee) : route('annee-academiques.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="w-full  md:ml-64 mx-auto bg-white shadow-2xl rounded-xl  max-w-5xl p-6">
        
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">{{ $title }}</h1>
        
        <form action="{{ $action }}" method="POST">
            @csrf
            @method($method)

            {{-- Champ Libellé --}}
            <div class="mb-4">
                <label for="libelle" class="block text-sm font-medium text-gray-700 mb-1">
                    Libellé (ex: 2024-2025)
                </label>
                <input type="text" id="libelle" name="libelle" 
                       value="{{ old('libelle', $annee->libelle ?? '') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('libelle') border-red-500 @enderror"
                       placeholder="Saisissez le libellé de l'année"
                       required>
                @error('libelle')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Champ Date de Début --}}
         {{-- Champ Date de Début --}}
<div class="mb-4">
    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">
        Date de Début
    </label>
    <input type="date" id="date_debut" name="date_debut" 
           {{-- CODE CORRIGÉ : L'opérateur ?-> est appliqué deux fois pour la sécurité --}}
           value="{{ old('date_debut', $annee?->date_debut?->format('Y-m-d')) }}"
           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('date_debut') border-red-500 @enderror"
           required>
    @error('date_debut')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>

        {{-- Champ Date de Fin --}}
<div class="mb-6">
    <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">
        Date de Fin
    </label>
    <input type="date" id="date_fin" name="date_fin" 
           {{-- CODE CORRIGÉ : Utiliser ?-> deux fois pour accéder à l'objet Carbon (date_fin) puis à sa méthode format() --}}
           value="{{ old('date_fin', $annee?->date_fin?->format('Y-m-d')) }}"
           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('date_fin') border-red-500 @enderror"
           required>
    @error('date_fin')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>

            <div class="flex justify-between items-center">
                {{-- Bouton Annuler --}}
                <a href="{{ route('annee-academiques.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Annuler
                </a>

                {{-- Bouton Soumettre --}}
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    {{ $isEdit ? 'Mettre à jour' : 'Enregistrer l\'Année' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
