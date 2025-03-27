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


<h2>Liste des utilisateurs</h2>

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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usersList as $u): ?>
                <tr title='modifier' data-id="<?= htmlspecialchars($u['idUsers']) ?>" class="clickable-row">
                    <td><?= htmlspecialchars($u['idUsers'] ?? 'INCONNU') ?></td>
                    <td><?= htmlspecialchars($u['firstName'] ?? 'INCONNU') ?></td>
                    <td><?= htmlspecialchars($u['lastName'] ?? 'INCONNU') ?></td>
                    <td><?= htmlspecialchars($u['email'] ?? 'INCONNU') ?></td>
                    <td><?= htmlspecialchars($u['phone'] ?? 'Non renseigné') ?></td>
                    <td><?= htmlspecialchars($u['roles'] ?? 'INCONNU', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (new DateTime($u['created_at'] ?? 'ERREUR'))->format('d/m/Y H:i') ?></td>
                    <td><?= htmlspecialchars($u['totalReservations'] ?? 'ERREUR') ?> résas</td>
                    <td><?= htmlspecialchars($u['totalOwnedRestaurants'] ?? 'ERREUR') ?> poss</td>
                    <td>
                        <form method="POST" action="?page=delete-user" onsubmit="return confirmDeletion('<?= htmlspecialchars($u['firstName']) ?>', '<?= htmlspecialchars($u['lastName']) ?>')">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($u['idUsers']) ?>">
                            <button type="submit" class="btn-supp">🗑️</button>
                        </form>
                    </td>
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
