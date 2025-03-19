document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".clickable-row").forEach(row => {
        row.addEventListener("click", function () {
            let userId = this.getAttribute("data-id");
            console.log("ID de l'utilisateur cliqué :", userId); // Vérifier la récupération de l'ID
            if (userId) {
                window.location.href = "?page=update-user&id=" + userId;
            }
        });
    });
});
