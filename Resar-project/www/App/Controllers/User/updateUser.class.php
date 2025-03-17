<?php

namespace App\Controllers\User;

use App\Config\DbConnect;
use App\Models\User;
use Exception;

class UpdateUser
{
    public function execute(array $postdata)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $errorMessage = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            try {
                $pdo = DbConnect::getPDO();
                $user = (new User($pdo))->findUserById($_SESSION['user_id']);

                if ($user) {
                    // Nettoyage des entrées
                    $firstName = htmlspecialchars(trim($postdata['firstName']));
                    $lastName = htmlspecialchars(trim($postdata['lastName']));
                    $phone = htmlspecialchars(trim($postdata['phone']));

                    // Validation des champs
                    if (empty($firstName) || empty($lastName) || empty($phone)) {
                        $errorMessage = "Tous les champs doivent être remplis.";
                    } elseif (strlen($firstName) > 50 || strlen($lastName) > 50) {
                        $errorMessage = "Le prénom et le nom ne doivent pas dépasser 50 caractères.";
                    } elseif (!preg_match('/^\+?[0-9 ]{8,15}$/', $phone)) {
                        $errorMessage = "Le numéro de téléphone n'est pas valide.";
                    } 
                    
                    if (empty($errorMessage)) {
                        // Mise à jour des informations
                        $stmt = $pdo->prepare("UPDATE users SET firstName = ?, lastName = ?, phone = ? WHERE idUsers = ?");
                        $stmt->execute([$firstName, $lastName, $phone, $_SESSION['user_id']]);
                        $_SESSION['phone'] = $phone;


                        $_SESSION['success_message'] = "Mise à jour réussie !";
                        header("Location: ?page=profil-user");
                        exit;
                    }
                } else {
                    $errorMessage = "Utilisateur non trouvé.";
                }
            } catch (Exception $e) {
                error_log("Erreur SQL : " . $e->getMessage());
                $errorMessage = "Une erreur interne s'est produite. Veuillez réessayer.";
            }

            if (!empty($errorMessage)) {
                $_SESSION['error_message'] = $errorMessage;
                header("Location: ?page=error");
                exit;
            }
        } else {
            $pdo = DbConnect::getPDO();
            $user = (new User($pdo))->findUserById($_SESSION['user_id']);

            if (!$user) {
                $_SESSION['error_message'] = "Utilisateur non trouvé.";
                header("Location: ?page=error");
                exit;
            }

            require '../App/Views/Profils/updateUser_view.php';
        }
    }
}
