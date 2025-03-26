<?php

namespace App\Controllers;

use App\Config\DbConnect;
use App\Models\Restaurant;
use App\Models\User;


class Home
{
    public function execute()
    {
        // Démarre la session si elle n'est pas encore active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Connexion à la base de données
        $pdo = DbConnect::getPDO();

        // Récupère des restaurants et des avis via les modèles
        $restaurants = Restaurant::getRandomRestaurants($pdo);
        $reviews = Restaurant::getRandomReviews($pdo);

        // Récupère les photos des utilisateurs ayant laissé un avis
        $userModel = new User($pdo);

        // Inclure la vue d'accueil
        require_once '../App/Views/home_view.php';
    }

    // Méthode pour récupérer les étoiles à afficher dans l'avis
    public static function getStars(int $rating): string
    {
        $fullStars = floor($rating);
        $emptyStars = 5 - $fullStars;
        $stars = str_repeat('★', $fullStars) . str_repeat('☆', $emptyStars);
        return $stars;
    }
}
