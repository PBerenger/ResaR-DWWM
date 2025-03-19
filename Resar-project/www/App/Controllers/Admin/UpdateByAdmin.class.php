<?php

namespace App\Controllers\Admin;

use App\Config\DbConnect;
use App\Models\User;
use Exception;

class UpdateByAdmin
{
    public function execute(array $postdata)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errorMessage = '';
        $pdo = DbConnect::getPDO();

        // Vérifie si un ID est passé dans l'URL pour modifier un utilisateur spécifique
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $userId = $_GET['id'];
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
                $email = htmlspecialchars(trim($postdata['email']));
                $phone = htmlspecialchars(trim($postdata['phone']));
                $roles = htmlspecialchars(trim($postdata['roles']));

                // Validation des champs
                if (empty($firstName) || empty($lastName) || empty($phone) || empty($email) || empty($roles)) {
                    $errorMessage = "Tous les champs doivent être remplis.";
                } elseif (strlen($firstName) > 50 || strlen($lastName) > 50) {
                    $errorMessage = "Le prénom et le nom ne doivent pas dépasser 50 caractères.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errorMessage = "L'email n'est pas valide.";
                } elseif (!preg_match('/^\+?[0-9 ]{8,15}$/', $phone)) {
                    $errorMessage = "Le numéro de téléphone n'est pas valide.";
                } else {
                    $allowedRoles = ['user', 'owner', 'admin'];
                    if (!in_array($roles, $allowedRoles)) {
                        $errorMessage = "Rôle invalide sélectionné.";
                    }
                }

                if (empty($errorMessage)) {
                    // Mise à jour des informations utilisateur
                    $stmt = $pdo->prepare("UPDATE users SET firstName = ?, lastName = ?, email = ?, phone = ?, roles = ? WHERE idUsers = ?");
                    $stmt->execute([$firstName, $lastName, $email, $phone, $roles, $userId]);

                    $_SESSION['success_message'] = "L'utilisateur a été mis à jour avec succès !";
                    header("Location: ?page=admin-home");
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

        require '../App/Views/Admin/updateByAdmin_view.php';
    }
}
