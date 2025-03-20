<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();
?>

<div class="profile-container">
    <h1>Modifier mes informations</h1>

    <div class="card-profil">
        <div class="card-profilAndSecurity">

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <!-- Formulaire de modification des informations personnelles -->
            <form method="POST" action="?page=update-by-admin&id=<?= $user->getId() ?>">
                <div class="card-personal-info">
                    <h2 class="h2-cardProfil">Informations personnelles</h2>
                    <div class="card-info">
                        <label for="firstName">Prénom :</label>
                        <input type="text" id="firstName" name="firstName" value="<?= htmlspecialchars($user->getFirstName()) ?>" required>
                    </div>
                    <div class="card-info">
                        <label for="lastName">Nom :</label>
                        <input type="text" id="lastName" name="lastName" value="<?= htmlspecialchars($user->getLastName()) ?>" required>
                    </div>
                </div>

                <div class="card-security-info">
                    <h2 class="h2-cardProfil">Informations de sécurité</h2>
                    <div class="card-info">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
                    </div>
                    <div class="card-info">
                        <label for="phone">Téléphone :</label>
                        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user->getPhone()) ?>">
                    </div>

                    <div class="card-info">
                        <label>Rôles :</label>
                        <?php
                        $roles = $user->getRole();
                        ?>
                        <label><input type="checkbox" name="roles[]" value="client" <?= in_array('client', $roles) ? 'checked' : '' ?>> Utilisateur</label>
                        <label><input type="checkbox" name="roles[]" value="admin" <?= in_array('admin', $roles) ? 'checked' : '' ?>> Administrateur</label>
                        <label><input type="checkbox" name="roles[]" value="owner" <?= in_array('owner', $roles) ? 'checked' : '' ?>> Propriétaire</label>
                    </div>
                </div>


                <div class="card-action">
                    <button type="button" class="btn-modifier" onclick="showPopup()">Enregistrer les modifications</button>
                    <a href="<?= $_SESSION['role'] === 'admin' ? '?page=admin-home' : '?page=profil-user' ?>" class="btn-retour">Annuler</a>
                </div>

                <!-- Popup -->
                <div id="confirmationPopup" class="popup-overlay">
                    <div class="popup">
                        <p>Voulez-vous modifier vos informations ?</p>
                        <button onclick="submitForm()" class="btn-confirm">Confirmer</button>
                        <button onclick="hidePopup()" class="btn-cancel">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="./scripts/Admin/updateByAdmin.js"></script>

<?php
$content = ob_get_clean();
$pageTitle = "Modifier mes informations - ResaR";
require __DIR__ .  "/../layout.php";
