<?php

namespace App\Models;

use PDO;

class User
{
    private int $idUsers;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $phone;
    private string $password;
    private string $inscriptionDate;
    private array|string $role;
    private string $lastConnection;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    // GETTERS

    public function getId(): int
    {
        return $this->idUsers;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPhonel(): string
    {
        return $this->phone;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getInscriptionDate(): string
    {
        return $this->inscriptionDate;
    }
    public function getRole(): int
    {
        return $this->role;
    }
    public function getLastConnection(): string
    {
        return $this->lastConnection;
    }

    // SETTERS

    public function setId(): int
    {
        return $this->idUsers;
    }
    public function setfirstName(): string
    {
        return $this->firstName;
    }
    public function setlastName(): string
    {
        return $this->lastName;
    }
    public function setEmail(): string
    {
        return $this->email;
    }
    public function setPhonel(): string
    {
        return $this->phone;
    }
    public function setPassword(): string
    {
        return $this->password;
    }
    public function setInscriptionDate(): string
    {
        return $this->inscriptionDate;
    }
    public function setRole(): int
    {
        return $this->role;
    }
    public function setLastConnection(): string
    {
        return $this->lastConnection;
    }

    //----------------------------------------------------------------


    public function findUserById(?int $id): bool
    {
        if (!$id) {
            return false;
        }

        $stmt = $this->pdo->prepare("SELECT u.*, GROUP_CONCAT(r.role_name) AS roles 
                                    FROM users u 
                                    LEFT JOIN user_roles ur ON u.idUsers = ur.user_id 
                                    LEFT JOIN roles r ON ur.role_id = r.id
                                    WHERE u.idUsers = ?
                                    ");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->idUsers = $user["idUsers"];
            $this->firstName = $user["firstName"];
            $this->lastName = $user["lastName"];
            $this->email = $user["email"];
            $this->phone = $user["phone"];
            $this->password = $user["password"];
            $this->inscriptionDate = $user["created_at"];
            $this->lastConnection = $user["lastConnection"] ?? null;
            $this->role = $user["roles"] ? explode(",", $user["roles"]) : [];
        }

        return $user !== false;
    }

    public function findUserByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT u.*, GROUP_CONCAT(r.role_name) AS roles 
                                    FROM users u 
                                    LEFT JOIN user_roles ur ON u.idUsers = ur.user_id 
                                    LEFT JOIN roles r ON ur.role_id = r.id
                                    WHERE u.email = ?
                                    ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->idUsers = $user["idUsers"];
            $this->firstName = $user["firstName"];
            $this->lastName = $user["lastName"];
            $this->email = $user["email"];
            $this->phone = $user["phone"];
            $this->password = $user["password"];
            $this->inscriptionDate = $user["created_at"];
            $this->lastConnection = $user["lastConnection"] ?? null;
            $this->role = $user["roles"] ? explode(",", $user["roles"]) : [];
            return $this;
        }

        return $user !== false;
    }


    public function checkPass($passToCheck): bool
    {
        return !empty($this->password) && password_verify($passToCheck, $this->password);
    }

    public function setSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION["USER_ID"] = $this->idUsers;

        // update the lastseen date in the database
        $stmt = $this->pdo->prepare("UPDATE users SET lastConnection = NOW() WHERE idUsers = ?");
        $stmt->execute([$this->idUsers]);
    }

    public function isAdmin(): bool
    {
        return is_array($this->role) ? in_array("admin", $this->role) : $this->role === 1;
    }

    public function checkAdmin(): void
    {
        if (!$this->isAdmin()) {
            header("HTTP/1.1 403 Forbidden");
            echo "Accès refusé.";
            exit;
        }
    }

    public static function checkAdminNew(PDO $pdo, ?int $id): ?User
    {
        $user = new User($pdo);
        $user->findUserById($id);

        if (!$user->isAdmin()) {
            header("Location: /");
            exit;
        }


        return $user;
    }

    public static function create(PDO $pdo, string $firstName, string $lastName, string $email, string $phone, string $password, int $roleId = 2): bool
    {
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("INSERT INTO users (
                                firstName, 
                                lastName, 
                                email, 
                                phone, 
                                password, 
                                created_at) 
                                VALUES (
                                :firstName, 
                                :lastName, 
                                :email, 
                                :phone, 
                                :password, 
                                NOW())");

            $success = $stmt->execute([
                "firstName" => $firstName,
                "lastName" => $lastName,
                "email" => $email,
                "phone" => $phone,
                "password" => password_hash($password, PASSWORD_DEFAULT)
            ]);

            if (!$success) {
                throw new \Exception("L'insertion de l'utilisateur a échoué.");
            }

            $userId = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO user_roles (
                                user_id, 
                                role_id) 
                                VALUES (
                                ?, 
                                ?)");

            $stmt->execute([$userId, $roleId]);

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public static function getAllUsers(PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT u.*, GROUP_CONCAT(r.role_name) AS roles 
                            FROM users u 
                            LEFT JOIN user_roles ur ON u.idUsers = ur.user_id 
                            LEFT JOIN roles r ON ur.role_id = r.id
                            GROUP BY u.idUsers
                            ");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];

        foreach ($results as $user) {
            $newUser = new User($pdo);
            $newUser->idUsers = $user["idUsers"];
            $newUser->firstName = $user["firstName"];
            $newUser->lastName = $user["lastName"];
            $newUser->email = $user["email"];
            $newUser->phone = $user["phone"];
            $newUser->password = $user["password"];
            $newUser->inscriptionDate = $user["created_at"];
            $newUser->lastConnection = $user["lastConnection"] ?? null;
            $newUser->role = $user["roles"] ? explode(",", $user["roles"]) : [];
            $users[] = $newUser;
        }

        return $users;
    }
}
