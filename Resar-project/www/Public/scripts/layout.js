document.addEventListener('DOMContentLoaded', () => {

    // Dark mode
    const body = document.body;
    const darkModeToggle = document.getElementById("dark-mode-toggle");

    if (darkModeToggle) {
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
            darkModeToggle.textContent = "🌙";
        }

        darkModeToggle.addEventListener("click", () => {
            body.classList.toggle('dark-mode');

            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                darkModeToggle.textContent = "🌙";
            } else {
                localStorage.removeItem('darkMode');
                darkModeToggle.textContent = "☀️";
            }
        });
    }

    // Register menu
    const openRegister = document.querySelector(".open-register");
    const closeRegister = document.getElementById("close-register");
    const registerMenu = document.getElementById("register-menu");

    if (openRegister && registerMenu) {
        openRegister.addEventListener("click", () => {
            registerMenu.classList.add("active");
        });
    }

    if (closeRegister && registerMenu) {
        closeRegister.addEventListener("click", () => {
            registerMenu.classList.remove("active");
        });
    }

    // Fermer le menu si l'on clique à l'extérieur
    document.addEventListener('click', (event) => {
        if (registerMenu.classList.contains("active") && !registerMenu.contains(event.target) && !openRegister.contains(event.target)) {
            registerMenu.classList.remove("active");
        }
    });

    // Menu burger
    const menuToggle = document.getElementById('menu-toggle');
    const closeMenu = document.getElementById('close-menu');
    const navLinks = document.querySelector('.nav-links');

    // Ajouter un événement pour ouvrir le menu
    menuToggle.addEventListener('click', () => {
        navLinks.classList.add('active');
        closeMenu.classList.add('active');
        menuToggle.style.display = 'none'; // Masquer le burger
    });

    // Ajouter un événement pour fermer le menu
    closeMenu.addEventListener('click', () => {
        navLinks.classList.remove('active');
        closeMenu.classList.remove('active');
        menuToggle.style.display = 'block'; // Afficher le burger
    });
});


// popup

function closePopup() {
    document.getElementById("phone-popup").style.display = "none";
}
window.onload = function() {
    if (!document.getElementById("phone-popup")) return; // Si le popup n'existe pas, on n'affiche rien
    if (sessionStorage.getItem('phoneFilled') === 'true') {
        document.getElementById("phone-popup").style.display = "none";
    } else {
        document.getElementById("phone-popup").style.display = "block";
    }
};

function closePopup() {
    document.getElementById("phone-popup").style.display = "none";
    sessionStorage.setItem('phoneFilled', 'true'); // Marquer que le téléphone a été rempli
}
