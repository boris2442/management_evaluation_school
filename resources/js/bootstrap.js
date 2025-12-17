import axios from "axios";
window.axios = axios;
import NProgress from "nprogress"; // 1. Import de la librairie
import "nprogress/nprogress.css"; // 2. Import du CSS (NProgress fournit un fichier CSS que l'on importe directement)

// --- Déclenchement de NProgress ---

// Configuration optionnelle
NProgress.configure({
    minimum: 0.1,
    speed: 500,
    showSpinner: false,
});

// 1. Démarrer la barre de progression au début du chargement de la page
document.addEventListener("DOMContentLoaded", () => {
    NProgress.start();
});

// 2. Terminer la barre de progression lorsque la page est entièrement chargée
window.addEventListener("load", () => {
    NProgress.done();
});

// 3. (Recommandé) Gérer les clics sur les liens pour une meilleure réactivité
document.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", function (event) {
        // Déclencher la progression si le lien pointe vers une page interne (pas un ancre ou un lien externe)
        if (
            this.hostname === window.location.hostname &&
            this.hash.length < 1
        ) {
            NProgress.start();
        }
        // Pour les formulaires POST/PUT/DELETE, on démarrera NProgress au moment du submit.
    });
});
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
