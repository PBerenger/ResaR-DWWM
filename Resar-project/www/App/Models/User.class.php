<?php

namespace App\Models;

use App\Config\DbConnect;

class User
{
    private \PDO $pdo;

    private int $idUsers;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $phone;
    private string $password;
    private string $photo;
    private array $role = [];
    private string $createdAt = '';


    public function __construct(?\PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DbConnect::getPDO();
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
    public function getPhone(): string
    {
        return $this->phone ?? 'Numéro introuvable';
    }
    public function getPhoto(): string
    {
        return !empty($this->photo) ? $this->photo : 'u_default.jpg';
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getRole(): array
    {
        if (is_array($this->role)) {
            return $this->role;
        }
        return $this->role ? explode(',', $this->role) : ['Rôle inconnu'];
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt ?? '';
    }


    // SETTERS

    public function setId(int $id): void
    {
        $this->idUsers = $id;
    }
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }
    public function setPassword(string $password): void
    {
        if (!password_get_info($password)['algo']) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $this->password = $password;
        }
    }
    public function setRole(array|string $role): void
    {
        $allowedRoles = ['client', 'admin', 'owner'];
        $roles = is_array($role) ? $role : [$role];

        foreach ($roles as $r) {
            if (!in_array($r, $allowedRoles)) {
                throw new \InvalidArgumentException("Rôle invalide : $r");
            }
        }

        $this->role = $roles;
    }
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    //----------------------------------------------------------------
    // METHODES

    private function commonUser(array $userData): User
    {
        $newUser = new User($this->pdo);
        $newUser->idUsers = $userData["idUsers"];
        $newUser->firstName = $userData["firstName"];
        $newUser->lastName = $userData["lastName"];
        $newUser->email = $userData["email"];
        $newUser->phone = $userData["phone"] ?? "";
        $newUser->password = $userData["password"];
        $newUser->photo = $userData["photo"] ?? 'u_default.jpg';
        $newUser->createdAt = $userData["created_at"] ?? "";
        $newUser->setRole($userData["roles"] ?? "");

        return $newUser;
    }

    public function findUserById(?int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT u.*, 
                                   GROUP_CONCAT(r.roleName) AS roles,
                                   p.photo_path AS photo
                            FROM users u
                            LEFT JOIN user_roles ur ON u.idUsers = ur.user_id 
                            LEFT JOIN roles r ON ur.role_id = r.idRole
                            LEFT JOIN user_photos up ON u.idUsers = up.user_id
                            LEFT JOIN photos p ON up.photo_id = p.idPhoto
                            WHERE u.idUsers = ?
                            GROUP BY u.idUsers, p.photo_path
                            ");

        $stmt->execute([$id]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            return $this->commonUser($user);
        }

        return null;
    }

    public function findUserByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT u.*, GROUP_CONCAT(r.roleName) AS roles 
                                FROM users u 
                                LEFT JOIN user_roles ur ON u.idUsers = ur.user_id 
                                LEFT JOIN roles r ON ur.role_id = r.idRole
                                WHERE u.email = ?
                                GROUP BY u.idUsers");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            $user["roles"] = $user["roles"] ?? "";
            return $this->commonUser($user);
        }
        return null;
    }

    public function loadRoles(): void
    {
        $stmt = $this->pdo->prepare("
        SELECT r.roleName 
        FROM roles r
        JOIN user_roles ur ON r.idRole = ur.role_id
        WHERE ur.user_id = ?");

        $stmt->execute([$this->idUsers]);

        $roles = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        $this->role = $roles ?: [];
    }

    public function deleteUserById(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE idUsers = ?");

        if (!$stmt->execute([$id])) {
            error_log("Erreur SQL [deleteUserById] : " . implode(" | ", $stmt->errorInfo()));
            return false;
        }

        return true;
    }

    public function checkPass(string $passToCheck): bool
    {
        if (empty($this->password) || strlen($this->password) < 60) {
            return false;
        }
        return password_verify($passToCheck, $this->password);
    }


    public function isAdmin(): bool
    {
        $roles = $this->getRole();
        return in_array("admin", $roles) || in_array("1", $roles);
    }

    public function checkAdmin(): void
    {
        if (!$this->isAdmin()) {
            throw new \Exception("403 Forbidden: Accès refusé.");
        }
    }

    public static function checkAdminNew(\PDO $pdo, ?int $id): ?User
    {
        $user = (new User($pdo))->findUserById($id);
        if (!$user || !$user->isAdmin()) {
            throw new \Exception("403 Forbidden: Accès refusé.");
        }
        return $user;
    }

    public static function create(
        \PDO $pdo,
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        string $password,
        int $roleId = 2
    ): ?User {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetchColumn() > 0) {
            return null;
        }

        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("INSERT INTO users (
                                firstName, 
                                lastName, 
                                email, 
                                phone, 
                                password, 
                                created_at) 
                            VALUES (:firstName, 
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

            $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            $email = strtolower($email);
            $stmt->execute([$userId, $roleId]);

            $pdo->commit();

            return (new User($pdo))->findUserById($userId);
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log('Erreur lors de la création de l\'utilisateur: ' . $e->getMessage());
            return null;
        }
    }

    public function getAllUsers(string $order = 'ASC'): array
    {
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $stmt = $this->pdo->prepare("SELECT 
                                    u.idUsers,
                                    u.*, 
                                    COALESCE(p.photo_path, 'uploads/users/u_default.jpg') AS photo,
                                    u.firstName, 
                                    u.lastName, 
                                    u.email, 
                                    u.phone, 
                                    u.created_at,
                                    GROUP_CONCAT(DISTINCT r.roleName) AS roles,
                                    COUNT(DISTINCT res.idReservations) AS totalReservations,
                                    COUNT(DISTINCT rest.idRestaurants) AS totalOwnedRestaurants
                                FROM users u
                                LEFT JOIN user_roles ur ON u.idUsers = ur.user_id
                                LEFT JOIN roles r ON ur.role_id = r.idRole
                                LEFT JOIN reservations res ON u.idUsers = res.user_id
                                LEFT JOIN restaurants rest ON u.idUsers = rest.owner_id
                                LEFT JOIN user_photos up ON u.idUsers = up.user_id
                                LEFT JOIN photos p ON up.photo_id = p.idPhoto
                                GROUP BY u.idUsers
                                ORDER BY u.idUsers $order");

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    // ----------------------------------------------------------------
    // GESTION DES PHOTOS

    public function uploadPhoto($userId, $file)
    {
        if (!isset($file['name']) || empty($file['name'])) {
            return "Aucune image sélectionnée.";
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return "Format d'image non autorisé.";
        }

        $maxSize = 2 * 1024 * 1024; // 2 Mo
        if ($file['size'] > $maxSize) {
            return "L'image dépasse la taille maximale autorisée (2 Mo).";
        }

        $uploadDir = 'uploads/profile_pics/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = "user_{$userId}." . $fileExt;
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Insère dans la BDD
            $stmt = $this->pdo->prepare("INSERT INTO photos (photo_path) VALUES (:photo_path)");
            $stmt->bindValue(':photo_path', $filePath, \PDO::PARAM_STR);
            $stmt->execute();

            $photoId = $this->pdo->lastInsertId();

            // Lier la photo à l'utilisateur
            $stmt = $this->pdo->prepare("INSERT INTO user_photos (user_id, photo_id) VALUES (:user_id, :photo_id)");
            $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':photo_id', $photoId, \PDO::PARAM_INT);
            $stmt->execute();

            return "Photo mise à jour avec succès.";
        } else {
            return "Erreur lors du téléchargement de l'image.";
        }
    }
}
