<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>

<div class="photo-upload-container">
    <h1>Modifier ma photo de profil</h1>

    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form action="?page=update-photo" method="post" enctype="multipart/form-data">
        <label for="profile_photo">Choisissez une photo :</label>
        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" required>
        <button type="submit" name="submit_photo">Télécharger</button>
    </form>
    
    <a href="?page=profil-user" class="btn-back">Retour à mon profil</a>
</div>

<?php
$content = ob_get_clean();
$pageTitle = "Modifier Photo - ResaR";
require __DIR__ . "/../layout.php";
?>
