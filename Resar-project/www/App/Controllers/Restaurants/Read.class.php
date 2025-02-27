<?php

namespace App\Controllers\Restaurants;

use App\Config\DbConnect;
use App\Models\{User, Restaurant};

class Read
{
    public function execute(array $postData)
    {
        // session_start(); // Démarrer la session si ce n'est pas déjà fait

        $pdo = DbConnect::getPDO();

        // Récupération des restaurants
        $restaurant = new Restaurant($pdo);
        $restaurants = $restaurant->getAllRestaurants();


        // Vérifier si l'utilisateur est connecté
        $userAdmin = false;
        if (!empty($_SESSION["USER_ID"])) {
            $user = new User($pdo);
            $user->findUserById($_SESSION["USER_ID"]);
            $userAdmin = $user->isAdmin();
        }

        // Inclure la vue en passant les variables
        require __DIR__ . "/../../Views/Restaurants/restaurants_view.php";
    }
}
