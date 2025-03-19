<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Config\DbConnect;

class DeleteByAdmin
{
    public function execute()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login-user");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["user_id"])) {
            $userId = (int) $_POST["user_id"];

            $pdo = DbConnect::getPDO();
            $userModel = new User($pdo);

            if ($userModel->deleteUserById($userId)) {
                $_SESSION['success_message'] = "Utilisateur supprimé avec succès.";

            } else {
                $_SESSION['error_message'] = "Erreur lors de la suppression de l'utilisateur.";
            }
        }
        
        header("Location: ?page=admin-home");
        exit;
    }
}
