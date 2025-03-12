<?php

namespace App\Controllers;

use App\Config\DbConnect;
use \PDOException;

class LoginUser
{
    public function execute(array $postdata)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }   
        $errorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($postdata['email']);
            $password = $postdata['password'];

            // Vérification que tous les champs sont remplis
            if (empty($email) || empty($password)) {
                $errorMessage = "Tous les champs doivent être remplis.";
            } else {
                $pdo = DbConnect::getPDO();

                // Vérifier si l'email existe et récupérer le rôle via la jointure
                $stmt = $pdo->prepare("
                    SELECT u.idUsers, u.password, r.roleName
                    FROM users u
                    LEFT JOIN user_roles ur ON u.idUsers = ur.user_id
                    LEFT JOIN roles r ON ur.role_id = r.idRole
                    WHERE u.email = ?
                ");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {

                    if (empty($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    }
                    $_SESSION['user_id'] = $user['idUsers'];
                    $_SESSION['role'] = $user['roleName'];
                    $_SESSION['success_message'] = "Connexion réussie ! Bienvenue.";
                    header("Location: ?page=success");
                    exit;
                } else {
                    $errorMessage = "Email ou mot de passe incorrect.";
                }
            }

            if (!empty($errorMessage)) {
                $_SESSION['error_message'] = $errorMessage;
                header("Location: ?page=error");
                exit;
            }
        }
    }
}
