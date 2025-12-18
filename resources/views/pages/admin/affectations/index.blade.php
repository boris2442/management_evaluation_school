@extends('layouts.admin.layout-admin')

@section('content')
<div
class="ml-0 md:ml-64 min-h-screen  dark:bg-[#1F2937] antialiased transition-colors duration-300 content mt-16" 
 >
    <div class="bg-white dark:bg-neutral-800 p-8 rounded-3xl shadow-sm border dark:border-neutral-700">
        <h2 class="text-2xl font-bold mb-6 dark:text-white">Affectation des Modules aux Enseignants</h2>

        <form action="{{ route('affectations.store') }}" method="POST">
            @csrf
            
            {{-- Sélection de l'Enseignant --}}
            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold dark:text-gray-300">Choisir un Enseignant</label>
                <select name="user_id" class="w-full p-3 rounded-xl border dark:bg-neutral-900 dark:border-neutral-600 dark:text-white">
                    <option value="">Sélectionnez un professeur...</option>
                    @foreach($enseignants as $ens)
                        <option value="{{ $ens->id }}">{{ $ens->name }} ({{ $ens->matricule }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Liste des Modules --}}
            <div class="mb-6">
                <label class="block mb-4 text-sm font-bold dark:text-gray-300">Modules à attribuer</label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($modules as $mod)
                        <div class="flex items-center p-4 border dark:border-neutral-700 rounded-xl hover:bg-gray-50 dark:hover:bg-neutral-700 transition">
                            <input type="checkbox" name="module_ids[]" value="{{ $mod->id }}" class="w-5 h-5 text-blue-600 rounded">
                            <label class="ml-3 text-sm dark:text-gray-200">
                                {{ $mod->nom_module }} <span class="text-xs text-gray-400">({{ $mod->specialite->nom_specialite ?? 'Général' }})</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition">
                Enregistrer les affectations
            </button>
        </form>
    </div>
</div>
@endsection
