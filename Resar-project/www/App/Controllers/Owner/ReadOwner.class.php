<?php

namespace App\Controllers\Owner;

use App\Models\Restaurant;
use App\Config\DbConnect;

class ReadOwner
{
    public function execute()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Vous devez être connecté pour accéder à cette page.";
            header("Location: ?page=login");
            exit();
        }

        $pdo = DbConnect::getPDO();
        $restaurantModel = new Restaurant($pdo);
        $ownerId = $_SESSION['user_id'];
        
        // Récupération des restaurants du propriétaire connecté
        $stmt = $pdo->prepare("SELECT * FROM Restaurants WHERE owner_id = ?");
        $stmt->execute([$ownerId]);
        $restaurants = $stmt->fetchAll();
        
        require __DIR__ . '/../../Views/Owner/ownerHome_view.php';
    }
}
