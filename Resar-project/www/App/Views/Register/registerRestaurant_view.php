<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>

<div class="rrestaurant-top">
    <div class="rrestaurant-présentation">
        <h2>Inscrivez votre restaurant sur</h2>
        <h1><span class="miroir-h">R</span>es<span class="miroir-xy">e</span>R</h1>
        <p>Augmentez vos revenus, dotez-vous d’une meilleure visibilité et fidélisez vos clients...</p>
    </div>

    <form method="POST" action="?page=register-restaurant">
        <!-- Section Informations Personnelles -->
        <div class="section section-personnel">
            <h3>Vos informations personnelles</h3>
            <input type="text" name="prenom" placeholder="Prénom" value="Res" required>
            <input type="text" name="nom" placeholder="Nom" value="Tau" required>
            <input type="email" name="email" placeholder="Email" value="res.tau@gmail.com" required>
            <input type="tel" name="telephone" pattern="[0-9]{10}" maxlength="10" placeholder="Votre téléphone" value="0123456789" required>
            <input type="password" name="password" placeholder="Mot de passe" value="Password@456" required>
            <input type="password" name="passwordRepeat" placeholder="Confirmation du mot de passe" value="Password@456" required>
            <div class="button-container">
                <button type="button" class="next-btn" onclick="showSection('restaurant')">Suivant</button>
            </div>
        </div>

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
                <button type="button" class="prev-btn" onclick="showSection('personnel')">Précédent</button>
            </div>
        </div>
    </form>
</div>

<div id="rrestaurant-mid" class="rrestaurant-mid">
    <h2>Pourquoi s'inscrire sur <span class="miroir-h">R</span>es<span class="miroir-xy">e</span>R ?</h2>

    <div class="reasons-container">
        <div class="reason1">
            <h3>Obtenez plus de visibilité en ligne</h3>
            <p>TheFork Manager est la première plateforme de recherche...</p>
        </div>

        <div class="reason2">
            <h3>Augmentez votre taux d'occupation</h3>
            <p>Un modèle de gestion gagnant-gagnant sans aucun risque...</p>
        </div>

        <div class="reason3">
            <h3>Luttez contre les no-shows</h3>
            <p>Réduisez le nombre des no-shows à l’aide des outils...</p>
        </div>

        <div class="reason4">
            <h3>Faites appel aux experts du secteur</h3>
            <p>Les équipes de TheFork accompagnent les restaurateurs...</p>
        </div>
    </div>
</div>


<div class="rrestaurant-bot">
    <div class="rrestaurant-img-background"></div>
</div>

<script src="./scripts/registerRestaurant.js"></script>

<?php
$content = ob_get_clean();
$pageTitle = "Inscription restaurateur - ResaR";
require __DIR__ .  "/../layout.php";
