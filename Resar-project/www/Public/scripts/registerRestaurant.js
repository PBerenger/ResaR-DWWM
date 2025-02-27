function showSection(section) {
    const personnelSection = document.querySelector('.section-personnel');
    const restaurantSection = document.querySelector('.section-restaurant');

    // Cache les deux sections par défaut
    personnelSection.style.display = 'none';
    restaurantSection.style.display = 'none';

    // Affiche la section correspondante en fonction de la valeur de 'section'
    if (section === 'restaurant') {
        restaurantSection.style.display = 'block';
    } else {
        personnelSection.style.display = 'block';
    }
}

// Appelle showSection pour afficher la section "personnel" au chargement de la page
window.onload = function () {
    showSection('personnel');
};

