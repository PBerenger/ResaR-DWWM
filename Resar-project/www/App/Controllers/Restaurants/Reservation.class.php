<?php

namespace App\Controllers\Restaurants;

use App\Models\Restaurant;
use App\Models\User;
use App\Config\DbConnect;

class Reservation
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DbConnect::getPDO();
    }

    public function execute(int $restaurantId)
    {
        // Vérification si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            // Redirection si l'utilisateur n'est pas connecté
            header('Location: /login');
            exit();
        }

        // Récupérer les informations du restaurant
        $restaurant = (new Restaurant($this->pdo))->getRestaurantFindById($restaurantId);
        if (!$restaurant) {
            // Redirection en cas de restaurant non trouvé
            header('Location: /restaurants');
            exit();
        }

        // Récupérer les créneaux horaires disponibles
        $availableSlots = $this->getAvailableSlots($restaurantId);

        // Récupérer la date sélectionnée
        $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve'])) {
            // Traitement de la réservation
            $this->reserveTable($restaurantId, $_POST['time'], $_POST['date'], $_POST['userId']);
        }

        // Affichage de la vue avec les données
        require __DIR__ . '/../views/restaurantReservation_view.php';
    }

    // Récupérer les créneaux horaires disponibles pour un restaurant
    private function getAvailableSlots(int $restaurantId): array
    {
        // Pour l'exemple, nous renverrons un tableau statique. 
        // Ici, vous pouvez l'adapter pour qu'il soit dynamique (par exemple, en fonction des horaires d'ouverture du restaurant).
        return [
            '12:00',
            '12:30',
            '13:00',
            '13:30',
            '14:00',
            '14:30',
            '19:00',
            '19:30',
            '20:00',
            '20:30',
        ];
    }

    // Effectuer la réservation
    private function reserveTable(int $restaurantId, string $time, string $date, int $userId)
    {
        // Préparer l'insertion de la réservation dans la base de données
        $stmt = $this->pdo->prepare(
            "INSERT INTO reservations (restaurant_id, user_id, date, time) 
             VALUES (:restaurant_id, :user_id, :date, :time)"
        );
        $stmt->bindValue(':restaurant_id', $restaurantId, \PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':date', $date, \PDO::PARAM_STR);
        $stmt->bindValue(':time', $time, \PDO::PARAM_STR);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Rediriger après une réservation réussie
            header('Location: /reservation-success');
            exit();
        } else {
            // Gérer l'échec de la réservation
            echo "Une erreur est survenue lors de la réservation.";
        }
    }
}
