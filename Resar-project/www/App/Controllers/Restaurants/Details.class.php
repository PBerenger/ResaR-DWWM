<?php

namespace App\Controllers\Restaurants;

use App\Config\DbConnect;
use App\Models\Restaurant;
use Exception;

class Details
{
    private string $photo;

    public function execute(int $id)
    {
        $pdo = DbConnect::getPDO();
        $restaurantModel = new Restaurant();
        $restaurant = $restaurantModel->getRestaurantFindById($id);

        if (!$restaurant) {
            header("HTTP/1.1 404 Not Found");
            include __DIR__ . "/../../Views/errors/404.php"; // Page d'erreur dédiée
            exit;
        }
        
        $this->photo = $restaurant->getPhoto() ?? 'd_default.jpg';

        try {
            $dishes = $this->getDishes($id);
        } catch (Exception $e) {
            error_log("Erreur récupération des plats : " . $e->getMessage());
            $dishes = [];
        }

        require __DIR__ . "/../../Views/Restaurants/restaurant-details_view.php";
    }

    public function getDishes(int $id): array
    {
        $pdo = DbConnect::getPDO();
        $query = "SELECT idDishes, name, description, price FROM dishes WHERE restaurant_id = ?";
        $stmt = $pdo->prepare($query);

        if (!$stmt->execute([$id])) {
            throw new Exception('Erreur lors de la récupération des plats.');
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPhoto(): string
    {
        return $this->photo ?? 'd_default.jpg';
    }
}
