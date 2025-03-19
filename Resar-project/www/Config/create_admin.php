<?php

require './DbConnect.php';
use App\Config\DbConnect;

try {
    $conn = DbConnect::getPDO();

    // Informations de l'administrateur
    $firstName = 'Admin';
    $lastName = 'User';
    $email = 'admin@example.com';
    $phone = '0000000000';
    $password = 'AdminPassword123';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertion de l'utilisateur
    $stmt = $conn->prepare("
        INSERT INTO users (firstName, lastName, email, phone, password)
        VALUES (:firstName, :lastName, :email, :phone, :password)
    ");
    $stmt->bindValue(':firstName', $firstName);
    $stmt->bindValue(':lastName', $lastName);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':phone', $phone);
    $stmt->bindValue(':password', $hashedPassword);
    $stmt->execute();

    // Récupération de l'ID du nouvel utilisateur
    $userId = $conn->lastInsertId();

    // Attribution du rôle 'admin' : ici, (idRole = 1)
    $stmt = $conn->prepare("
        INSERT INTO user_roles (user_id, role_id)
        VALUES (:user_id, 1)
    ");
    $stmt->bindValue(':user_id', $userId);
    $stmt->execute();

    echo "Administrateur créé avec succès.";
} catch (\PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Fermer la connexion
DbConnect::closeConnection();
