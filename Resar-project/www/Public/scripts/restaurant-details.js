    document.querySelector('.btn-view-dishes').addEventListener('click', function(e) {
        e.preventDefault(); // Empêche la redirection de la page
        document.getElementById('dishes-container').scrollIntoView({
            behavior: 'smooth' // Ajoute un défilement fluide vers le menu
        });
    });
