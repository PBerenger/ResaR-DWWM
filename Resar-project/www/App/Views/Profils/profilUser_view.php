<?php ob_start();
echo "HOHO";
var_dump($user) ?>

<main>
    <h1>Votre profil</h1>

    <div id='userInfos'>
        <h2>Informations personnelles</h2>
        <p>Prénom : <?= $user["firstName"] ?></p>
        <p>Nom : <?= $user['lastName'] ?></p>
        <p>Email : <?= $user['email'] ?></p>
        <p>Téléphone : <?= $user['phone'] ?></p>
        <p>Date d'inscription : <?= $user['created_at'] ?></p>
    </div>

</main>

<?php
$content = ob_get_clean();
$pageTitle = "Mon Profil - ResaR";
require __DIR__ .  "/../layout.php";
?>