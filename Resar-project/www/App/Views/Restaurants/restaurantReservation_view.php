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

<h2>Réservation pour <?= htmlspecialchars($restaurant->getName()); ?></h2>

<form action="index.php?page=restaurant-reservation&id=<?= urlencode($restaurant->getId()); ?>" method="post" class="reservation-form">
    <label for="date">Date :</label>
    <input type="date" name="date" required>

    <label for="heure">Choisissez une heure :</label>
    <div class="checkbox-heures">
        <?php
        $start = new DateTime('18:00');
        $end = new DateTime('22:00');

        while ($start <= $end) {
            $heure = $start->format('H:i');
            echo '<label><input type="radio" name="heure" value="' . $heure . '"> ' . $heure . '</label><br>';
            $start->modify('+30 minutes');
        }
        ?>
    </div>

    <label for="nombre_personnes">Nombre de personnes :</label>
    <input type="number" name="nombre_personnes" min="1" max="20" required>

    <input type="submit" name="submit_reservation" value="Réserver">
</form>


<script src="./scripts/Restaurants/reservation.js"></script>

<?php
$content = ob_get_clean();
$pageTitle = "Réservation - " . htmlspecialchars($restaurant->getName());
require "../App/Views/layout.php";
