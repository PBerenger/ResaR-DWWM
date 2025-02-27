<?php ob_start(); ?>

<main>
    <div class="home-container">
        <h1>Bienvenue sur <span class="miroir-h">R</span>es<span class="miroir-xy">e</span>R</h1>
        <div class="home-top">
            <h2>Découvrir des restaurants</h2>
            <p>Bienvenue sur notre réseau social pour les amateurs de bonne cuisine. Découvrez des restaurants, réservez une table, et explorez leurs menus !</p>
        </div>

        <div class="home-mid1">
            <div class="home-restaurants-slider">
                <div class="slider-container">
                    <?php if (!empty($restaurants)): ?>
                        <?php foreach ($restaurants as $restaurant) : ?>
                            <div class="home-restaurant-slide" data-restaurant-id="<?= urlencode($restaurant->getId()); ?>">
                                <h3><?= htmlspecialchars($restaurant->getName()); ?></h3>

                                <?php
                                $photoPath = 'assets/img/r_default.jpg';
                                if (!empty($restaurant->getPhoto()) && file_exists('assets/img/' . $restaurant->getPhoto())) {
                                    $photoPath = 'assets/img/' . htmlspecialchars($restaurant->getPhoto());
                                }
                                ?>
                                <img src="<?= $photoPath ?>" alt="Photo du restaurant" class="restaurant-photo">

                                <div class="restaurant-info">
                                    <p><strong>adresse :</strong> <?= htmlspecialchars($restaurant->getAddress()); ?></p>
                                    <p><strong>Téléphone :</strong> <?= htmlspecialchars($restaurant->getPhone()); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun restaurant disponible.</p>
                    <?php endif; ?>
                </div>

                <!-- Contrôles du slider -->
                <button class="slider-prev">←</button>
                <button class="slider-next">→</button>
            </div>
        </div>

        <div class="separation"></div>
        <h2>LE site pour trouver un restaurant près de chez vous !</h2>
        <div class="separation"></div>
        
        <div class="home-img-background1"></div>
        
        <div class="separation"></div>
        <div class="home-mid2">     
            <h2>Enregistrez votre restaurant</h2>
            <p>Propriétaire d'un restaurant ? Inscrivez-vous pour ajouter votre établissement à notre base de données.</p>
            <a href="index.php?page=register-restaurant" class="btn-more-info">Enregistrer un restaurant</a>
            <a href="index.php?page=register-restaurant#rrestaurant-mid" class="btn-more-info">En savoir plus</a>
        </div>
        
        <div class="home-img-background2"></div>

        <div class="separation"></div>
        <div class="home-bot">
            <h2>Vos avis</h2>
            <div class="home-reviews">
                <?php if (!empty($reviews)) : ?>
                    <?php foreach ($reviews as $review) : ?>
                        <div class="review-box">
                            <div class="review-header">

                                <?php
                                $photoPath = 'assets/img/u_default.jpg';
                                if (!empty($review->userPhoto) && file_exists('assets/img/' . $review->userPhoto)) {
                                    $photoPath = 'assets/img/' . htmlspecialchars($review->userPhoto);
                                }
                                ?>
                                <img src="<?= $photoPath ?>" alt="Photo du profil" class="review-user-photo">

                                <div class="review-user-info">
                                    <h4><?= htmlspecialchars($review->userName) ?></h4>
                                    <p><?= date('d M Y', strtotime($review->created_at)) ?></p>
                                </div>
                            </div>
                            <div class="review-body">
                                <p class="review-comment"><?= nl2br(htmlspecialchars($review->content)) ?></p>
                            </div>
                            <div class="review-footer">
                                <span class="review-rating"><?= \App\Controllers\Home::getStars($review->rating) ?></span>
                            </div>
                            <div class="review-restaurant">
                                <a href="index.php?page=restaurant&id=<?= htmlspecialchars($review->restaurantId) ?>">

                                    <?php
                                    if (!empty($restaurantPhoto) && file_exists('assets/img/' . $restaurantPhoto)) {
                                        $photoPath = 'assets/img/' . htmlspecialchars($restaurantPhoto);
                                    } else {
                                        $photoPath = 'assets/img/r_default.jpg';
                                    }
                                    ?>
                                    <img src="<?= htmlspecialchars($photoPath) ?>"
                                        alt="Photo du restaurant"
                                        class="restaurant-photo">

                                    <h3><?= htmlspecialchars($review->restaurantName) ?></h3>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun avis disponible.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>
<script src="./scripts/home.js"></script>

<?php
$content = ob_get_clean();
$pageTitle = "Accueil - ResaR";
require "layout.php";
