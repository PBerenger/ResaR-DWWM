<?php

namespace App\Controllers\User;

use App\Config\DbConnect;
use App\Models\User;

class ReadUser
{
    public function execute(array $postData)
    {
        // session_start(); // Démarrer la session si ce n'est pas déjà fait

        $pdo = DbConnect::getPDO();

        // Récupération des restaurants
        $user = new User($pdo);
        $user = $user->findUserById($_SESSION["USER_ID"]);


        // Vérifier si l'utilisateur est connecté
        $userAdmin = false;
        if (!empty($_SESSION["USER_ID"])) {
            $user = new User($pdo);
            $user->findUserById($_SESSION["USER_ID"]);
            // $userAdmin = $user->isAdmin();
        }
        require __DIR__ . "/../../Views/Profils/profilUser_view.php";
    }
}