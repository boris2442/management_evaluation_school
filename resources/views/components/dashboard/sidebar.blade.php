<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0 dark:bg-neutral-900 dark:border-neutral-700">
    
    <!-- Titre entreprise -->
    <div class="h-16 flex items-center justify-center uppercase font-bold text-xl 
                text-gray-800 dark:text-white tracking-wide border-b border-gray-200 dark:border-neutral-700">
       S.G.E.M.A
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-4 space-y-2">
        <!-- Accueil -->
        <a href=""
            class="flex items-center py-2.5 px-4 rounded-lg transition duration-200
               'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-800'">
            <i class="fas fa-home mr-3"></i> 
            <span>Accueil</span>
        </a>

        <!-- Dashboard -->
        <a href=""
            class="flex items-center py-2.5 px-4 rounded-lg transition duration-200
                  'bg-blue-600  text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-800'">
            <i class="fas fa-chart-line mr-3"></i> 
            <span>Dashboard</span>
        </a>

        <!-- Années Académiques -->
     <a href="{{ route('annee-academiques.index') }}"
   class="flex items-center py-2.5 px-4 rounded-lg transition duration-200
   {{ request()->routeIs('annee-academiques.*')
        ? 'bg-[#F3F4F6] dark:bg-black text-gray-900 dark:text-white'
        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-800' }}">
    
    <i class="fas fa-calendar-alt mr-3"></i>
    <span>Années Académiques</span>
</a>

{{-- Si les liens sont active on do --}}
        <!-- Utilisateurs -->
        <a 
        href="{{ route('specialites.index') }}"
          class="flex items-center py-2.5 px-4 rounded-lg transition duration-200
   {{ request()->routeIs('specialites.*')
        ? 'bg-[#F3F4F6] text-gray-900'
        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-800' }}" >
            <i class="fas fa-graduation-cap mr-3"></i> 
            <span>Specialites</span>
        </a>

        <!-- Clients -->
   <a href="{{ route('modules.index') }}"
   class="flex items-center py-2.5 px-4 rounded-lg transition duration-200
   {{ request()->routeIs('modules.*')
        ? 'bg-[#F3F4F6] dark:bg-neutral-800 text-gray-900 dark:text-white'
        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-800' }}">
    
    <i class="fas fa-book mr-3"></i>
    <span>Modules</span>
</a>


        <!-- Inscriptions -->
        <a href="{{ route('inscriptions.index') }}"
            class="flex items-center py-2.5 px-4 rounded-lg transition duration-200
          {{ request()->routeIs('inscriptions.*')
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-800' }}">
            <i class="fas fa-user-graduate mr-3"></i> 
            <span>Inscriptions</span>
        </a>

        <!-- Eva -->
        <a href="{{ route('evaluations.index') }}"
            class="flex items-center py-2.5 px-4 rounded-lg transition duration-200
                 {{ request()->routeIs('evaluations.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-800' }}">
            <i class="fas fa-file-alt mr-3"></i> 
            <span>Evaluations</span>
        </a>

        <!-- Bilan general -->
        <a 
        href="{{ route('bilan.index') }}"
          class="{{ request()->routeIs('bilan.index')
            ? 'bg-blue-600 text-white'
            : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-800' }}">
            <i class="fas fa-chart-bar mr-3"></i> 
            <span>Bilan general</span>
        </a>

        <!-- Séparateur -->
        <div class="border-t border-gray-200 dark:border-neutral-700 my-4"></div>

        <!-- Déconnexion -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" 
                    class="flex items-center w-full py-2.5 px-4 rounded-lg transition duration-200
                           bg-red-500 hover:bg-red-600 text-white dark:hover:bg-red-700">
                <i class="fas fa-sign-out-alt mr-3"></i> 
                <span>Déconnexion</span>
            </button>
        </form>
    </nav>
</aside>

<!-- Overlay mobile -->
<div id="sidebar-overlay" 
     class="fixed inset-0 bg-black bg-opacity-40 z-30 hidden md:hidden" 
     onclick="toggleSidebar()">
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isOpen = sidebar.classList.contains('translate-x-0');

        if (isOpen) {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        } else {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
        }
    }

    // Initialisation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        // Sur mobile, cacher la sidebar par défaut
        if (window.innerWidth < 768) {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('hidden');
        }
    });
</script>
