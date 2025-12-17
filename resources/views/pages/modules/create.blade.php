@extends('layouts.admin.layout-admin')

@section('content')
<div class="container mx-auto px-4 py-8 ml-0 md:ml-64 mt-16 dark:bg-neutral-900 min-h-screen">
    
    {{-- Détection du mode (Création ou Édition) --}}
    @php
        $isEdit = $module->exists;
        $title = $isEdit ? 'Modifier le Module : ' . $module->nom_module : 'Créer un Nouveau Module';
        $formAction = $isEdit ? route('modules.update', $module) : route('modules.store');
        $buttonText = $isEdit ? 'Enregistrer les modifications' : 'Créer le Module';
        $buttonColor = $isEdit ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500';
        
        // Initialisation de l'array des enseignants assignés pour le pré-cochage
        $assignedEnseignantsIds = $isEdit ? $module->enseignants->pluck('id')->toArray() : [];
    @endphp

    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6">{{ $title }}</h1>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-8">
        
        <form action="{{ $formAction }}" method="POST">
            @csrf
            
            @if ($isEdit)
                @method('PUT')
            @endif

            {{-- Messages d'erreurs --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-900 dark:border-red-600 dark:text-red-100" role="alert">
                    <p class="font-bold">Erreurs de validation :</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- 1. Code du Module (Affiché et en lecture seule seulement en mode EDIT) --}}
                @if ($isEdit)
                    <div>
                        <label for="code_module" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code du Module (Non modifiable)</label>
                        <input type="text" id="code_module" value="{{ $module->code_module }}" readonly
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-gray-100 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed">
                    </div>
                @endif
                
                {{-- 2. Champ Nom du Module --}}
                <div class="{{ $isEdit ? '' : 'md:col-span-2' }}">
                    <label for="nom_module" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom du Module <span class="text-red-500">*</span></label>
                    <input type="text" name="nom_module" id="nom_module" value="{{ old('nom_module', $module->nom_module) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('nom_module') border-red-500 @enderror">
                    @error('nom_module')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- 3. Champ Spécialité --}}
                <div>
                    <label for="specialite_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Spécialité Rattachée <span class="text-red-500">*</span></label>
                    <select name="specialite_id" id="specialite_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('specialite_id') border-red-500 @enderror">
                        <option value="">Sélectionner une spécialité</option>
                        @foreach ($specialites as $specialite)
                            @php
                                $selectedId = $isEdit ? $module->specialite_id : null;
                                $selected = (old('specialite_id', $selectedId) == $specialite->id);
                            @endphp
                            <option value="{{ $specialite->id }}" {{ $selected ? 'selected' : '' }}>
                                {{ $specialite->nom_specialite }} ({{ $specialite->code_unique }})
                            </option>
                        @endforeach
                    </select>
                    @error('specialite_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- 4. Champ Coefficient --}}
                <div>
                    <label for="coef_module" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coefficient <span class="text-red-500">*</span></label>
                    <input type="number" name="coef_module" id="coef_module" value="{{ old('coef_module', $module->coef_module ?? 1) }}" required min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('coef_module') border-red-500 @enderror">
                    @error('coef_module')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 5. Champ Ordre / Séquence --}}
                <div>
                    <label for="ordre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ordre du Module (dans la Spécialité) <span class="text-red-500">*</span></label>
                    <input type="number" name="ordre" id="ordre" value="{{ old('ordre', $module->ordre) }}" required min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('ordre') border-red-500 @enderror">
                    @error('ordre')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
            
            <hr class="my-6 border-gray-200 dark:border-gray-700">

            {{-- 6. Affectation des Enseignants (Checkboxes) --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Affectation des Enseignants (Optionnel)</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse ($enseignants as $enseignant)
                        @php
                            // Logique de pré-cochage : old() prend la priorité après une erreur de validation
                            $checked = (is_array(old('enseignants')) && in_array($enseignant->id, old('enseignants'))) || 
                                       (!is_array(old('enseignants')) && in_array($enseignant->id, $assignedEnseignantsIds));
                        @endphp
                        <div class="flex items-center">
                            <input id="enseignant_{{ $enseignant->id }}" name="enseignants[]" type="checkbox" value="{{ $enseignant->id }}"
                                   {{ $checked ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                            <label for="enseignant_{{ $enseignant->id }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                {{ $enseignant->name }}
                            </label>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 col-span-4">Aucun enseignant trouvé.</p>
                    @endforelse
                </div>
                @error('enseignants')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('modules.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-150">
                    Annuler
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $buttonColor }} focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150">
                    {{ $buttonText }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
