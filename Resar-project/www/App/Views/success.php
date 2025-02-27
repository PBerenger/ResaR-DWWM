<?php ob_start(); ?>

<main>
    <body>
        <div class="success-error-container">
            <h1>Félicitations, votre inscription a réussi !</h1>
            <p>Vous avez été inscrit avec succès.</p>

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
</main>

<?php
$content = ob_get_clean();
$pageTitle = "Succès - ResaR";
require "layout.php";