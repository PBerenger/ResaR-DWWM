<?php

namespace App\Controllers\Restaurants;

use App\Config\DbConnect;
use App\Models\Restaurant;
use App\Models\User;
use Exception;

class UpdateRestaurant
{
    private $restaurantModel;

    public function __construct()
    {
        $this->restaurantModel = new Restaurant(DbConnect::getPDO());
    }

    public function execute(array $postdata, $restaurantId = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérification de l'ID (transmis par le routeur)
        if ($restaurantId === null || !is_numeric($restaurantId)) {
            $_SESSION['error_message'] = "Aucun restaurant valide sélectionné.";
            header("Location: ?page=error");
            exit;
        }

        $pdo = DbConnect::getPDO();
        $restaurantModel = new Restaurant($pdo);
        $restaurant = $restaurantModel->getRestaurantFindById($restaurantId);

        if (!$restaurant) {
            $_SESSION['error_message'] = "Restaurant non trouvé.";
            header("Location: ?page=error");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $name = htmlspecialchars(trim($postdata['name']));
                $phone = htmlspecialchars(trim($postdata['phone']));
                $description = htmlspecialchars(trim($postdata['description']));
                $address = htmlspecialchars(trim($postdata['address']));
                $city = htmlspecialchars(trim($postdata['city']));
                $zip_code = htmlspecialchars(trim($postdata['zip_code']));
                $country = htmlspecialchars(trim($postdata['country']));

                $ownerId = $_SESSION['user_id'] ?? $restaurant->getOwnerId();

                // Gestion de la photo
                if (!$restaurantId) {
                    $message = "Utilisateur non connecté.";
                } else {
                    $photoPath = $restaurant->uploadPhoto($restaurantId, $_FILES["restaurant_pic"]);
                    if (!empty($_FILES["restaurant_pic"]["name"])) {
                        $photoPath = $this->restaurantModel->uploadPhoto($restaurantId, $_FILES["restaurant_pic"]);
                    }
                }
                // Mise à jour en base de données
                $restaurantModel->updateRestaurant($restaurantId, $ownerId, $name, $phone, $description, $address, $city, $zip_code, $country, null, null, $photoPath);
                echo "huhu";

                $_SESSION['success_message'] = "Modification réussie !";
                header("Location: ?page=owner-home");
                exit;
            } catch (Exception $e) {
                error_log("Erreur SQL : " . $e->getMessage());
                die("Erreur détectée : " . $e->getMessage());
                $_SESSION['error_message'] = "Une erreur est survenue. Impossible de modifier les informations du restaurant.";
                header("Location: ?page=error");
                exit;
            }
        }

        // Affichage de la vue
        require '../App/Views/Restaurants/updateRestaurant_view.php';
    }
}
