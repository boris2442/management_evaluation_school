<!DOCTYPE html>
{{-- AJOUT CLASSE DYNAMIQUE : Laissez la gestion de la classe 'dark' au script ci-dessous --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-300">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>@yield('title')</title>

    {{-- Script D'INITIALISATION DU DARK MODE (ESSENTIEL) --}}
    <script>
        // Script pour initialiser le mode sombre et éviter le FOUC (Flash of Unstyled Content)
        (function() {
            const rootHtml = document.documentElement;
            const theme = localStorage.getItem('color-theme');

            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                rootHtml.classList.add('dark');
            } else {
                rootHtml.classList.remove('dark');
            }

            // Fonction globale pour basculer le thème (utilisée par le composant 'dark.dark')
            window.toggleDarkMode = function() {
                const isDark = rootHtml.classList.toggle('dark');
                localStorage.setItem('color-theme', isDark ? 'dark' : 'light');
            
                // Déclenche un événement personnalisé (facultatif, mais utile pour d'autres composants)
                document.dispatchEvent(new CustomEvent('theme-change', { detail: { isDark } }));
            };
        })();
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Edu+NSW+ACT+Cursive:wght@400..700&family=Italianno&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,800;1,900&family=Nova+Round&family=Parkinsans:wght@300..800&family=Playwrite+HU:wght@100..400&family=Playwrite+PE+Guides&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/style.css'])
    @endif

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

</head>

{{-- Corps de la page avec fond basique pour la transition --}}
<body 

 class=' dark:bg-gray-900 transition-colors duration-300' 

>

    <div class="flex h-screen overflow-hidden dark:bg-gray-900">
        @include('components.dashboard.sidebar')
        <div class="flex-1 flex flex-col overflow-y-auto">
            @include('components.dashboard.header')
            <main class="flex-1  dark:bg-gray-900 "  >
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
