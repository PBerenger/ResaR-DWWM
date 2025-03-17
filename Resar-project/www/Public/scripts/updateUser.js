// Fonction pour afficher le popup de confirmation
function showPopup() {
    const popup = document.getElementById("confirmationPopup");
    popup.style.display = "flex";
}

// Fonction pour masquer le popup
function hidePopup() {
    const popup = document.getElementById("confirmationPopup");
    popup.style.display = "none";
}

// Fonction pour soumettre le formulaire
function submitForm() {
    const form = document.querySelector("form");
    form.submit();
}

// Fermer le popup si l'utilisateur clique en dehors de la popup
window.addEventListener("click", (event) => {
    const popup = document.getElementById("confirmationPopup");
    if (event.target === popup) {
        hidePopup();
    }
});
