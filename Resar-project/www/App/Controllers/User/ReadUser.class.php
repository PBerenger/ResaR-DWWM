<?php

namespace App\Controllers\User;

use App\Config\DbConnect;
use App\Models\User;

class ReadUser
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
        
        $pdo = DbConnect::getPDO();
        $userId = $_SESSION['user_id'];
        
        $userModel = new User($pdo);
        $user = $userModel->findUserById($userId);
        if ($user) {
            $_SESSION['phone'] = $user->getPhone();
        } else {
            $_SESSION['error_message'] = "Numéro de téléphone introuvable.";
            header("Location: ?page=error");
            exit;
        }

        require __DIR__ . '/../../Views/Profils/profilUser_view.php';
    }
}