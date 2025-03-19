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

    <form id="uploadForm" action="?page=update-photo" method="post" enctype="multipart/form-data">
        <label for="profile_photo">Choisissez une photo :</label>
        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" required>

        <div class="crop-container">
            <img id="preview" style="max-width:100%; display:none;">
        </div>

        <input type="hidden" name="cropped_image" id="cropped_image">
        <button type="submit" name="submit_photo">Télécharger</button>
    </form>

    <a href="?page=profil-user" class="btn-back">Retour à mon profil</a>
</div>

<!-- Inclusion de Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="./scripts/Users/updatePhoto.js"></script>

<?php
$content = ob_get_clean();
$pageTitle = "Modifier Photo - ResaR";
require __DIR__ . "/../layout.php";
