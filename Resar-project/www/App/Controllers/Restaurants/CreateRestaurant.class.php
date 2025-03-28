<?php

namespace App\Controllers\Restaurants;

use App\Config\DbConnect;
use PDO;
use PDOException;

class CreateRestaurant
{
    public function execute(array $postdata, int $userId)
    {
        session_start(); // Assure que la session est démarrée si on stocke des erreurs
        $validationError = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Connexion à la base de données
                $pdo = DbConnect::getPDO();

                // Vérification et nettoyage des données
                $nomRestaurant = trim($postdata['nomRestaurant'] ?? '');
                $address = trim($postdata['address'] ?? '');
                $city = trim($postdata['city'] ?? '');
                $zipCode = trim($postdata['zip_Code'] ?? '');
                $country = trim($postdata['country'] ?? '');
                $telephone = trim($postdata['telephone'] ?? '');
                $description = trim($postdata['description'] ?? '');

                // Vérification des champs obligatoires
                if (empty($nomRestaurant) || empty($address) || empty($city) || empty($zipCode) || empty($country) || empty($telephone) || empty($description)) {
                    $_SESSION['error_message'] = "Tous les champs doivent être remplis.";
                    header("Location: ?page=error");
                    exit;
                }

                // Préparation et exécution de l'insertion
                $stmtRestaurant = $pdo->prepare("
                    INSERT INTO restaurants (owner_id, name, phone, description, address, city, zip_code, country) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $stmtRestaurant->execute([$userId, $nomRestaurant, $telephone, $description, $address, $city, $zipCode, $country]);

                // Récupération de l'ID du restaurant inséré
                $restaurantId = $pdo->lastInsertId();

                // Redirection après insertion réussie
                header("Location: ?page=success&id=" . $restaurantId);
                exit;

            } catch (PDOException $e) {
                $_SESSION['error_message'] = "Erreur SQL : " . $e->getMessage();
                header("Location: ?page=error");
                exit;
            }
        }
    }
}
