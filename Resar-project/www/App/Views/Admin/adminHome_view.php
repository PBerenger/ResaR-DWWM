<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();
?>
<h2>Vos information administrateur :</h2>

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
    </div>
</div>


<h2>Liste des utilisateurs</h2>

<form method="GET" action="?page=admin-home" class="filter-form">
    <label for="filter">Filtrer par :</label>
    <select name="filter" id="filter">
        <option value="id_asc" <?= ($_GET['filter'] ?? '') === 'id_asc' ? 'selected' : '' ?>>ID (Ascendant)</option>
        <option value="id_desc" <?= ($_GET['filter'] ?? '') === 'id_desc' ? 'selected' : '' ?>>ID (Descendant)</option>
        <option value="admin" <?= ($_GET['filter'] ?? '') === 'admin' ? 'selected' : '' ?>>Rôle Admin</option>
        <option value="date_asc" <?= ($_GET['filter'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Date (Ancien à Récent)</option>
        <option value="date_desc" <?= ($_GET['filter'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Date (Récent à Ancien)</option>
        <option value="reservations_desc" <?= ($_GET['filter'] ?? '') === 'reservations_desc' ? 'selected' : '' ?>>Réservations (Descendant)</option>
        <option value="reservations_asc" <?= ($_GET['filter'] ?? '') === 'reservations_asc' ? 'selected' : '' ?>>Réservations (Ascendant)</option>
        <option value="restaurants_desc" <?= ($_GET['filter'] ?? '') === 'restaurants_desc' ? 'selected' : '' ?>>Restaurants (Descendant)</option>
        <option value="restaurants_asc" <?= ($_GET['filter'] ?? '') === 'restaurants_asc' ? 'selected' : '' ?>>Restaurants (Ascendant)</option>
    </select>

    <label for="search">Rechercher :</label>
    <input type="text" name="search" id="search" placeholder="Nom, Prénom, Email" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

    <button type="submit" class="btn-filter">Appliquer</button>
</form>

<?php if (!empty($usersList)): ?>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Rôles</th>
                <th>Date de création</th>
                <th>Réservations</th>
                <th>Restaurants possédés</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usersList as $user): ?>
                <tr title='modifier' data-id="<?= htmlspecialchars($user['idUsers']) ?>" class="clickable-row">
                    <td><?= htmlspecialchars($user['idUsers'] ?? 'INCONNU') ?></td>
                    <td><?= htmlspecialchars($user['firstName'] ?? 'INCONNU') ?></td>
                    <td><?= htmlspecialchars($user['lastName'] ?? 'INCONNU') ?></td>
                    <td><?= htmlspecialchars($user['email'] ?? 'INCONNU') ?></td>
                    <td><?= htmlspecialchars($user['phone'] ?? 'Non renseigné') ?></td>
                    <td><?= htmlspecialchars($user['roles'] ?? 'INCONNU', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (new DateTime($user['created_at'] ?? 'ERREUR'))->format('d/m/Y H:i') ?></td>
                    <td><?= htmlspecialchars($user['totalReservations'] ?? 'ERREUR') ?></td>
                    <td><?= htmlspecialchars($user['totalOwnedRestaurants'] ?? 'ERREUR') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun utilisateur trouvé.</p>
<?php endif; ?>

<script src="./scripts/Admin/adminHome.js"></script>


<?php
$content = ob_get_clean();
$pageTitle = "Accueil - ResaR";
require __DIR__ .  "/../layout.php";
