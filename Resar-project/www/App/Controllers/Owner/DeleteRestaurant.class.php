<?php

namespace App\Controllers\Owner;

use App\Models\Restaurant;
use App\Config\DbConnect;

class DeleteRestaurant
{
    public function execute()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client' || $_SESSION['role'] !== 'owner') {
            header("Location: ?page=owner-home");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["restaurant_id"])) {
            $restaurantId = (int) $_POST["restaurant_id"];

            $pdo = DbConnect::getPDO();
            $restaurantModel = new Restaurant($pdo);

            if ($restaurantModel->deleteRestaurantById($restaurantId)) {
                $_SESSION['success_message'] = "Restaurant supprimé avec succès.";
                header("Location: ?page=home");
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de la suppression du restaurant.";
                header("Location: ?page=owner-home");
                exit;
            }
        }

        header("Location: ?page=ownerHome");
        exit;
    }
}
