<?php

namespace App\Controllers\Register;

use App\Config\DbConnect;
use \PDOException;

class RegisterUser
{
    public function execute(array $postdata)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }        
        $errorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prenom = htmlspecialchars(trim($postdata['prenom']));
            $nom = htmlspecialchars(trim($postdata['nom']));
            $email = htmlspecialchars(trim($postdata['email']));
            $password = $postdata['password'];
            $passwordRepeat = $postdata['passwordRepeat'];

            if (empty($prenom) || empty($nom) || empty($email) || empty($password) || empty($passwordRepeat)) {
                $errorMessage = "Tous les champs doivent être remplis.";
            } 
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "L'adresse email n'est pas valide.";
            }
            elseif (strlen($prenom) > 50 || strlen($nom) > 50) {
                $errorMessage = "Le prénom et le nom ne doivent pas dépasser 50 caractères.";
            }
            elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[$@!%*#?&])[A-Za-z\d$@!%*#?&]{8,}$/", $password)) {
                $errorMessage = "Le mot de passe doit contenir au moins :
                                    - 8 caractères
                                    - Une majuscule
                                    - Une minuscule
                                    - Un chiffre
                                    - Un caractère spécial parmi $@!%*#?&.";
            }
            elseif ($password !== $passwordRepeat) {
                $errorMessage = "Les mots de passe ne correspondent pas.";
            } else {
                try {
                    $pdo = DbConnect::getPDO();
                    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                    $stmt = $pdo->prepare("SELECT idUsers FROM users WHERE email = ?");
                    $stmt->execute([$email]);

                    if ($stmt->fetch()) {
                        $errorMessage = "L'email est déjà utilisé.";
                    } else {
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                        
                        $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$prenom, $nom, $email, $hashedPassword]);
                        
                        $userId = $pdo->lastInsertId();

                        $stmtRole = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                        $stmtRole->execute([$userId, 2]);

                        $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                        header("Location: ?page=success");
                        exit;
                    }
                } catch (PDOException $e) {
                    error_log("Erreur SQL : " . $e->getMessage());
                    $_SESSION['error_message'] = "Une erreur interne s'est produite. Veuillez réessayer.";
                    header("Location: ?page=error");
                    exit;
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
