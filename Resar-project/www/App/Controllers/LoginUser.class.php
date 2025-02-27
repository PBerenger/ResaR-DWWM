<?php

namespace App\Controllers;

use App\Config\DbConnect;
use \PDOException;

class LoginUser
{
    public function execute(array $postdata)
    {
        $validationError = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($postdata['email']);
            $password = $postdata['password'];

            // Vérification que tous les champs sont remplis
            if (empty($email) || empty($password)) {
                $validationError = "Tous les champs doivent être remplis.";
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
                    // Connexion réussie, démarrer la session et rediriger
                    session_start();

                    if (empty($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Génère un nouveau token CSRF
                    }
                    $_SESSION['user_id'] = $user['idUsers'];
                    $_SESSION['role'] = $user['roleName']; // Stocker le rôle dans la session
                    header("Location: ?page=success"); // Rediriger vers la page d'accueil
                    exit;
                } else {
                    $validationError = "Email ou mot de passe incorrect.";
                }
            }

            if (!empty($validationError)) {
                $_SESSION['error_message'] = $validationError;
                header("Location: ?page=error"); // Rediriger vers la page de connexion avec une erreur
                exit;
            }
        }
    }
}
