<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

$successMessage = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);
?>

    <body>
        <div class="success-error-container">
            <h1>Félicitations !</h1>

            <?php if ($successMessage): ?>
                <p><?= htmlspecialchars($successMessage) ?></p>
            <?php endif; ?>

            <p id="countdown">Redirection dans 3 secondes...</p>

            <script>
                let seconds = 3;
                let countdownElement = document.getElementById("countdown");

                let countdownInterval = setInterval(function() {
                    seconds--;
                    countdownElement.textContent = "Redirection dans " + seconds + " secondes...";

                    if (seconds <= 0) {
                        clearInterval(countdownInterval);
                        window.location.href = "?page=home";
                    }
                }, 1000);
            </script>
        </div>
    </body>

<?php
$content = ob_get_clean();
$pageTitle = "Succès - ResaR";
require "layout.php";
