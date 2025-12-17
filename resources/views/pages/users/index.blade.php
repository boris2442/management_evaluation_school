@extends('layouts.admin.layout-admin')

@section('content')
{{-- Background corrigé en #1F2937 (bg-gray-800) --}}
<div class="ml-0 md:ml-64 min-h-screen dark:bg-[#1F2937] text-gray-100 antialiased bg-white " x-data="{ openEditModal: false, currentUser: {} }">
    
    <div class="container mx-auto px-6 py-10 mt-12">
        
        {{-- EN-TÊTE --}}
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-white uppercase">Utilisateurs</h1>
                <p class="text-sm text-gray-400 font-medium">Gestion administrative des comptes</p>
            </div>

            <div class="relative" x-data="{ menuGlobal: false }">
                <button @click="menuGlobal = !menuGlobal" 
                        class="p-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg border border-gray-600 transition-all shadow-sm">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                    </svg>
                </button>
                {{-- Dropdown Global --}}
                <div x-show="menuGlobal" @click.away="menuGlobal = false" x-cloak
                     class="absolute right-0 mt-2 w-56 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-[100] overflow-hidden py-1">
                    <a href="{{ route('users.trash') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700 transition">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Voir la Corbeille
                    </a>
                </div>
            </div>
        </div>

        {{-- BARRE DE RECHERCHE AVEC ICÔNE SUBMIT INTÉGRÉE --}}
        <div class="flex gap-4 mb-8">
            <form action="{{ route('users.index') }}" method="GET" class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher par nom ou email..." 
                       class="w-full dark:bg-gray-700   dark:text-white pl-5 pr-14 py-3.5  focus:ring-1 focus:ring-blue-500 placeholder-gray-500
                       

                       bg-gray-700 text-white rounded-xl px-5 py-3.5 border border-gray-600 outline-none focus:border-blue-500 transition-all shadow-sm
                          " aria-label="Recherche Utilisateurs
                       ">
                
                {{-- Icône Submit à l'intérieur --}}
                <button type="submit" class="absolute right-2 top-1.5 p-2 text-gray-400 hover:text-white hover:bg-gray-600 rounded-lg transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>

            <div class="relative">
                <input type="date" class="bg-gray-700 text-white rounded-xl px-5 py-3.5 border border-gray-600 outline-none focus:border-blue-500 transition-all shadow-sm">
            </div>
        </div>

        {{-- TABLEAU --}}
        <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-xl overflow-hidden">
      <table class="w-full text-left bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">

                <thead class="bg-gray-700/50 border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-gray-400">Identité</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-gray-400 text-center">Genre</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-700/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-white">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->sexe == 'Masculin' ? 'bg-blue-900/40 text-blue-300' : 'bg-pink-900/40 text-pink-300' }}">
                                {{ $user->sexe == 'Masculin' ? 'Homme' : 'Femme' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right relative" x-data="{ rowMenu: false }">
                            <button @click="rowMenu = !rowMenu" class="text-gray-500 hover:text-white p-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                            </button>

                            <div x-show="rowMenu" @click.away="rowMenu = false" x-cloak
                                 class="absolute right-10 top-0 mt-2 w-40 bg-gray-900 border border-gray-700 rounded-xl shadow-2xl z-[110] py-1">
                                <button @click="currentUser = {{ json_encode($user) }}; openEditModal = true; rowMenu = false" 
                                        class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 transition">
                                    Modifier
                                </button>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-900/20 transition">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }
</style>
@endsection
