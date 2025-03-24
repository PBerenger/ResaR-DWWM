<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>

<h2>🖼️ Changer votre photo de profil 🖼️</h2>
<?php if (!empty($message)) : ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form id="updatePhoto" action="" method="POST" enctype="multipart/form-data">
    <label for="profilePicInput" class="download-btn">Choisir un fichier</label>
    <input id="profilePicInput" type="file" name="profile_pic" accept="image/*" onchange="previewImage(event)" style="display: none;">
    <br>
    <img id="imagePreview" src="#" alt="Aperçu" style="display: none;">
    <br>
    <button type="submit" name="uploadUserPhoto">Mettre à jour</button>
</form>

<script src="./scripts/Users/updatePhoto.js"></script>
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.min.css">
<!-- Cropper.js JS -->
<script src="https://unpkg.com/cropperjs/dist/cropper.min.js"></script>


<?php
$content = ob_get_clean();
$pageTitle = "Modifier Photo - ResaR";
require __DIR__ . "/../layout.php";
