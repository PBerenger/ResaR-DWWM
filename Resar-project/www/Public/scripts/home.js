//slider

document.addEventListener('DOMContentLoaded', () => {
    const sliderContainer = document.querySelector('.slider-container');
    const prevButton = document.querySelector('.slider-prev');
    const nextButton = document.querySelector('.slider-next');
    let currentIndex = 0;  // Indice du restaurant actuellement affiché
    const totalRestaurants = document.querySelectorAll('.home-restaurant-slide').length;

    // Fonction pour faire défiler à gauche
    function goToPrevSlide() {
        if (currentIndex === 0) {
            currentIndex = totalRestaurants - 1;  // Revenir à la fin du slider
        } else {
            currentIndex--;
        }
        updateSliderPosition();
    }

    // Fonction pour faire défiler à droite
    function goToNextSlide() {
        if (currentIndex === totalRestaurants - 1) {
            currentIndex = 0;  // Revenir au début du slider
        } else {
            currentIndex++;
        }
        updateSliderPosition();
    }

    // Mettre à jour la position du slider
    function updateSliderPosition() {
        const offset = -currentIndex * 100;  // Décalage basé sur l'indice actuel
        sliderContainer.style.transform = `translateX(${offset}%)`;
    }

    // Ajouter les événements sur les boutons
    prevButton.addEventListener('click', goToPrevSlide);
    nextButton.addEventListener('click', goToNextSlide);

    // Défilement automatique toutes les 5 secondes
    setInterval(goToNextSlide, 10000);  // Appelle la fonction goToNextSlide toutes les 5000ms (5 secondes)

    // Ajouter l'événement de clic sur chaque restaurant du slider
    const restaurantSlides = document.querySelectorAll('.home-restaurant-slide');
    restaurantSlides.forEach(slide => {
        slide.addEventListener('click', () => {
            // Récupérer l'ID du restaurant à partir de l'élément HTML (assume que l'ID est stocké en tant qu'attribut de données)
            const restaurantId = slide.getAttribute('data-restaurant-id');
            // Rediriger vers la page de détails du restaurant
            window.location.href = `index.php?page=restaurant-details&id=${encodeURIComponent(restaurantId)}`;
        });
    });
});


// slider tactile

document.addEventListener("DOMContentLoaded", function () {
    const sliderContainer = document.querySelector(".slider-container");
    let startX = 0;
    let isDragging = false;

    sliderContainer.addEventListener("touchstart", (e) => {
        startX = e.touches[0].clientX;
        isDragging = true;
    });

    sliderContainer.addEventListener("touchmove", (e) => {
        if (!isDragging) return;

        let moveX = e.touches[0].clientX - startX;

        // Si l'utilisateur glisse vers la gauche, on passe au slide suivant
        if (moveX < -50) {
            nextSlide();
            isDragging = false; // Empêcher plusieurs changements en un seul mouvement
        }
        // Si l'utilisateur glisse vers la droite, on passe au slide précédent
        else if (moveX > 50) {
            prevSlide();
            isDragging = false;
        }
    });

    sliderContainer.addEventListener("touchend", () => {
        isDragging = false;
    });

    function nextSlide() {
        const slides = document.querySelectorAll(".home-restaurant-slide");
        const slider = document.querySelector(".slider-container");

        let currentTransform = getComputedStyle(slider).transform;
        let translateX = currentTransform !== "none" ? parseInt(currentTransform.split(",")[4]) : 0;

        if (translateX > -((slides.length - 1) * slides[0].offsetWidth)) {
            slider.style.transform = `translateX(${translateX - slides[0].offsetWidth}px)`;
        }
    }

    function prevSlide() {
        const slides = document.querySelectorAll(".home-restaurant-slide");
        const slider = document.querySelector(".slider-container");

        let currentTransform = getComputedStyle(slider).transform;
        let translateX = currentTransform !== "none" ? parseInt(currentTransform.split(",")[4]) : 0;

        if (translateX < 0) {
            slider.style.transform = `translateX(${translateX + slides[0].offsetWidth}px)`;
        }
    }
});

