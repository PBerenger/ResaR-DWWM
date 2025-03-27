<?php

namespace App\Controllers\Owner;

use App\Config\DbConnect;
use App\Models\User;
use Exception;

class UpdateOwner
{
    public function execute(array $postdata)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errorMessage = '';
        $pdo = DbConnect::getPDO();

        // Vérifie si un ID est passé dans l'URL pour la modification d'un utilisateur spécifique
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $userId = $_GET['id'];
        } elseif (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        } else {
            $_SESSION['error_message'] = "Aucun utilisateur spécifié.";
            header("Location: ?page=error");
            exit;
        }

        // Charger l'utilisateur
        $user = (new User($pdo))->findUserById($userId);

        if (!$user) {
            $_SESSION['error_message'] = "Utilisateur non trouvé.";
            header("Location: ?page=error");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
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
                    if ($_SESSION['role'] === 'admin' && isset($role)) {
                        $stmt = $pdo->prepare("UPDATE users SET firstName = ?, lastName = ?, phone = ?, roles = ? WHERE idUsers = ?");
                        $stmt->execute([$firstName, $lastName, $phone, $role, $userId]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE users SET firstName = ?, lastName = ?, phone = ? WHERE idUsers = ?");
                        $stmt->execute([$firstName, $lastName, $phone, $userId]);
                    }

                    $_SESSION['success_message'] = "Mise à jour réussie !";

                    // Redirection selon le rôle
                    if ($_SESSION['role'] === 'admin') {
                        header("Location: ?page=admin-home");
                    } elseif ($_SESSION['role'] === 'owner') {
                        header("Location: ?page=owner-home");
                    } else {
                        header("Location: ?page=profil-user");
                    }
                    
                    exit;
                }
            } catch (Exception $e) {
                error_log("Erreur SQL : " . $e->getMessage());
                $errorMessage = "Une erreur interne s'est produite. Veuillez réessayer.";
            }
        }

        if (!empty($errorMessage)) {
            $_SESSION['error_message'] = $errorMessage;
            header("Location: ?page=error");
            exit;
        }

        require '../App/Views/Profils/updateUser_view.php';
    }
}
