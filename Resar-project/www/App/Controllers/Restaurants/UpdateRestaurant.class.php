<?php

namespace App\Controllers\Restaurants;

use App\Config\DbConnect;
use App\Models\Restaurant;
use Exception;

class UpdateRestaurant
{
    public function execute(array $postdata)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $pdo = DbConnect::getPDO();
        $restaurantModel = new Restaurant($pdo);

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error_message'] = "Aucun restaurant sélectionné.";
            header("Location: ?page=error");
            exit;
        }

        $restaurantId = $_GET['id'];
        $restaurant = $restaurantModel->getRestaurantFindById($restaurantId);

        if (!$restaurant) {
            $_SESSION['error_message'] = "Restaurant non trouvé.";
            header("Location: ?page=error");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_restaurant'])) {
            try {
                $name = htmlspecialchars(trim($postdata['name']));
                $phone = htmlspecialchars(trim($postdata['phone']));
                $description = htmlspecialchars(trim($postdata['description']));
                $address = htmlspecialchars(trim($postdata['address']));
                $city = htmlspecialchars(trim($postdata['city']));
                $zip_code = htmlspecialchars(trim($postdata['zip_code']));
                $country = htmlspecialchars(trim($postdata['country']));

                // Gestion de la photo
                if (!empty($_FILES['restaurant_photo']['name'])) {
                    $targetDir = "uploads/restaurants/";
                    $fileName = time() . "_" . basename($_FILES["restaurant_photo"]["name"]);
                    $targetFilePath = $targetDir . $fileName;
                    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                    $allowTypes = ['jpg', 'png', 'jpeg', 'gif'];
                    if (in_array($fileType, $allowTypes)) {
                        if (move_uploaded_file($_FILES["restaurant_photo"]["tmp_name"], $targetFilePath)) {
                            $photoPath = $targetFilePath;
                        } else {
                            throw new Exception("Erreur lors du téléchargement de l'image.");
                        }
                    } else {
                        throw new Exception("Format d'image non valide. Utilisez JPG, PNG, JPEG ou GIF.");
                    }
                } else {
                    $photoPath = $restaurant->getPhoto();
                }

                $restaurantModel->updateRestaurant($restaurantId, $name, $phone, $description, $address, $city, $zip_code, $country, $photoPath);

                $_SESSION['success_message'] = "Modification réussie !";
                header("Location: ?page=restaurants");
                exit;

            } catch (Exception $e) {
                error_log("Erreur SQL : " . $e->getMessage());
                $_SESSION['error_message'] = "Une erreur est survenue.";
            }
        }

        require '../App/Views/Restaurants/updateRestaurant_view.php';
    }
}
