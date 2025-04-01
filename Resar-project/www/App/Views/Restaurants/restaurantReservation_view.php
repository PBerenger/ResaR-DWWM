<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>

<h2>RESERVEZ CHEZ <?= htmlspecialchars($restaurant->getName()); ?></h2>
<p>Téléphone : <?= htmlspecialchars($restaurant->getPhone()); ?></p>
<p>Adresse : <?= htmlspecialchars($restaurant->getAddress()); ?></p>
<p>Code postal : <?= htmlspecialchars($restaurant->getZipCode()); ?> | Ville : <?= htmlspecialchars($restaurant->getCity()); ?></p>

<form method="post">
    <label for="date">Date :</label>
    <input type="date" name="date" id="date" value="<?= $date ?>" required onchange="this.form.submit()">

    <label for="time">Horaires disponibles :</label>
    <select name="time" id="time" required>
        <?php foreach ($availableSlots as $slot): ?>
            <option value="<?= $slot ?>"><?= $slot ?></option>
        <?php endforeach; ?>
    </select>

    <input type="hidden" name="restaurantId" value="<?= $restaurantId ?>">
    <input type="hidden" name="userId" value="<?= $_SESSION['user_id'] ?? 0 ?>">

    <button type="submit" name="reserve">Réserver</button>
</form>



<?php
$content = ob_get_clean();
$pageTitle = "Réservation - ResaR";
require __DIR__ .  "/../layout.php";
