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

<div class="profile-container">

    <h1>Mon profil</h1>

    <div class="card-profil">
        <div class="card-profilAndSecurity">
            <!-- Carte des informations personnelles -->
            <div class="card-personal-info">
                <h2 class="h2-cardProfil">Informations personnelles</h2>
                <div class="card-info">
                    Nom : <?= htmlspecialchars($user->getFirstName()) ?>
                </div>
                <div class="card-info">
                    Prénom : <?= htmlspecialchars($user->getLastName()) ?>
                </div>
                <div class="card-info">
                    Role(s) : <?= htmlspecialchars(implode(', ', $user->getRole())) ?>
                </div>
            </div>

            <!-- Carte des informations de sécurité -->
            <div class="card-security-info">
                <h2 class="h2-cardProfil">Informations de sécurité</h2>
                <div class="card-info">
                    Email : <?= htmlspecialchars($user->getEmail()) ?>
                </div>
                <div class="card-info">
                    Téléphone : <?= htmlspecialchars($user->getPhone()) ?>
                </div>
                <?php
                $createdAt = new DateTime($user->getCreatedAt());
                $fmt = new IntlDateFormatter(
                    'fr_FR',
                    IntlDateFormatter::LONG,
                    IntlDateFormatter::SHORT,
                    'Europe/Paris'
                );
                $formattedDate = $fmt->format($createdAt);
                ?>
                <div class="card-info">
                    Date de création : <?= $formattedDate ?>
                </div>

            </div>
            <a href="?page=update-user" class="btn-modifier">Modifier vos informations</a>
            <div class="card-action">
                <form method="POST" action="?page=delete-user" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user->getId()) ?>">
                    <button type="submit" class="btn-supprimer">❌ Supprimer le compte ❌</button>
                </form>
            </div>
        </div>

        <!-- Carte de la photo de profil -->
        <div class="card-profile-photo">
            <?php
            $photoPath = (!empty($user->getPhoto()) && is_file($user->getPhoto()))
                ? $user->getPhoto()
                : 'assets/uploads/users/u_default.jpg';
            ?>
            <img src="<?= htmlspecialchars($photoPath) ?>" alt="Photo de profil" class="user-photo">
            <a href="?page=update-photo" class="btn-modifier">Modifier</a>
        </div>
    </div>
</div>

<div class="separation"></div>

<div class="profilUser-réservation">
    <h2>Réserver une table dans un restaurant</h2>
    <p>Choisissez parmis une liste de restaurants près de chez vous et reservez une table</p>
    <a class="btn-more-resa">Réserver</a>
</div>

<!-- popup -->
<?php if (isset($_SESSION['user_id']) && empty($_SESSION['phone'])): ?>
    <div id="phone-popup" class="popup">
        <div class="popup-content">
            <p>Vous n'avez pas encore renseigné votre numéro de téléphone.</p>
            <p>Il sera utile pour réserver une table !</p>
            <button onclick="window.location.href='?page=update-user'">Renseigner</button>
            <button onclick="closePopup()">Plus tard</button>
        </div>
    </div>
<?php endif; ?>

<script src="./scripts/Users/profilUser.js"></script>

<?php
$content = ob_get_clean();
$pageTitle = "Mon Profil - ResaR";
require __DIR__ .  "/../layout.php";
