<?php

namespace App\Controllers\Restaurants;

use App\Models\Restaurant;
use App\Config\DbConnect;

class DeleteRestaurant
{
    public function execute()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['client', 'owner'])) {
            header("Location: ?page=owner-home");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["restaurant_id"])) {
            $restaurantId = (int) $_POST["restaurant_id"];
            $userId = $_SESSION['user_id'];

            $pdo = DbConnect::getPDO();
            $restaurantModel = new Restaurant($pdo);

            // Vérifier si le restaurant appartient bien à l'utilisateur
            $stmt = $pdo->prepare("SELECT owner_id FROM Restaurants WHERE idRestaurants = ?");
            $stmt->execute([$restaurantId]);
            $restaurant = $stmt->fetch();

            if (!$restaurant || $restaurant['owner_id'] != $userId) {
                $_SESSION['error_message'] = "Vous ne pouvez pas supprimer ce restaurant.";
                header("Location: ?page=owner-home");
                exit;
            }

            // Suppression du restaurant
            if ($restaurantModel->deleteRestaurantById($restaurantId)) {
                $_SESSION['success_message'] = "Restaurant supprimé avec succès.";
            } else {
                $_SESSION['error_message'] = "Erreur lors de la suppression du restaurant.";
            }

            header("Location: ?page=owner-home");
            exit;
        }

        header("Location: ?page=owner-home");
        exit;
    }
}
