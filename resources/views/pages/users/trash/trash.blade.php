@extends('layouts.admin.layout-admin')

@section('content')
<div class="ml-0 md:ml-64 min-h-screen dark:bg-[#1F2937] antialiased transition-colors duration-300 content mt-16"
     x-data="{ 
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
    
    <div class="container mx-auto px-6 py-10">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('users.index') }}" class="p-2 text-gray-400 hover:text-white rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl   font-extrabold tracking-tight text-gray-900 dark:text-white uppercase">Corbeille Utilisateurs</h1>
                <p class="text-sm text-gray-500">Gestion des comptes supprimés temporairement</p>
            </div>
        </div>

        @if($deletedUsers->isEmpty())
            <div class="border border-dashed border-neutral-700 rounded-2xl p-20 text-center">
                <svg class="w-20 h-20 text-neutral-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <p class="text-gray-500 text-xl font-medium">La corbeille est vide.</p>
            </div>
        @else
            {{-- BARRE D'ACTION GROUPÉE FLOTTANTE --}}
            <template x-if="selectedUsers.length > 0">
                <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[200] bg-gray-900 border border-neutral-700 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-6 animate-bounce-in">
                    <span class="text-sm font-bold border-r border-neutral-700 pr-6">
                        <span x-text="selectedUsers.length"></span> sélectionné(s)
                    </span>
                    
                    <div class="flex gap-3">
                        {{-- Restaurer la sélection --}}
                        <form action="{{ route('users.bulkRestore') }}" method="POST">
                            @csrf
                            <template x-for="id in selectedUsers" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-lg text-xs font-black uppercase transition-all">
                                Restaurer
                            </button>
                        </form>

                        {{-- Supprimer définitivement la sélection --}}
                        <form action="{{ route('users.bulkForceDelete') }}" method="POST" @submit.prevent="if(confirm('Action IRRÉVERSIBLE ! Supprimer définitivement ces utilisateurs ?')) $el.submit()">
                            @csrf @method('DELETE')
                            <template x-for="id in selectedUsers" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-xs font-black uppercase transition-all">
                                Vider définitivement
                            </button>
                        </form>
                    </div>

                    <button @click="selectedUsers = []; allSelected = false" class="text-gray-500 hover:text-white transition ml-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </template>

            <div class="rounded-2xl shadow-xl overflow-hidden border border-neutral-700 dark:bg-[#111827]/50 backdrop-blur-sm">
                <table class="w-full text-left text-sm">
                    <thead class="  dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700  ">
                        <tr>
                            <th class="px-6 py-4 w-10">
                                <input type="checkbox" @click="toggleAll()" x-model="allSelected"
                                       class="w-5 h-5 rounded border-neutral-600 bg-[#1F2937] text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </th>
                            <th class="px-6 py-4 tracking-wider">Utilisateur</th>
                            <th class="px-6 py-4 tracking-wider">Date de suppression</th>
                            <th class="px-6 py-4 text-right tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y ">
                        @foreach($deletedUsers as $user)
                        <tr class="transition-colors" :class="selectedUsers.includes('{{ $user->id }}') ? 'bg-blue-600/10' : 'hover:bg-neutral-800/30'">
                            <td class="px-6 py-4">
                                <input type="checkbox" value="{{ $user->id }}" x-model="selectedUsers"
                                       class="user-checkbox w-5 h-5 rounded border-neutral-600 bg-[#1F2937] text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-white font-bold">{{ $user->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-400 font-medium">
                                <span class=" px-2 py-1 rounded text-xs">
                                    {{ $user->deleted_at->translatedFormat('d M Y à H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2" x-data="{ rowMenu: false }">
                                     <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                        @csrf
                                        <button title="Restaurer" class="p-2 text-emerald-500 hover:bg-emerald-500/10 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('users.forceDelete', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
                                        @csrf @method('DELETE')
                                        <button title="Supprimer définitivement" class="p-2 text-red-500 hover:bg-red-500/10 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    @keyframes bounce-in {
        0% { transform: translate(-50%, 100px); opacity: 0; }
        60% { transform: translate(-50%, -10px); opacity: 1; }
        100% { transform: translate(-50%, 0); opacity: 1; }
    }
    .animate-bounce-in {
        animation: bounce-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
</style>
@endsection
