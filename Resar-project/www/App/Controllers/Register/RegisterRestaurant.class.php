<?php

namespace App\Controllers\Register;

use App\Config\DbConnect;
use \PDOException;

class RegisterRestaurant
{

    public function execute(array $postdata, array $files)
    {
        $validationError = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération et validation des données utilisateur
            $prenom = trim($postdata['prenom']);
            $nom = trim($postdata['nom']);
            $email = trim($postdata['email']);
            $telephone = trim($postdata['telephone']);
            $password = $postdata['password'];
            $passwordRepeat = $postdata['passwordRepeat'];
            
            // Récupération et validation des données restaurant
            $nomRestaurant = trim($postdata['nomRestaurant']);
            $adresse = trim($postdata['address']);
            $ville = trim($postdata['city']);
            $codePostal = trim($postdata['zip_Code']);
            $pays = trim($postdata['country']);
            $description = trim($postdata['description']);
            
            // Vérification des champs obligatoires
            if (
                empty($prenom) || empty($nom) || empty($email) || empty($telephone) || empty($password) || empty($passwordRepeat) ||
                empty($nomRestaurant) || empty($adresse) || empty($ville) || empty($codePostal) || empty($pays) || empty($description)
                ) {
                    $validationError = "Tous les champs doivent être remplis.";
                    echo "ici tout va bien";
                }
                // Vérification de l'email
                elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $validationError = "L'email n'est pas valide.";
                }
                // Vérification du mot de passe
                elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[$@!%*#?&])[A-Za-z\d$@!%*#?&]{8,}$/", $password)) {
                    $validationError = "Le mot de passe doit contenir au moins :
                    - 8 caractères
                    - Une majuscule
                    - Une minuscule
                    - Un chiffre
                    - Un caractère spécial parmi $@!%*#?&.";
                }
            // Vérification de la correspondance des mots de passe
            elseif ($password !== $passwordRepeat) {
                $validationError = "Les mots de passe ne correspondent pas.";
            }

            if (!empty($validationError)) {
                $_SESSION['error_message'] = $validationError;
                // header("Location: ?page=error");
                exit;
            }
            // Connexion à la base de données
            $pdo = DbConnect::getPDO();
            echo "erreur ici";
            try {
                $pdo->beginTransaction();


                // Vérifier si l'email existe déjà
                $stmt = $pdo->prepare("SELECT idUsers FROM users WHERE email = ?");
                $stmt->execute([$email]);

                if ($stmt->fetch()) {
                    $pdo->rollBack();
                    $_SESSION['error_message'] = "L'email est déjà utilisé.";
                    // header("Location: ?page=error");
                    exit;
                }

                // Inscription de l'utilisateur
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, email, password, phone) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$prenom, $nom, $email, $hashedPassword, $telephone]);

                // Récupérer l'ID utilisateur nouvellement créé
                $userId = $pdo->lastInsertId();

                // Inscription du restaurant
                $stmt = $pdo->prepare("INSERT INTO restaurants (name, address, city, zip_code, country, description, owner_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nomRestaurant, $adresse, $ville, $codePostal, $pays, $description, $userId]);

                // Récupérer l'ID restaurant nouvellement créé
                $restaurantId = $pdo->lastInsertId();

                // // Gérer l'upload des images
                // $photoProfilPath = $this->uploadImage($files['photoProfil'], "uploads/profils/", 150, 150);
                // $photoRestaurantPath = $this->uploadImage($files['photoRestaurant'], "uploads/restaurants/", 2048, 1200);

                // if ($photoProfilPath !== null) {
                //     // Ajouter l'image à la table photos
                //     $stmt = $pdo->prepare("INSERT INTO photos (photo_path) VALUES (?)");
                //     $stmt->execute([$photoProfilPath]);
                //     $photoId = $pdo->lastInsertId();

                //     // Lier la photo à l'utilisateur
                //     $stmt = $pdo->prepare("INSERT INTO user_photos (user_id, photo_id) VALUES (?, ?)");
                //     $stmt->execute([$userId, $photoId]);
                // }

                // if ($photoRestaurantPath !== null) {
                //     // Ajouter l'image à la table photos
                //     $stmt = $pdo->prepare("INSERT INTO photos (photo_path) VALUES (?)");
                //     $stmt->execute([$photoRestaurantPath]);
                //     $photoId = $pdo->lastInsertId();

                //     // Lier la photo au restaurant
                //     $stmt = $pdo->prepare("INSERT INTO restaurant_photos (restaurant_id, photo_id) VALUES (?, ?)");
                //     $stmt->execute([$restaurantId, $photoId]);
                // }

                // // Valider la transaction
                // $pdo->commit();

                $_SESSION['success_message'] = "Inscription réussie !";
                // header("Location: ?page=success");
                exit;
            } catch (PDOException $e) {
                $pdo->rollBack();
                error_log("Erreur SQL : " . $e->getMessage());
                $_SESSION['error_message'] = "Une erreur est survenue. Veuillez réessayer plus tard.";
                // header("Location: ?page=error");
                exit;
            }
        }

        if (!empty($validationError)) {
            $_SESSION['error_message'] = $validationError;
            // header("Location: ?page=error");
            exit;
        }
    }

    // private function uploadImage($file, $directory, $minWidth, $minHeight)
    // {
    //     if ($file['error'] !== UPLOAD_ERR_OK) {
    //         return null;
    //     }

    //     // Vérification du type de fichier
    //     $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    //     if (!in_array($file['type'], $allowedTypes)) {
    //         return null;
    //     }

    //     // Vérification des dimensions
    //     list($width, $height) = getimagesize($file['tmp_name']);
    //     if ($width < $minWidth || $height < $minHeight) {
    //         return null;
    //     }

    //     // Vérification de la taille du fichier
    //     if ($file['size'] > 5 * 1024 * 1024) { // Limite de 5 Mo
    //         return null;
    //     }

    //     // Création du répertoire s'il n'existe pas
    //     if (!is_dir($directory)) {
    //         mkdir($directory, 0777, true);
    //     }

    //     // Déplacement du fichier
    //     $fileName = uniqid() . "_" . basename($file['name']);
    //     $filePath = $directory . $fileName;
    //     move_uploaded_file($file['tmp_name'], $filePath);

    //     return $filePath;
    // }
}
