function submitForm() {
    let checkboxes = document.querySelectorAll('input[name="roles[]"]:checked');
    if (checkboxes.length === 0) {
        alert("Veuillez sélectionner au moins un rôle.");
        return;
    }
    document.querySelector("form").submit();
}

// Fonction pour soumettre le formulaire
function confirmSubmitForm() {
    const form = document.querySelector("form");
    form.submit();
}

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

// Fermer le popup si l'utilisateur clique en dehors du popup
window.addEventListener("click", (event) => {
    const popup = document.getElementById("confirmationPopup");
    if (event.target === popup) {
        hidePopup();
    }
});
