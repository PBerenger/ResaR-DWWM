<?php ob_start(); ?>

<main>
    <div class="rrestaurant-top">
        <div class="rrestaurant-présentation">
            <h2>Inscrivez votre restaurant sur</h2>
            <h1><span class="miroir-h">R</span>es<span class="miroir-xy">e</span>R</h1>

            <p>Augmentez vos revenus, dotez-vous d’une meilleure visibilité et fidélisez vos clients en rejoignant nos 55 000 restaurants partenaires déjà réservables sur ResaR, la première plateforme de recherche et de réservation de restaurants en Europe. Testez ResaR Manager, notre logiciel de réservation et de gestion de tables. Visibilité et inscription gratuite. Facturation à l'utilisation : les commissions sont basées sur le nombre de couverts réservés et honorés.</p>
        </div>

        <form method="POST" action="?page=register-user">
            <div class="section section-personnel">
                <h3>Vos informations personnelles</h3>
                <input type="text" name="prenom" placeholder="Prénom" value="Res" required>
                <input type="text" name="nom" placeholder="Nom" value="Tau" required>
                <input type="email" name="email" placeholder="Email" value="res.tau@gmail.com" required>
                <input type="tel" name="telephone" pattern="[0-9]{10}" maxlength="10" placeholder="Votre téléphone" value="0123456789" required>
                <input type="password" name="password" placeholder="Mot de passe" value="Password@456" required>
                <input type="password" name="passwordRepeat" placeholder="Confirmation du mot de passe" value="Password@456" required>
            </div>
            <button type="submit" name="userSubmit">S'inscrire</button>
        </form>
        <!-- Section Informations Personnelles -->
        
        <form method="POST" action="?page=register-restaurant" enctype="multipart/form-data">
            <!-- Section Informations Restaurant -->
            <div class="section section-restaurant">
                <h3>Informations sur votre restaurant</h3>
                <input type="text" name="nomRestaurant" placeholder="Nom du restaurant" value="ResiTauto" required>
                <input type="text" name="address" placeholder="Adresse" value="12 place des roues" required>
                <input type="text" name="city" placeholder="Ville" value="Lille" required>
                <input type="text" name="zip_Code" placeholder="Code postal" value="59000" required>
                <input type="text" name="country" placeholder="Pays" value="france" required>
                <textarea name="description" placeholder="Décrivez votre restaurant"></textarea>
            </div>

            <!-- Section Upload -->
            <div class="section section-upload">
                <h3>Ajoutez des photos</h3>
                <label for="photoProfil">Photo de profil :</label>
                <input type="file" name="photoProfil" accept="image/*">

                <label for="photoRestaurant">Bannière du restaurant :</label>
                <input type="file" name="photoRestaurant" accept="image/*">
            </div>

            <button type="submit" name="restaurantSubmit">S'inscrire</button>
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
</main>
<script src="public/js/script.js"></script>

<?php
$content = ob_get_clean();
$pageTitle = "Inscription restaurateur - ResaR";
require __DIR__ .  "/../layout.php";