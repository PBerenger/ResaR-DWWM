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
    private string $createdAt;


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
        return $this->phone;
    }
    public function getPhoto(): string
    {
        return $this->photo ?? 'u_default.jpg';
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
        return $this->createdAt;
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
        $this->role = is_array($role) ? $role : explode(",", $role);
    }
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    //----------------------------------------------------------------
    // METHODES
    public function findUserById(?int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT u.*, 
                                GROUP_CONCAT(r.roleName) AS roles 
                                FROM users u 
                                LEFT JOIN user_roles ur ON u.idUsers = ur.user_id 
                                LEFT JOIN roles r ON ur.role_id = r.idRole
                                WHERE u.idUsers = ?
                                GROUP BY u.idUsers");
        $stmt->execute([$id]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            $user["roles"] = $user["roles"] ?? "";
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

    private function commonUser(array $userData): User
    {
        $newUser = new User($this->pdo);
        $newUser->idUsers = $userData["idUsers"];
        $newUser->firstName = $userData["firstName"];
        $newUser->lastName = $userData["lastName"];
        $newUser->email = $userData["email"];
        $newUser->phone = $userData["phone"] ?? "";
        $newUser->password = $userData["password"];
        $newUser->createdAt = $userData["created_at"] ?: "";
        $newUser->setRole($userData["roles"] ?? "");

        return $newUser;
    }



    public function checkPass(string $passToCheck): bool
    {
        if (empty($this->password)) {
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
            $stmt->execute([$userId, $roleId]);

            $pdo->commit();

            return (new User($pdo))->findUserById($userId);
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log('Erreur lors de la création de l\'utilisateur: ' . $e->getMessage());
            return null;
        }
    }


    public static function getAllUsers(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT 
                                u.idUsers AS id,
                                u.email,
                                u.firstName,
                                u.lastName,
                                u.password,
                                u.created_at AS createdAt,
                                GROUP_CONCAT(r.roleName) AS roles
                            FROM users u
                            LEFT JOIN user_roles ur ON u.idUsers = ur.user_id
                            LEFT JOIN roles r ON ur.role_id = r.idRole
                            GROUP BY u.idUsers");
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map([self::class, 'commonUser'], $results);
    }

    // ----------------------------------------------------------------
    // GESTION DES PHOTOS

    public function uploadPhoto(array $file): bool
    {
        // Vérification de la présence du fichier
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Extensions et types MIME autorisés
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        // Récupération des infos sur le fichier
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $fileInfo->file($file['tmp_name']);
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($mimeType, $allowedTypes) || !in_array($extension, $allowedExtensions)) {
            return false;
        }

        // Nouveau nom de fichier unique
        $newFileName = uniqid('user_' . $this->idUsers . '_') . '.' . $extension;

        // Dossier de destination
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . $newFileName;

        // Déplacement du fichier
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            return false;
        }

        // Mise à jour du chemin en base de données
        $stmt = $this->pdo->prepare("UPDATE users SET photo = ? WHERE idUsers = ?");
        if ($stmt->execute([$newFileName, $this->idUsers])) {
            $this->photo = $newFileName;
            return true;
        }

        return false;
    }
}
