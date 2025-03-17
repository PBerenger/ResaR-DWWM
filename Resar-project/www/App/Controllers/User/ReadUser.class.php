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

        // Vérification si l'utilisateur est bien connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login-user");
            exit;
        }
        
        // Connexion à la base de données
        $pdo = DbConnect::getPDO();
        $userId = $_SESSION['user_id'];
        
        // Récupérer les informations de l'utilisateur
        $userModel = new User($pdo);
        $user = $userModel->findUserById($userId);
        if ($user) {
            $_SESSION['phone'] = $user->getPhone();
            $_SESSION['photo'] = $user->getPhoto(); //?
        } else {
            $_SESSION['error_message'] = "Numéro de téléphone introuvable.";
            header("Location: ?page=error");
            exit;
        }

        // Si l'utilisateur n'existe pas, afficher une erreur
        if (!$user) {
            echo "Utilisateur introuvable.";
            exit;
        }

        // Inclure la vue du profil utilisateur et passer les données
        require __DIR__ . '/../../Views/Profils/profilUser_view.php';
    }
}