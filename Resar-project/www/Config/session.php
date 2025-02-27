<?php

// Générer un token CSRF unique lors de la première session ou à chaque nouvelle session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Token unique de 64 caractères
}
