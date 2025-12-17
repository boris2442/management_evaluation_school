@extends('layouts.admin.layout-admin')

@section('content')
<div class="min-h-screen  dark:bg-neutral-900 py-10 px-4 ml-0 md:ml-64 mt-16">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-100 ">Gestion des Années Académiques</h1>
        
        {{-- Bouton Kebab Menu avec Dropdown --}}
        <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" type="button" 
                    class="p-2 rounded-full text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150" 
                    id="menu-button">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                </svg>
            </button>

            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-neutral-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                <div class="py-1">
                    <a href="{{ route('annee-academiques.create') }}" class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-neutral-600">
                        ➕ Ajouter une Année
                    </a>
                    <div class="border-t border-gray-100 dark:border-neutral-600"></div>
                    <a href="#" class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-neutral-600">
                        📥 Exporter la liste (Excel)
                    </a>
                    <a href="#" onclick="window.print()" class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-neutral-600">
                        🖨️ Imprimer la page
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Messages Flash --}}
    @if (session('success'))
        <div id="flash-message" class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-200 p-4 mb-4 rounded">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- 1. MODE TABLEAU (VISIBLE SUR PC/TABLETTE) --}}
    <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden hidden sm:block">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-gray-300 uppercase text-sm">
                    <th class="py-3 px-6 text-left">Libellé</th>
                    <th class="py-3 px-6 text-left">Période</th>
                    <th class="py-3 px-6 text-center">Statut</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light">
                @foreach ($annees as $annee)
                    <tr class="border-b border-gray-200 dark:border-neutral-600 hover:bg-gray-50 dark:hover:bg-neutral-700 {{ $annee->statut ? 'bg-yellow-50 dark:bg-yellow-900/20' : '' }}">
                        <td class="py-3 px-6 text-left whitespace-nowrap">
                            <span class="font-medium">{{ $annee->libelle }}</span>
                            @if ($annee->statut)
                                <span class="ml-2 text-xs bg-yellow-600 text-white px-2 py-0.5 rounded-full">Active</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-left text-xs">
                            Du {{ $annee->date_debut->format('d/m/Y') }} au {{ $annee->date_fin->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            <form action="{{ route('annee-academiques.toggle-statut', $annee) }}" method="POST">
                                @csrf @method('PUT') 
                                <button type="submit" class="text-white font-bold py-1 px-3 rounded-lg text-xs transition {{ $annee->statut ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }}">
                                    {{ $annee->statut ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('annee-academiques.edit', $annee) }}" class="w-4 hover:text-purple-500 transform hover:scale-110">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <form action="{{ route('annee-academiques.destroy', $annee) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-4 hover:text-red-500 transform hover:scale-110">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- 2. MODE GRILLE / MOBILE (VISIBLE UNIQUEMENT SUR MOBILE) --}}
{{-- 2. MODE GRILLE / MOBILE (VISIBLE UNIQUEMENT SUR MOBILE) --}}
<div class="sm:hidden space-y-4">
    @forelse ($annees as $annee)
        <div class="bg-white dark:bg-neutral-800 shadow-lg rounded-lg p-4 border border-gray-200 dark:border-neutral-700 transition duration-150">
            
            {{-- Ligne 1: Libellé et Badge Statut --}}
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $annee->libelle }}</h3>
                    @if ($annee->statut)
                        <span class="text-[10px] bg-yellow-600 text-white px-2 py-0.5 rounded-full uppercase font-bold">Active</span>
                    @endif
                </div>

                {{-- MENU KEBAB POUR CHAQUE CARTE --}}
                <div class="relative inline-block text-left" x-data="{ mobile_actions_{{ $annee->id }}: false }" @click.outside="mobile_actions_{{ $annee->id }} = false">
                    <button @click="mobile_actions_{{ $annee->id }} = !mobile_actions_{{ $annee->id }}" type="button" 
                            class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-neutral-700 focus:outline-none">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                        </svg>
                    </button>

                    {{-- Dropdown Actions --}}
                    <div x-show="mobile_actions_{{ $annee->id }}"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-xl bg-white dark:bg-neutral-700 ring-1 ring-black ring-opacity-5 z-20">
                        <div class="py-1">
                            {{-- Action Modifier --}}
                            <a href="{{ route('annee-academiques.edit', $annee) }}" 
                               class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-neutral-600 transition duration-100">
                               ✏️ Modifier l'année
                            </a>

                            {{-- Action Activer/Désactiver --}}
                            <form action="{{ route('annee-academiques.toggle-statut', $annee) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm {{ $annee->statut ? 'text-orange-600' : 'text-green-600' }} hover:bg-gray-100 dark:hover:bg-neutral-600 transition duration-100">
                                    {{ $annee->statut ? '🛑 Désactiver' : '✅ Activer l\'année' }}
                                </button>
                            </form>

                            <div class="border-t border-gray-100 dark:border-neutral-600"></div>

                            {{-- Action Supprimer --}}
                            <form action="{{ route('annee-academiques.destroy', $annee) }}" method="POST" onsubmit="return confirm('Supprimer cette année ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 w-full text-left block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-neutral-600 transition duration-100">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Période --}}
            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                <p><strong>Période :</strong> Du {{ $annee->date_debut->format('d/m/Y') }} au {{ $annee->date_fin->format('d/m/Y') }}</p>
            </div>
        </div>
    @empty
        <div class="p-6 text-center bg-white dark:bg-neutral-800 shadow rounded-lg text-gray-500">
            Aucune année académique trouvée.
        </div>
    @endforelse
</div>

</div>

<script>
    // Masquer les messages flash après 5s
    document.addEventListener('DOMContentLoaded', function() {
        const flash = document.querySelector('#flash-message');
        if (flash) {
            setTimeout(() => {
                flash.style.opacity = '0';
                flash.style.transition = 'opacity 0.5s';
                setTimeout(() => flash.remove(), 500);
            }, 5000);
        }
    });
</script>
@endsection
