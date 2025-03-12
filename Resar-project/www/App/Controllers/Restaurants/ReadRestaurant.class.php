<?php

namespace App\Controllers\Restaurants;

use App\Config\DbConnect;
use App\Models\{User, Restaurant};

class ReadRestaurant
{
    public function execute(array $postData)
    {
        $pdo = DbConnect::getPDO();

        // Récupération des restaurants
        $restaurant = new Restaurant($pdo);
        $restaurants = $restaurant->getAllRestaurants();


        // Vérifier si l'utilisateur est connecté
        $userAdmin = false;
        if (!empty($_SESSION["USER_ID"])) {
            $user = new User($pdo);
            $user->findUserById($_SESSION["USER_ID"]);
            // $userAdmin = $user->isAdmin();
        }
        require __DIR__ . "/../../Views/Restaurants/restaurants_view.php";
    }
}
