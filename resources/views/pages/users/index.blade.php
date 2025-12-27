@extends('layouts.admin.layout-admin')

@section('content')
{{-- 1. LE CONTENEUR PRINCIPAL --}}
{{-- <div class="
ml-0 md:ml-64 min-h-screen  dark:bg-[#1F2937] antialiased transition-colors duration-300 content " 
     x-data="{ openEditModal: false, currentUser: {} }"> --}}
    
<div class="ml-0 md:ml-64 min-h-screen dark:bg-[#1F2937] antialiased transition-colors duration-300 content" 
     x-data="{ 
        openEditModal: false, 
        currentUser: {}, 
        selectedUsers: [], 
        allSelected: false,
        toggleAll() {
            if (this.allSelected) {
                this.selectedUsers = [];
            } else {
                this.selectedUsers = Array.from(document.querySelectorAll('.user-checkbox')).map(el => el.value);
            }
            this.allSelected = !this.allSelected;
        }
     }">




    <div class="container mx-auto px-6 py-10 mt-12">
        
        {{-- 2. L'EN-TÊTE (Titre + Menu Corbeille) --}}
        {{-- <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class=" text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white uppercase">Utilisateurs</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Gestion administrative des comptes</p>
            </div>

            <div class="relative" x-data="{ menuGlobal: false }">
                <button @click="menuGlobal = !menuGlobal" 
                        class="p-2.5 bg-white dark:bg-gray-700 text-gray-700 dark:text-white rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                </button>
                <div x-show="menuGlobal" @click.away="menuGlobal = false" x-cloak
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl z-[100] py-1">
                    <a href="{{ route('users.trash') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Voir la Corbeille
                    </a>
                </div>
            </div>
        </div> --}}

{{-- 2. L'EN-TÊTE (Titre + Menu Actions) --}}
<div class="flex justify-between items-center mb-10" x-data="{ openImportModal: false }">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white uppercase">Utilisateurs</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Gestion administrative des comptes</p>
    </div>

    <div class="relative" x-data="{ menuGlobal: false }">
        <button @click="menuGlobal = !menuGlobal" 
                class="p-2.5 bg-white dark:bg-gray-700 text-gray-700 dark:text-white rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
        </button>
        
        <div x-show="menuGlobal" @click.away="menuGlobal = false" x-cloak
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl z-[100] py-2">
            
            {{-- Action Import --}}
            <button @click="openImportModal = true; menuGlobal = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-600 transition">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m-4 4l-4-4m4 4l4-4m-5 8h2a2 2 0 002-2v-3a2 2 0 012-2h1"></path></svg>
                Importation Massive (.xlsx)
            </button>



{{-- Action Export EXCEL --}}
            <a href="{{ route('users.export', ['format' => 'excel', 'role' => request('role'), 'annee_id' => request('annee_id')]) }}" 
               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 transition">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Exporter vers Excel
            </a>

            {{-- Action Export PDF (Maquette Officielle) --}}
            <a href="{{ route('users.export', ['format' => 'pdf', 'role' => request('role'), 'annee_id' => request('annee_id')]) }}" 
               target="_blank"
               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 transition">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Générer la Liste (PDF)
            </a>



            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

            {{-- Action Corbeille --}}
            <a href="{{ route('users.trash') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Voir la Corbeille
            </a>
        </div>
    </div>

    {{-- MODALE D'IMPORTATION (Placée ici pour être liée au bouton) --}}
    @include('pages.users.partials.import-modal') 
    @if(session('error'))
    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-xs">
        {{ session('error') }}
    </div>
@endif
</div>





        {{-- 3. BARRE DE RECHERCHE & BOUTON RÉINITIALISER --}}
        <div class="flex flex-col md:flex-row gap-4 mb-8">
            <form action="{{ route('users.index') }}" method="GET" class="relative flex-1 group">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher par nom ou email..." 
                       class="w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl pl-5 pr-14 py-3.5 border border-gray-200 dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm">
                
                <button type="submit" class="absolute right-2 top-1.5 p-2 text-gray-400 hover:text-blue-600 dark:hover:text-white transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>

            {{-- Bouton réinitialiser (Apparaît si on recherche quelque chose) --}}
            @if(request('search'))
                <a href="{{ route('users.index') }}" 
                   class="flex items-center justify-center gap-2 px-6 py-3.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-bold rounded-xl border border-red-100 dark:border-red-900/30 hover:bg-red-100 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Réinitialiser
                </a>
            @endif
        </div>

        {{-- 4. LE TABLEAU --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                {{-- BARRE D'ACTION GROUPÉE --}}
<template x-if="selectedUsers.length > 0">
    <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[200] bg-gray-900 dark:bg-blue-600 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-6 animate-bounce-in">
        <span class="text-sm font-bold">
            <span x-text="selectedUsers.length"></span> sélectionné(s)
        </span>
        <form action="{{ route('users.bulkDestroy') }}" method="POST" @submit.prevent="if(confirm('Supprimer ces utilisateurs ?')) $el.submit()">
            @csrf @method('DELETE')
            {{-- On injecte les IDs dans des inputs cachés --}}
            <template x-for="id in selectedUsers" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-xs font-black uppercase transition-all">
                Supprimer la sélection
            </button>
        </form>
        <button @click="selectedUsers = []; allSelected = false" class="text-gray-400 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</template>
                <table class="w-full text-left">
                    <thead class="   dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700   ">
                        <tr>
                            <th class="px-6 py-4 w-10">
    <input type="checkbox" @click="toggleAll()" x-model="allSelected"
           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Identité</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Genre</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-500  dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
        <input type="checkbox" value="{{ $user->id }}" x-model="selectedUsers"
               class="user-checkbox w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
    </td>
    {{-- Tes autres colonnes TD (Identité, Genre, etc.) --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $user->sexe == 'Masculin' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300' }}">
                                    {{ $user->sexe }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right relative" x-data="{ rowMenu: false }">
                                <button @click="rowMenu = !rowMenu" class="text-gray-400 hover:text-gray-900 dark:hover:text-white p-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                </button>
                                <div x-show="rowMenu" @click.away="rowMenu = false" x-cloak
                                     class="absolute right-10 top-0 mt-2 w-44 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl z-[110] py-1">
                                    {{-- ICI : ON CHARGE LES INFOS DANS currentUser ET ON OUVRE LA MODALE --}}
                                    <button @click="currentUser = {{ json_encode($user) }}; openEditModal = true; rowMenu = false" 
                                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        Modifier
                                    </button>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4  dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700">
                {{ $users->links() }}
            </div>
        </div>
    </div>

  {{-- 5. LA MODALE (LA FENÊTRE QUI S'OUVRE) --}}
<div x-show="openEditModal" x-cloak class="fixed inset-0 z-[150] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        {{-- Fond noir transparent --}}
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="openEditModal = false"></div>

        {{-- Boîte de dialogue --}}
        <div class="relative bg-white dark:bg-[#1F2937] rounded-2xl w-full max-w-lg shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            {{-- Affichage des erreurs de validation --}}
            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 p-4 border-b border-red-100 dark:border-red-900/50">
                    <ul class="list-disc list-inside text-xs text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form :action="'{{ route('users.update', 'ID_REPLACE') }}'.replace('ID_REPLACE', currentUser.id)" method="POST">
                @csrf 
                @method('PUT')

                <div class="px-8 py-6">
                    <h3 class="text-xl font-bold text-gray-900  mb-6 uppercase">Modifier Profil</h3>
                    <div class="space-y-4">
                        {{-- Nom --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nom Complet</label>
                            <input type="text" name="name" x-model="currentUser.name" 
                                   class="w-full  dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900  focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Adresse Email</label>
                            <input type="email" name="email" x-model="currentUser.email" 
                                   class="w-full dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Genre (Dynamique ENUM) --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Genre</label>
                                <select name="sexe" x-model="currentUser.sexe" 
                                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none">
                                    @foreach($genres as $genre)
                                        <option value="{{ $genre }}">{{ $genre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Rôle (Dynamique ENUM) --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Rôle</label>
                                <select name="role" x-model="currentUser.role" 
                                        class="w-full dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none">
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-4  dark:bg-gray-800/50 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" @click="openEditModal = false" class="text-gray-400 font-bold px-4 hover:text-gray-600 transition">Annuler</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-bold shadow-lg active:scale-95 transition-all">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<style>
    [x-cloak] { display: none !important; }
 
</style>
@endsection

<style>
    [x-cloak] { display: none !important; }

    /* Animation d'entrée pour la barre flottante */
    @keyframes bounce-in {
        0% { transform: translate(-50%, 100px); opacity: 0; }
        60% { transform: translate(-50%, -10px); opacity: 1; }
        100% { transform: translate(-50%, 0); opacity: 1; }
    }
    .animate-bounce-in {
        animation: bounce-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
</style>
