@extends('layouts.admin.layout-admin') 

@section('content')
<div 
class="ml-0 md:ml-64 min-h-screen  dark:bg-[#1F2937] antialiased transition-colors duration-300 content mt-16" 
>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Gestion des Spécialités</h1>
        
        {{-- NOUVEAU BLOC : Bouton Kebab Menu avec Dropdown (Actions globales) --}}
        <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
            
            {{-- Bouton Kebab (3 points verticaux) --}}
            <button @click="open = !open" type="button" 
                    class="p-2 rounded-full text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 shadow-md" 
                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                </svg>
            </button>

            {{-- Menu Déroulant --}}
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-neutral-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-10" 
                 role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                    
                    {{-- Option 1: Ajouter --}}
                    <a href="{{ route('specialites.create') }}" 
                       class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-neutral-600 transition duration-100" 
                       role="menuitem" tabindex="-1">
                       ➕ Ajouter une Spécialité
                    </a>
                    
                    {{-- Séparateur --}}
                    <div class="border-t border-gray-100 dark:border-neutral-600"></div>

                    {{-- Option 2: Exporter --}}
                    <a href="#" {{-- Route d'exportation --}}
                       class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-neutral-600 transition duration-100" 
                       role="menuitem" tabindex="-1">
                       📥 Exporter (CSV/Excel)
                    </a>
                </div>
            </div>
        </div>
        {{-- FIN NOUVEAU BLOC --}}
    </div>

    {{-- Affichage des messages Flash --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-800 dark:border-green-600 dark:text-green-50" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:border-red-600 dark:text-red-50" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- 1. Mode GRAND ÉCRAN (Tableau) --}}
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden hidden sm:block">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Nom de la Spécialité</th>
                    <th class="py-3 px-6 text-left">Code Unique</th>
                    <th class="py-3 px-6 text-left">Description</th>
                    <th class="py-3 px-6 text-center">Modules rattachés</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light">
                @forelse ($specialites as $specialite)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                        
                        <td class="py-3 px-6 text-left whitespace-nowrap font-medium dark:text-gray-200">
                            {{ $specialite->nom_specialite }}
                        </td>
                        
                        <td class="py-3 px-6 text-left">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded dark:bg-blue-700 dark:text-blue-100">
                                {{ $specialite->code_unique }}
                            </span>
                        </td>
                        
                        <td class="py-3 px-6 text-left dark:text-gray-400">
                            {{ Str::limit($specialite->description, 50) }}
                        </td>

                        <td class="py-3 px-6 text-center dark:text-gray-400">
                            {{-- $specialite->modules_count ?? 0 --}} 0
                        </td>
                        
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                {{-- Modifier --}}
                                <a href="{{ route('specialites.edit', $specialite) }}" 
                                   class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-500 transform hover:scale-110" title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                                
                                {{-- Supprimer --}}
                                <form action="{{ route('specialites.destroy', $specialite) }}" method="POST" onsubmit="return confirm('ATTENTION : Êtes-vous sûr de vouloir supprimer cette spécialité ? Cela peut être impossible si des modules y sont rattachés.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500 transform hover:scale-110" title="Supprimer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500 dark:text-gray-400">
                            Aucune spécialité n'a été trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 2. Mode PETIT ÉCRAN (Cartes DIV) --}}
    <div class="sm:hidden">
        @forelse ($specialites as $specialite)
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 mb-4 border border-gray-200 dark:border-gray-700 transition duration-150">
                
                {{-- Ligne 1: Titre et Code --}}
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $specialite->nom_specialite }}</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded dark:bg-blue-700 dark:text-blue-100">
                        {{ $specialite->code_unique }}
                    </span>
                </div>
                
                {{-- Ligne 2: Description --}}
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                    **Description :** {{ Str::limit($specialite->description, 80) }}
                </p>

                {{-- Ligne 3: Modules et Actions --}}
                <div class="flex justify-between items-center border-t border-gray-100 dark:border-gray-700 pt-3">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        Modules rattachés : **0**
                    </span>
                    
                    {{-- Actions (Mini-dropdown pour mobile) --}}
                    <div class="relative inline-block text-left" x-data="{ mobile_open_{{ $specialite->id }}: false }" @click.outside="mobile_open_{{ $specialite->id }} = false">
                        <button @click="mobile_open_{{ $specialite->id }} = !mobile_open_{{ $specialite->id }}" type="button" 
                                class="p-1 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-neutral-700 focus:outline-none" 
                                aria-expanded="true">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                            </svg>
                        </button>
                        
                        <div x-show="mobile_open_{{ $specialite->id }}"
                             x-transition:enter="transition ease-out duration-100" 
                             x-transition:enter-start="transform opacity-0 scale-95" 
                             x-transition:enter-end="transform opacity-100 scale-100" 
                             x-transition:leave="transition ease-in duration-75" 
                             x-transition:leave-start="transform opacity-100 scale-100" 
                             x-transition:leave-end="transform opacity-0 scale-95" 
                             class="origin-top-right absolute right-0 bottom-full mb-2 w-40 rounded-md shadow-lg bg-white dark:bg-neutral-700 ring-1 ring-black ring-opacity-5 z-20">
                            <div class="py-1">
                                {{-- Modifier --}}
                                <a href="{{ route('specialites.edit', $specialite) }}" 
                                   class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-neutral-600 transition duration-100">
                                   ✏️ Modifier
                                </a>
                                {{-- Supprimer --}}
                                <form action="{{ route('specialites.destroy', $specialite) }}" method="POST" 
                                      onsubmit="return confirm('Supprimer cette spécialité ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 w-full text-left block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-neutral-600 transition duration-100">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center bg-white dark:bg-gray-800 shadow-lg rounded-lg text-gray-500 dark:text-gray-400">
                Aucune spécialité n'a été trouvée. Veuillez en <a href="{{ route('specialites.create') }}" class="text-blue-600 hover:underline">ajouter une</a>.
            </div>
        @endforelse
    </div>
    
    {{-- Message si aucune spécialité n'est trouvée (pour le mode grand écran) --}}
    @if ($specialites->isEmpty() && request()->routeIs('specialites.index'))
    <div class="hidden sm:block p-6 text-center text-gray-500 dark:text-gray-400">
        Aucune spécialité n'a été trouvée. Veuillez en <a href="{{ route('specialites.create') }}" class="text-blue-600 hover:underline">ajouter une</a>.
    </div>
    @endif
</div>
@endsection
