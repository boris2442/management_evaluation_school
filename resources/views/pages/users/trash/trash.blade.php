@extends('layouts.admin.layout-admin')

@section('content')
<div class="container mx-auto px-4 py-8 ml-0 md:ml-64 mt-16 dark:bg-neutral-900 min-h-screen">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('users.index') }}" class="p-2 bg-neutral-800 text-gray-400 hover:text-white rounded-lg transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-white uppercase">Corbeille Utilisateurs</h1>
    </div>

    @if($deletedUsers->isEmpty())
        <div class="bg-neutral-800 border border-neutral-700 rounded-xl p-12 text-center">
            <svg class="w-16 h-16 text-neutral-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <p class="text-gray-400 text-lg">La corbeille est vide.</p>
        </div>
    @else
        <div class="bg-neutral-800 rounded-xl shadow-xl overflow-hidden border border-neutral-700">
            <table class="w-full text-left text-sm">
                <thead class="bg-neutral-700 text-gray-300 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4">Utilisateur</th>
                        <th class="px-6 py-4">Supprimé le</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-700">
                    @foreach($deletedUsers as $user)
                    <tr class="hover:bg-neutral-700/50 transition">
                        <td class="px-6 py-4">
                            <div class="text-white font-bold">{{ $user->name }}</div>
                            <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-400">
                            {{ $user->deleted_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            {{-- Bouton Restaurer --}}
                            <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 bg-emerald-600/10 text-emerald-500 hover:bg-emerald-600 hover:text-white px-4 py-2 rounded-lg font-bold transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Restaurer
                                </button>
                            </form>

                            {{-- Optionnel: Suppression définitive (Force Delete) --}}
                            {{-- 
                            <form action="/users-force-delete/{{ $user->id }}" method="POST" onsubmit="return confirm('Attention ! Action irréversible.')">
                                @csrf @method('DELETE')
                                <button class="p-2 text-red-500 hover:bg-red-500/10 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </form> 
                            --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
