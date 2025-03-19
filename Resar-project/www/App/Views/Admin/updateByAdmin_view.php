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
            <!-- Formulaire de modification des informations personnelles -->
            <form method="POST" action="?page=update-user">
                <div class="card-personal-info">
                    <h2 class="h2-cardProfil">Informations personnelles</h2>
                    <div class="card-info">
                        <label for="firstName">Nom :</label>
                        <input type="text" name="firstName" id="firstName" value="<?= htmlspecialchars($user->getFirstName()) ?>" required>
                    </div>
                    <div class="card-info">
                        <label for="lastName">Prénom :</label>
                        <input type="text" name="lastName" id="lastName" value="<?= htmlspecialchars($user->getLastName()) ?>" required>
                    </div>
                </div>

                <div class="card-security-info">
                    <h2 class="h2-cardProfil">Informations de sécurité</h2>
                    <div class="card-info">
                        <label for="email">Email :</label>
                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user->getEmail()) ?>">
                    </div>
                    <div class="card-info">
                        <label for="phone">Téléphone :</label>
                        <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($user->getPhone()) ?>" required>
                    </div>

                    <div class="card-info">
                        <label for="roles">Rôle :</label>
                        <select name="roles" id="roles" required>
                            <option value="user" <?= $user->getRole() === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                            <option value="admin" <?= $user->getRole() === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                            <option value="owner" <?= $user->getRole() === 'owner' ? 'selected' : '' ?>>Propriétaire</option>
                        </select>
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

<script src="./scripts/Users/updateUser.js"></script>

<?php
$content = ob_get_clean();
$pageTitle = "Modifier mes informations - ResaR";
require __DIR__ .  "/../layout.php";
