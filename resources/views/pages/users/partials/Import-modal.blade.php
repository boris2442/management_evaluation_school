<div x-show="openImportModal" x-cloak class="fixed inset-0 z-[200] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" @click="openImportModal = false"></div>

        {{-- Contenu Modale --}}
        <div class="relative bg-white dark:bg-gray-800 rounded-3xl max-w-lg w-full p-8 shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Importation Excel</h2>
                    <p class="text-sm text-gray-500">Ajoutez plusieurs utilisateurs en un clic</p>
                </div>
                <button @click="openImportModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Zone de téléchargement du template --}}
            <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-2xl border border-emerald-100 dark:border-emerald-800/30 flex items-center gap-4">
                <div class="bg-emerald-500 p-2.5 rounded-xl text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase">Fichier Modèle</p>
                    <a href="{{ asset('templates/import_users_template.xlsx') }}" class="text-sm text-emerald-600 dark:text-emerald-300 hover:underline font-medium">Télécharger le template requis</a>
                </div>
            </div>

            {{-- Formulaire --}}
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                {{-- Zone d'affichage des erreurs --}}
@if(session('error'))
    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-xs">
        {{ session('error') }}
    </div>
@endif


                @csrf
                <div class="space-y-6">
                    <div class="group relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-10 text-center hover:border-blue-500 dark:hover:border-blue-400 transition-all cursor-pointer">
                        <input type="file" name="file" accept=".xlsx, .xls, .csv" 
                               class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
                        <div class="space-y-2">
                            <div class="mx-auto w-12 h-12 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Glissez votre fichier ici ou <span class="text-blue-600">parcourez</span></p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold italic">Format supporté : .XLSX (Max 2Mo)</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-500/20 transition-all active:scale-95 uppercase tracking-widest text-xs">
                        Lancer l'intégration des données
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- <script>
    document.querySelector('form[action*="import"]').addEventListener('submit', function() {
        alert('Le formulaire tente de partir !');
    });
</script> --}}
