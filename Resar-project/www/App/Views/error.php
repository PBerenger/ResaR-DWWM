<?php ob_start(); ?>

<main>

    <body>
        <div class="success-error-container">
            <h1>Oups, quelque chose s'est mal passé !</h1>
            <p>Nous sommes désolés, mais une erreur s'est produite. Nous travaillons pour résoudre ce problème.</p>

            <h3>Détails de l'erreur :</h3>

            <p>
                <?= isset($_SESSION['error_message']) ? htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8') : "Aucun message d'erreur disponible."; ?>
            </p>


            <?php if (!empty($_SESSION["errorInscription"])): ?>
                <?php foreach ($_SESSION["errorInscription"] as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            <?php endif; ?>


            <!-- <p>Si vous avez besoin d'aide, veuillez contacter notre support technique.</p> -->


            <a href="?page=home" class="btn-return">Retour à l'accueil</a>

        </div>
    </body>
</main>

<?php
$content = ob_get_clean();
$pageTitle = "erreur - ResaR";
require "layout.php";
