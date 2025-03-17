<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>

<h2>Restaurants</h2>
<p>Choisissez le restaurant qui vous correspond près de chez vous !</p>

<div class="restaurants-container">
    <?php if (!empty($restaurants)): ?>
        <?php foreach ($restaurants as $restaurant): ?>
            <div class="restaurant-card">
                <h3><?= htmlspecialchars($restaurant->getName()); ?></h3>

                <?php
                if (!empty($userProfilePhoto) && file_exists('assets/uploads/users/' . $userProfilePhoto)) {
                    $photoPath = 'assets/uploads/users/' . htmlspecialchars($userProfilePhoto);
                } else {
                    $photoPath = 'assets/uploads/users/u_default.jpg';
                }
                ?>
                <img src="<?= htmlspecialchars($photoPath) ?>"
                    alt="Photo de profil"
                    class="user-photo">

                <p><strong>Téléphone :</strong> <?= htmlspecialchars($restaurant->getPhone()); ?></p>

                <a href="index.php?page=restaurant-details&id=<?= urlencode($restaurant->getId()); ?>" class="btn-more-info">En savoir plus</a>
                <a href="restaurant-resa.php?id=<?= urlencode($restaurant->getId()); ?>" class="btn-more-resa">Réserver</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun restaurant disponible.</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
$pageTitle = "Liste des Restaurants - ResaR";
require "../App/Views/layout.php";
