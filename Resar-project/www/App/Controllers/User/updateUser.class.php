<?php

namespace App\Controllers\User;

use App\Config\DbConnect;
use \PDOException;

class UpdateUser
{
    public function execute(array $postdata)
    {
        $pdo = DbConnect::getPDO();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier les données du formulaire et les mettre à jour
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            // Assure-toi de valider et nettoyer les données ici
            // Mettre à jour l'utilisateur dans la base de données

            // Exemple de mise à jour avec PDO
            $stmt = $pdo->prepare("UPDATE users SET firstName = ?, lastName = ?, email = ? WHERE idUsers = ?");
            $stmt->execute([$prenom, $nom, $email, $_GET['id']]);

            // Rediriger après la mise à jour
            header('Location: index.php?page=profil-user');
            exit;
        }

        // Récupérer l'utilisateur pour pré-remplir le formulaire
        $stmt = $pdo->prepare("SELECT * FROM users WHERE idUsers = ?");
        $stmt->execute([$_GET['id']]);
        $user = $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
