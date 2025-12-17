<header class="fixed top-0 right-0 left-0 z-30 bg-white border-b border-gray-200 dark:bg-neutral-900 dark:border-neutral-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <!-- Bouton hamburger mobile -->
            <div class="flex items-center">
                <button id="toggleSidebarMobile" 
                        aria-expanded="true" 
                        aria-controls="sidebar"
                        class="md:hidden mr-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-neutral-800 rounded-lg p-2"
                        onclick="toggleSidebar()">
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                
                <!-- Logo ou titre -->
                <a href="{{ route('dashboard') }}" class="flex ml-2 md:mr-24">
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-gray-800 dark:text-white">
                        {{ config('app.name', 'Evaluations') }}
                    </span>
                </a>
            </div>

            <!-- Right side -->
            <div class="flex items-center space-x-3">
                <!-- Dark mode toggle -->
                <button id="theme-toggle" 
                        type="button" 
                        class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-neutral-800 rounded-lg p-2">
                    <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- User menu -->
                <div class="flex items-center ml-3">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ auth()->user()->name ?? 'Utilisateur' }}
                        </span>
                        
                        <!-- Avatar -->
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Dark mode toggle
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    if (localStorage.getItem('color-theme') === 'dark' || 
        (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleLightIcon.classList.remove('hidden');
        document.documentElement.classList.add('dark');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
        document.documentElement.classList.remove('dark');
    }

    themeToggleBtn.addEventListener('click', function() {
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        }
    });
</script>
