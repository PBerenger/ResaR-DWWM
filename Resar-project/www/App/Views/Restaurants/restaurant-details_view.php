<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>

<h2><?= htmlspecialchars($restaurant->getName()); ?></h2>

<div class="restaurants-details-container">
    <?php
    $photoPath = !empty($restaurant->getPhoto()) && file_exists('assets/uploads/restaurants/' . $restaurant->getPhoto())
        ? 'assets/uploads/restaurants/' . htmlspecialchars($restaurant->getPhoto())
        : 'assets/uploads/restaurants/r_default.jpg';
    ?>
    <img src="<?= htmlspecialchars($photoPath) ?>"
        alt="Photo du restaurant"
        class="restaurant-photo-cover">

    <div class="restaurant-details-bottom">
        <?php
        $photoPath = !empty($restaurant->getPhoto()) && file_exists('assets/uploads/users/' . $restaurant->getPhoto())
            ? 'assets/uploads/users/' . htmlspecialchars($restaurant->getPhoto())
            : 'assets/uploads/users/u_default.jpg';
        ?>
        <div class="restaurant-profile-card">
            <img src="assets/uploads/users/u_default.jpg" alt="Photo de profil" class="restaurant-profile-photo">
        </div>

        <div class="restaurant-infos">
            <p>Propriétaire : <?= htmlspecialchars($restaurant->getOwnerId()); ?></p>
            <p>Téléphone : <?= htmlspecialchars($restaurant->getPhone()); ?></p>
            <p>Adresse : <?= htmlspecialchars($restaurant->getAddress()); ?></p>
            <p>Code postal : <?= htmlspecialchars($restaurant->getZipCode()); ?> | Ville : <?= htmlspecialchars($restaurant->getCity()); ?></p>
            <p>Description : <?= htmlspecialchars($restaurant->getDescription()); ?></p>
            <p>Date de création : <?= htmlspecialchars($restaurant->getCreatedAt()); ?></p>

            <button class="btn-view-dishes">Voir le menu</button>
            <a href="restaurant_resa.php?id=<?= urlencode($restaurant->getId()); ?>" class="btn-more-resa">Réserver</a>
        </div>
    </div>
</div>

<div class="dishes-container" id="dishes-container">
    <h2>Menu du restaurant</h2>
    <?php if (!empty($dishes)): ?>
        <div class="dishes-grid">
            <?php foreach ($dishes as $item): ?>
                <div class="dish-card">
                    <img src="assets/uploads/dishes/d_default.jpg" alt="Photo du plat" class="dish-photo">
                    <div class="dish-info">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p><?= htmlspecialchars($item['description']) ?></p>
                        <p><strong><?= number_format($item['price'], 2) ?> €</strong></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun menu disponible pour ce restaurant.</p>
    <?php endif; ?>
</div>

<script src="./scripts/Restaurants/restaurant-details.js"></script>

<?php
$content = ob_get_clean();
$pageTitle = "Détails du Restaurant - ResaR";
require "../App/Views/layout.php";
