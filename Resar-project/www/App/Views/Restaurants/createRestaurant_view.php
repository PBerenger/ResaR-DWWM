<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();
?>

<div id="rrestaurant-top" class="rrestaurant-top">
    <div class="rrestaurant-présentation">
        <h2>Inscrivez votre nouveau restaurant sur</h2>
        <h1><span class="miroir-h">R</span>es<span class="miroir-xy">e</span>R</h1>
        <p>Augmentez vos revenus, dotez-vous d’une meilleure visibilité et fidélisez vos clients...</p>
    </div>

    <form method="POST" action="?page=create-restaurant">
        <!-- Section Informations Personnelles -->
        
        <!-- Section Informations Restaurant -->
        <div class="section section-restaurant">
            <h3>Informations sur votre restaurant</h3>
            <input type="text" name="nomRestaurant" placeholder="Nom du restaurant" value="ResTauto" required>
            <input type="text" name="address" placeholder="Adresse" value="12 place des roues" required>
            <input type="text" name="city" placeholder="Ville" value="Lille" required>
            <input type="text" name="zip_Code" placeholder="Code postal" value="59000" required>
            <input type="text" name="country" placeholder="Pays" value="france" required>
            <input type="hidden" name="role" value="3">
            <textarea name="description" placeholder="Décrivez votre restaurant"></textarea>
            <div class="button-container">
                <button type="submit" class='submitRestaurant' name="userSubmit">S'inscrire</button>
            </div>
        </div>
    </form>
</div>



<!-- <script src="./scripts/Admin/adminHome.js"></script> -->

<?php
$content = ob_get_clean();
$pageTitle = "Accueil - ResaR";
require __DIR__ .  "/../layout.php";
