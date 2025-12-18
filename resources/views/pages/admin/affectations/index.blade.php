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



   <div class="mt-12 bg-white dark:bg-neutral-800 rounded-3xl border dark:border-neutral-700 shadow-sm overflow-visible">

    
    <div class="p-6 border-b dark:border-neutral-700">
        <h3 class="text-xl font-bold dark:text-white">Récapitulatif des attributions</h3>
    </div>

    {{-- Version TABLEAU (Visible seulement sur MD et plus) --}}
    <div class="hidden md:block overflow-visible">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-neutral-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase">
                    <th class="p-4 font-bold">Enseignant</th>
                    <th class="p-4 font-bold">Modules attribués</th>
                    <th class="p-4 font-bold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-neutral-700">
                @foreach($enseignants as $ens)
                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/30 transition">
                    <td class="p-4">
                        <div class="font-bold dark:text-white">{{ $ens->name }}</div>
                        <div class="text-xs text-gray-500 italic">{{ $ens->matricule }}</div>
                    </td>
                    <td class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @forelse($ens->modulesEnseignes as $m)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $m->nom_module }}
                                </span>
                            @empty
                                <span class="text-gray-400 text-xs italic">Aucun module</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="p-4 text-center relative overflow-visible">
                        <button onclick="toggleDropdown('dropdown-{{ $ens->id }}')" class="p-2 hover:bg-gray-200 dark:hover:bg-neutral-600 rounded-full">
                            <svg class="w-5 h-5 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                        </button>
                        <div id="dropdown-{{ $ens->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-neutral-800 border dark:border-neutral-700 rounded-xl shadow-2xl z-[100]">
                            <button onclick="editAssign('{{ $ens->id }}', {{ $ens->modulesEnseignes->pluck('id') }})" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-700">✏️ Editer</button>
                            <form action="{{ route('affectations.destroy', $ens->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">🗑️ Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Version GRID/CARDS (Visible seulement sur Mobile) --}}
    <div class="md:hidden grid grid-cols-1 gap-4 p-4">
        @foreach($enseignants as $ens)
        <div class="bg-gray-50 dark:bg-neutral-900/50 p-4 rounded-2xl border dark:border-neutral-700 relative">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="font-bold dark:text-white">{{ $ens->name }}</div>
                    <div class="text-xs text-gray-500">{{ $ens->matricule }}</div>
                </div>
                <button onclick="toggleDropdown('dropdown-mob-{{ $ens->id }}')" class="p-1">
                    <svg class="w-5 h-5 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                </button>
                <div id="dropdown-mob-{{ $ens->id }}" class="hidden absolute right-4 top-12 w-40 bg-white dark:bg-neutral-800 border rounded-xl shadow-xl z-[100]">
                    <button onclick="editAssign('{{ $ens->id }}', {{ $ens->modulesEnseignes->pluck('id') }})" class="block w-full text-left px-4 py-3 text-sm dark:text-white">✏️ Editer</button>
                    <form action="{{ route('affectations.destroy', $ens->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="block w-full text-left px-4 py-3 text-sm text-red-500">🗑️ Supprimer</button>
                    </form>
                </div>
            </div>
            <div class="flex flex-wrap gap-1">
                @foreach($ens->modulesEnseignes as $m)
                    <span class="text-[10px] px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full dark:bg-blue-900/30 dark:text-blue-300">
                        {{ $m->nom_module }}
                    </span>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>

<script>
    // Fonction pour ouvrir/fermer le menu
    function toggleDropdown(id) {
        // Fermer tous les autres dropdowns ouverts
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            if(el.id !== id) el.classList.add('hidden');
        });
        document.getElementById(id).classList.toggle('hidden');
    }

    // Fermer si on clique ailleurs
    window.onclick = function(event) {
        if (!event.target.closest('button')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
        }
    }

    // Fonction pour charger les données dans le formulaire en haut
    function editAssign(userId, moduleIds) {
        // On sélectionne le prof dans le select
        const select = document.querySelector('select[name="user_id"]');
        select.value = userId;

        // On décoche tout
        document.querySelectorAll('input[name="module_ids[]"]').forEach(cb => cb.checked = false);

        // On coche les modules correspondants
        moduleIds.forEach(id => {
            const checkbox = document.querySelector(`input[value="${id}"]`);
            if(checkbox) checkbox.checked = true;
        });

        // Scroll vers le formulaire
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }



</script>
    </div>
</div>
@endsection
