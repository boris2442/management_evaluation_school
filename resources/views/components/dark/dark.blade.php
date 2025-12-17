{{-- 
    Composant Dark Mode Toggle (Bouton et Logique JS)
    
    1. Utilise localStorage pour mémoriser le choix.
    2. Ajoute/Supprime la classe 'dark' à la balise <html>.
    3. Utilise la classe 'transition duration-300' de Tailwind pour une animation fluide.
--}}

<button id="theme-toggle" type="button" 
        class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 
               focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 
               rounded-lg text-sm p-2.5 transition duration-300">
    
    {{-- Icône Lune (affichée en mode CLAIR) --}}
    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.293 13.068A8.999 8.001 0 017 3.707a8.999 8.999 0 1013.586 10.957.5.5 0 00-.293-.016z"></path>
    </svg>
    
    {{-- Icône Soleil (affichée en mode SOMBRE) --}}
    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zM6.591 4.591a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414l-.707-.707zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zM4.591 13.409a1 1 0 101.414 1.414l.707-.707a1 1 0 00-1.414-1.414l-.707.707zM10 18a1 1 0 01-1-1v-1a1 1 0 112 0v1a1 1 0 01-1 1zM13.409 15.409a1 1 0 10-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM15.409 6.591a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707z" />
    </svg>
</button>

<script>
    // Référence aux icônes et à l'élément racine
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    const rootHtml = document.documentElement; // L'élément <html>

    // 1. Détecter la préférence stockée
    // Utiliser 'dark' si le local storage le dit OU si c'est la préférence du système ET que le local storage n'a rien dit.
    const isDarkPreferred = localStorage.getItem('color-theme') === 'dark' || 
                           (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

    // Initialiser le thème au chargement
    if (isDarkPreferred) {
        rootHtml.classList.add('dark');
        themeToggleLightIcon.classList.remove('hidden'); // Afficher le soleil (pour passer au mode clair)
    } else {
        rootHtml.classList.remove('dark');
        themeToggleDarkIcon.classList.remove('hidden'); // Afficher la lune (pour passer au mode sombre)
    }

    // 2. Gérer le basculement lors du clic
    document.getElementById('theme-toggle').addEventListener('click', function() {
        
        // --- ÉTAPE CRUCIALE POUR LA TRANSITION FLUIDE ---
        // Ajouter temporairement la transition à la balise racine
        rootHtml.classList.add('transition', 'duration-300');
        
        // Basculer l'icône
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        // Basculer la classe 'dark' sur l'élément <html>
        rootHtml.classList.toggle('dark');
        
        // Mettre à jour le stockage local
        if (rootHtml.classList.contains('dark')) {
            localStorage.setItem('color-theme', 'dark');
        } else {
            localStorage.setItem('color-theme', 'light');
        }

        // Retirer la transition après un court délai pour qu'elle ne s'applique pas
        // à TOUTES les autres modifications de classes CSS du projet, mais seulement au thème.
        setTimeout(() => {
            rootHtml.classList.remove('transition', 'duration-300');
        }, 300); // 300ms correspond à la durée de la transition.
    });
</script>
