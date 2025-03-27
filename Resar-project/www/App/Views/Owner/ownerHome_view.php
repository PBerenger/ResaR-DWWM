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

<?php if (!empty($restaurants)) : ?>
    <ul>
        <?php foreach ($restaurants as $restaurant) : ?>
            <li>
                <h2><?= htmlspecialchars($restaurant['name']) ?></h2>
                <p>Adresse : <?= htmlspecialchars($restaurant['address']) ?></p>
                <p>Téléphone : <?= htmlspecialchars($restaurant['phone']) ?></p>
                <p>Description : <?= htmlspecialchars($restaurant['description']) ?></p>

                <!-- <a href="?page=owner-reservations&id=<?= $restaurant['id'] ?>">Voir les réservations</a>
                <a href="?page=owner-menu&id=<?= $restaurant['id'] ?>">Voir le menu</a> -->

                <!-- <a href="?page=update-restaurant&id=<?= $restaurant['id'] ?>">Modifier</a> -->
                <div class="card-action">
                    <form method="POST" action="?page=delete-restaurant" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce restaurant ? Cette action est irréversible.')">
                        <input type="hidden" name="restaurant_id" value="<?= htmlspecialchars($restaurant['idRestaurants']) ?>">
                        <button type="submit" class="btn-supprimer">❌ Supprimer le restaurant ❌</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>Aucun restaurant enregistré.</p>
<?php endif; ?>

<a href="?page=addRestaurant">Ajouter un restaurant</a>


<script src="./scripts/Admin/adminHome.js"></script>

<?php
$content = ob_get_clean();
$pageTitle = "Accueil - ResaR";
require __DIR__ .  "/../layout.php";
