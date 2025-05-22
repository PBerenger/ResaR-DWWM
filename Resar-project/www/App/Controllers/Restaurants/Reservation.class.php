<?php

namespace App\Controllers\Restaurants;

use App\Models\Restaurant;
use App\Models\ReservationModel;
use Exception;

class Reservation
{
    public function execute(int $restaurantId): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $errorMessage = '';

        try {
            $restaurantModel = new Restaurant();
            $restaurant = $restaurantModel->getRestaurantFindById($restaurantId);

            if (!$restaurant) {
                $_SESSION['error_message'] = "Restaurant non trouvé.";
                header("Location: index.php?page=restaurants-list");
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reservation'])) {
                $reservation = new ReservationModel();
                $reservation->setUserId($_SESSION['user_id']);
                $reservation->setDate($_POST['date']);
                $reservation->setHeure($_POST['heure']);
                $reservation->setNombrePersonnes((int) $_POST['nombre_personnes']);
                $reservation->setRestaurantId($restaurantId);

                $reservation->save();

                $_SESSION['success_message'] = "Réservation réussie !";
                header("Location: ?page=profil-user");
                exit;
            }

            require __DIR__ . "/../../Views/Restaurants/restaurantReservation_view.php";

        } catch (Exception $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            $_SESSION['error_message'] = "Une erreur interne s'est produite. Veuillez réessayer.";
            header("Location: ?page=error");
            exit;
        }
    }
}
