<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Config\DbConnect;

class ReadAdmin
{
    public function execute()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérification si l'utilisateur est bien connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login-user");
            exit;
        }

        // Connexion à la base de données
        $pdo = DbConnect::getPDO();
        $userModel = new User($pdo);
        
        // Récupérer tous les utilisateurs
        $usersList = $userModel->getAllUsers();

        // Inclure la vue et passer les données
        require __DIR__ . '/../../Views/Admin/adminHome_view.php';
    }
}
