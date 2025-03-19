document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".clickable-row").forEach(row => {
        row.addEventListener("click", function () {
            let userId = this.getAttribute("data-id");
            console.log("ID de l'utilisateur cliqué :", userId); // Vérifie la récupération de l'ID
            if (userId) {
                window.location.href = "?page=update-by-admin&id=" + userId;
            }
        });
    });

    // Empêche la propagation du clic sur le bouton de suppression
    document.querySelectorAll(".btn-supp").forEach(button => {
        button.addEventListener("click", function (event) {
            event.stopPropagation(); // Empêche le clic sur la ligne
        });
    });
});