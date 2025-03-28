<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();
?>

<h2>Modifier les informations du restaurant</h2>

<?php if (isset($_SESSION['error_message'])): ?>
    <p style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
<?php endif; ?>

<form action="?page=update-restaurant&id=<?= $restaurant->getId(); ?>" method="POST" enctype="multipart/form-data">
    <label>Nom du restaurant :</label>
    <input type="text" name="name" value="<?= htmlspecialchars($restaurant->getName()); ?>" required>

    <label>Téléphone :</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($restaurant->getPhone()); ?>">

    <label>Description :</label>
    <textarea name="description"><?= htmlspecialchars($restaurant->getDescription()); ?></textarea>

    <label>Adresse :</label>
    <input type="text" name="address" value="<?= htmlspecialchars($restaurant->getAddress()); ?>" required>

    <label>Ville :</label>
    <input type="text" name="city" value="<?= htmlspecialchars($restaurant->getCity()); ?>" required>

    <label>Code postal :</label>
    <input type="text" name="zip_code" value="<?= htmlspecialchars($restaurant->getZipCode()); ?>" required>

    <label>Pays :</label>
    <input type="text" name="country" value="<?= htmlspecialchars($restaurant->getCountry()); ?>" required>

    <label>Photo du restaurant :</label>
    <input type="file" name="restaurant_photo">
    <img src="<?= htmlspecialchars($restaurant->getPhoto()); ?>" alt="Photo actuelle" width="100">

    <button type="submit" name="update_restaurant">Modifier</button>
</form>

<!-- <script src="./scripts/Users/updateUser.js"></script> -->

<?php
$content = ob_get_clean();
$pageTitle = "Modifier mes informations - ResaR";
require __DIR__ .  "/../layout.php";
