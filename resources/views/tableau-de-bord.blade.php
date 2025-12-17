@extends('layouts.admin.layout-admin')

@section('content')
<div class="container mx-auto px-4 py-8 ml-0 md:ml-64 mt-16 min-h-screen  dark:bg-neutral-900 transition-colors duration-300">
    
    {{-- ENTÊTE ET SÉLECTEUR D'ANNÉE --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">Analytics <span class="text-blue-600">Global</span></h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Données consolidées pour l'exercice <span class="font-bold text-gray-800 dark:text-gray-200">{{ $anneeActive->libelle }}</span></p>
        </div>
        
        {{-- Badge Statut --}}
        <div class="flex items-center gap-3 bg-white dark:bg-neutral-800 p-2 pr-4 rounded-full shadow-sm border dark:border-neutral-700">
            <div class="bg-green-500 h-8 w-8 rounded-full flex items-center justify-center animate-pulse">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold dark:text-gray-200">Système Actif</span>
        </div>
    </div>

    {{-- SECTION 1 : CHIFFRES CLÉS (Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Étudiants --}}
        <div class="bg-white dark:bg-neutral-800 rounded-3xl p-6 shadow-sm border dark:border-neutral-700 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-2xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Inscriptions</p>
                    <h2 class="text-3xl font-black dark:text-white">{{ $statsGlobales['total_etudiants'] }}</h2>
                </div>
            </div>
        </div>

        {{-- Enseignants --}}
        <div class="bg-white dark:bg-neutral-800 rounded-3xl p-6 shadow-sm border dark:border-neutral-700 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-purple-100 dark:bg-purple-900/30 text-purple-600 rounded-2xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Corps Enseignant</p>
                    <h2 class="text-3xl font-black dark:text-white">{{ $statsGlobales['total_enseignants'] }}</h2>
                </div>
            </div>
        </div>

        {{-- Taux de Réussite --}}
        <div class="bg-white dark:bg-neutral-800 rounded-3xl p-6 shadow-sm border dark:border-neutral-700 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-green-100 dark:bg-green-900/30 text-green-600 rounded-2xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Taux Admis</p>
                    <h2 class="text-3xl font-black dark:text-white">{{ $performance['taux_reussite'] }}%</h2>
                </div>
            </div>
        </div>

        {{-- Moyenne Générale --}}
        <div class="bg-white dark:bg-neutral-800 rounded-3xl p-6 shadow-sm border dark:border-neutral-700 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-orange-100 dark:bg-orange-900/30 text-orange-600 rounded-2xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Moyenne Gné</p>
                    <h2 class="text-3xl font-black dark:text-white">{{ $performance['moyenne_generale'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 2 : GRAPHIQUES ET TOP 5 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Graphique de Répartition --}}
        <div class="lg:col-span-1 bg-white dark:bg-neutral-800 p-8 rounded-3xl shadow-sm border dark:border-neutral-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold dark:text-white">Effectifs par Filière</h3>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Visualisation</span>
            </div>
            <div class="h-80">
                <canvas id="chartSpecialite"></canvas>
            </div>
        </div>

       

        {{-- Graphique Mixité (Sexe) --}}
        <div class="bg-white dark:bg-neutral-800 p-8 rounded-3xl shadow-sm border dark:border-neutral-700">
            <h3 class="text-xl font-bold mb-6 dark:text-white">Répartition de Genre</h3>
            <div class="h-64">
                <canvas id="chartSexe"></canvas>
            </div>
        </div>


         {{-- Tableau d'Honneur (Top 5) --}}
        <div class="bg-white dark:bg-neutral-800 p-8 rounded-3xl shadow-sm border dark:border-neutral-700">
            <h3 class="text-xl font-bold mb-6 dark:text-white">Tableau d'Honneur 🏆</h3>
            <div class="space-y-6">
                @forelse($performance['top_etudiants'] as $major)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-400 flex items-center justify-center text-white font-bold text-sm shadow-sm group-hover:scale-110 transition-transform">
                            {{ substr($major['nom'], 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold dark:text-white">{{ $major['nom'] }}</p>
                            <p class="text-xs text-gray-500">{{ $major['specialite'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full text-xs font-black">
                            {{ $major['moyenne'] }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-10">Aucune donnée disponible</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- CHART.JS CONFIG --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Config Chart Filières
    new Chart(document.getElementById('chartSpecialite'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($repartition->pluck('label')) !!},
            datasets: [{
                label: 'Étudiants',
                data: {!! json_encode($repartition->pluck('total')) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 12,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } }
        }
    });

    // Config Chart Sexe
    new Chart(document.getElementById('chartSexe'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($demographie->pluck('sexe')) !!},
            datasets: [{
                data: {!! json_encode($demographie->pluck('total')) !!},
                backgroundColor: ['#3b82f6', '#ec4899', '#94a3b8'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } }
        }
    });
</script>
@endsection
