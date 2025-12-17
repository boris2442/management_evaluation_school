<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AcademicaPro | Gestion Académique Simplifiée</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <style>
        .dark-mode-transition {
            transition: background-color 0.3s, color 0.3s;
        }
    </style>
    
    <script>
        // Script initial pour éviter le flash de contenu non stylisé (FOUC)
        const rootHtml = document.documentElement;
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            rootHtml.classList.add('dark');
        } else {
            rootHtml.classList.remove('dark');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('theme-toggle-btn');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            
            // Mise à jour de l'icône au chargement
            if (rootHtml.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            themeToggleBtn.addEventListener('click', function() {
                // Ajout temporaire de la transition
                rootHtml.classList.add('dark-mode-transition');
                
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                rootHtml.classList.toggle('dark');
                
                if (rootHtml.classList.contains('dark')) {
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    localStorage.setItem('color-theme', 'light');
                }

                // Retirer la transition après un court délai
                setTimeout(() => {
                    rootHtml.classList.remove('dark-mode-transition');
                }, 300);
            });
        });
    </script>
      <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/style.css'])
      
        @endif
</head>

<body class="dark-mode-transition bg-gray-50 dark:bg-gray-900 font-sans antialiased text-gray-800 dark:text-gray-200">

    <div class="min-h-screen flex flex-col justify-between">

        {{-- Bande de Navigation (Simplifiée) --}}
        <header class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow-md">
            <div class="container mx-auto flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                    🎓 AcademicaPro
                </a>
                
                <nav class="flex items-center space-x-4">
                    {{-- Bouton Dark Mode --}}
                    <button id="theme-toggle-btn" type="button" 
                            class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 
                                   focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 
                                   rounded-lg text-sm p-2.5 transition duration-300">
                        {{-- Icône Lune (sombre) --}}
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.068A8.999 8.001 0 017 3.707a8.999 8.999 0 1013.586 10.957.5.5 0 00-.293-.016z"></path>
                        </svg>
                        {{-- Icône Soleil (clair) --}}
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zM6.591 4.591a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414l-.707-.707zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zM4.591 13.409a1 1 0 101.414 1.414l.707-.707a1 1 0 00-1.414-1.414l-.707.707zM10 18a1 1 0 01-1-1v-1a1 1 0 112 0v1a1 1 0 01-1 1zM13.409 15.409a1 1 0 10-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM15.409 6.591a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707z" />
                        </svg>
                    </button>
                    
                    {{-- Liens de Connexion/Inscription --}}
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium hidden sm:inline-block">Tableau de Bord</a>
                        @else
                            <a href="{{ route('login') }}" class="py-2 px-4 border border-indigo-600 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-50 dark:hover:bg-gray-700 transition duration-150 font-medium">Connexion</a>
                            
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="py-2 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 ml-2 font-medium hidden sm:inline-block">Inscription</a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </div>
        </header>

        {{-- Section Principale (Hero) --}}
        <main class="flex-grow flex items-center">
            <div class="container mx-auto px-4 py-16 sm:py-24 text-center">
                
                <h1 class="text-5xl sm:text-6xl font-extrabold text-gray-900 dark:text-white leading-tight mb-4">
                    La <span class="text-indigo-600 dark:text-indigo-400">Gestion Académique</span> Simplifiée.
                </h1>
                
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-10">
                    Optimisez chaque étape de votre année scolaire, de l'inscription des étudiants à la génération des bilans de compétences. Un seul outil, une clarté totale.
                </p>
                
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-semibold py-3 px-8 rounded-full shadow-lg transition duration-300 transform hover:scale-105">
                            Accéder au Tableau de Bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-semibold py-3 px-8 rounded-full shadow-lg transition duration-300 transform hover:scale-105">
                            Commencer maintenant
                        </a>
                    @endauth
                </div>

                {{-- Illustration simple --}}
                <div class="mt-12 opacity-90 max-w-5xl mx-auto">
                    
                </div>

            </div>
        </main>

        {{-- Footer --}}
        <footer class="p-4 text-center text-sm text-gray-500 dark:text-gray-400 border-t dark:border-gray-700 bg-white dark:bg-gray-800">
            &copy; {{ date('Y') }} AcademicaPro. Tous droits réservés. | Propulsé par Laravel & Tailwind CSS.
        </footer>
    </div>

    </body>
</html>
