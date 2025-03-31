<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();
?>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success_message']) ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($_SESSION['error_message']) ?>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>


<h1>Mon profil Restaurateur</h1>
<a class="btn-ajouter" href="?page=create-restaurant">Ajouter un restaurant</a>
<div class="card-restaurant">
    <?php if (!empty($restaurants)) : ?>
        <ul>
            <?php foreach ($restaurants as $restaurant) : ?>
                <li>
                    <h2><?= htmlspecialchars($restaurant['name']) ?></h2>
                    <div class="card-restaurant-photo">
                        <?php
                        $photoPathRestaurant = (!empty($restaurant['photo_path']) && is_file($restaurant['photo_path']))
                            ? $restaurant['photo_path']
                            : 'assets/uploads/restaurants/r_default.jpg';
                        ?>
                        <img src="<?= htmlspecialchars($photoPath) ?>" alt="Photo de restaurant" class="restaurant-photo">
                    </div>

                    <div class="restaurant-details">
                        <div class="infos-restaurant">
                            <p>Adresse : <?= htmlspecialchars($restaurant['address']) ?></p>
                            <p>Téléphone : <?= htmlspecialchars($restaurant['phone']) ?></p>
                            <p>Ville : <?= htmlspecialchars($restaurant['city']) ?></p>
                            <p>Pays : <?= htmlspecialchars($restaurant['country']) ?></p>
                            <p>Code postal : <?= htmlspecialchars($restaurant['zip_code']) ?></p>
                        </div>
                        <div class="description-restaurant">
                            <p>Description : <?= htmlspecialchars($restaurant['description']) ?></p>
                        </div>
                    </div>

                    <div class="card-action">
                        <form method="POST" action="?page=delete-restaurant" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce restaurant ? Cette action est irréversible.')">
                            <input type="hidden" name="restaurant_id" value="<?= htmlspecialchars($restaurant['idRestaurants']) ?>">
                            <button type="submit" class="btn-supprimer">❌ Supprimer le restaurant ❌</button>
                        </form>
                        <a class="btn-modifier" href="?page=update-restaurant&id=<?= htmlspecialchars($restaurant['idRestaurants']) ?>">modifier les informations de ce restaurant</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>Aucun restaurant enregistré.</p>
    <?php endif; ?>
</div>



<!-- <script src="./scripts/Admin/adminHome.js"></script> -->

<?php
$content = ob_get_clean();
$pageTitle = "Accueil - ResaR";
require __DIR__ .  "/../layout.php";
