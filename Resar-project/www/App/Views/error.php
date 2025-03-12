<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

$errorMessage = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);
?>

<body>
    <div class="success-error-container">
        <h1>Une erreur est survenue !</h1>

        <?php if ($errorMessage): ?>
            <p><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <a href="?page=home" class="btn-return">Retour à l'accueil</a>

    </div>
</body>

<?php
$content = ob_get_clean();
$pageTitle = "erreur - ResaR";
require "layout.php";
