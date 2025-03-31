<?php

namespace App\Models;

use App\Config\DbConnect;

class Restaurant
{
    private \PDO $pdo;

    private int $idRestaurants;
    private int $owner_id;
    private string $name;
    private ?string $phone;
    private ?string $description;
    private string $address;
    private string $city;
    private string $zip_code;
    private string $country;
    private ?float $latitude;
    private ?float $longitude;
    private ?string $photo;
    private string $created_at;

    public function __construct(?\PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DbConnect::getPDO();
    }

    // GETTERS
    public function getId(): int
    {
        return $this->idRestaurants;
    }

    public function getOwnerId(): int
    {
        return $this->owner_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getZipCode(): string
    {
        return $this->zip_code;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function getPhoto(): ?string
    {
        return $this->photo ?? 'r_default.jpg';
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    // SETTERS
    public function setId(int $id): void
    {
        $this->idRestaurants = $id;
    }

    public function setOwner(int $owner_id): void
    {
        $this->owner_id = $owner_id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function setZipCode(string $zip_code): void
    {
        $this->zip_code = $zip_code;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo ?? 'r_default.jpg';
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    //----------------------------------------------------------------

    // CRUD Operations

    public function getRestaurantByOwnerId($ownerId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM restaurants WHERE owner_id = ?");
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function createRestaurant(int $owner_id, string $name, ?string $phone, ?string $description, string $address, string $city, string $zip_code, string $country, ?float $latitude, ?float $longitude, ?string $photo): bool
    {
        $query = "INSERT INTO restaurants (
                owner_id, 
                name, 
                phone, 
                description, 
                address, 
                city, 
                zip_code, 
                country, 
                latitude, 
                longitude, 
                photo, 
                created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$owner_id, $name, $phone, $description, $address, $city, $zip_code, $country, $latitude, $longitude, $photo]);
    }

    public function updateRestaurant(
        int $idRestaurants,
        int $owner_id,
        string $name,
        ?string $phone,
        ?string $description,
        string $address,
        string $city,
        string $zip_code,
        string $country,
        ?float $latitude,
        ?float $longitude,
        ?string $photoPath
    ): bool {
        // 1. Si la photo a été fournie, on insère d'abord la nouvelle photo dans la table 'photos'
        if ($photoPath) {
            // Insertion de la photo dans la table 'photos'
            $stmt = $this->pdo->prepare("INSERT INTO photos (photo_path) VALUES (:photo_path)");
            $stmt->bindValue(':photo_path', $photoPath, \PDO::PARAM_STR);
            $stmt->execute();

            // Récupérer l'ID de la photo insérée
            $photoId = $this->pdo->lastInsertId();

            // 2. Mettre à jour la relation entre le restaurant et la photo dans la table 'restaurant_photos'
            $stmt = $this->pdo->prepare("REPLACE INTO restaurant_photos (restaurant_id, photo_id) 
                                         VALUES (:restaurant_id, :photo_id)");
            $stmt->bindValue(':restaurant_id', $idRestaurants, \PDO::PARAM_INT);
            $stmt->bindValue(':photo_id', $photoId, \PDO::PARAM_INT);
            $stmt->execute();
        }

        // 3. Mettre à jour les autres informations du restaurant
        $stmt = $this->pdo->prepare("
            UPDATE restaurants 
            SET owner_id = :owner_id, 
                name = :name, 
                phone = :phone, 
                description = :description, 
                address = :address, 
                city = :city, 
                zip_code = :zip_code, 
                country = :country, 
                latitude = :latitude, 
                longitude = :longitude
            WHERE idRestaurants = :idRestaurants
        ");

        $stmt->bindValue(':owner_id', $owner_id, \PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, \PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone ?? null, $phone ? \PDO::PARAM_STR : \PDO::PARAM_NULL);
        $stmt->bindValue(':description', $description ?? null, $description ? \PDO::PARAM_STR : \PDO::PARAM_NULL);
        $stmt->bindValue(':address', $address, \PDO::PARAM_STR);
        $stmt->bindValue(':city', $city, \PDO::PARAM_STR);
        $stmt->bindValue(':zip_code', $zip_code, \PDO::PARAM_STR);
        $stmt->bindValue(':country', $country, \PDO::PARAM_STR);
        $stmt->bindValue(':latitude', $latitude ?? null, $latitude !== null ? \PDO::PARAM_STR : \PDO::PARAM_NULL);
        $stmt->bindValue(':longitude', $longitude ?? null, $longitude !== null ? \PDO::PARAM_STR : \PDO::PARAM_NULL);
        $stmt->bindValue(':idRestaurants', $idRestaurants, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteRestaurantById(int $idRestaurants): bool
    {
        // Vérification si le restaurant existe avant de le supprimer
        $stmt = $this->pdo->prepare("SELECT idRestaurants FROM restaurants WHERE idRestaurants = ?");
        $stmt->execute([$idRestaurants]);
        $restaurant = $stmt->fetch();

        if (!$restaurant) {
            error_log("Restaurant non trouvé avec l'ID : $idRestaurants");
            return false;  // Si le restaurant n'existe pas, on retourne false
        }

        // Si le restaurant existe, on procède à la suppression
        $stmt = $this->pdo->prepare("DELETE FROM restaurants WHERE idRestaurants = ?");
        if (!$stmt->execute([$idRestaurants])) {
            error_log("Erreur SQL [deleteRestaurantById] : " . implode(" | ", $stmt->errorInfo()));
            return false;
        }

        return true;
    }

    public function getRestaurantFindById(int $idRestaurants): ?Restaurant
    {
        $stmt = $this->pdo->prepare("SELECT * FROM restaurants WHERE idRestaurants = ?");
        $stmt->execute([$idRestaurants]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            $restaurant = new Restaurant(null);
            foreach ($result as $key => $value) {
                if (property_exists($restaurant, $key)) {
                    $restaurant->$key = $value;
                }
            }
            return $restaurant;
        }
        return null;
    }

    public function getAllRestaurants(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM restaurants");

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $restaurants = [];
        foreach ($results as $row) {
            $restaurant = new self($this->pdo);
            foreach ($row as $key => $value) {
                if (property_exists($restaurant, $key)) {
                    $restaurant->$key = $value;
                }
            }
            $restaurants[] = $restaurant;
        }

        return $restaurants;
    }

    // Fonction de récupération des restaurants aléatoires
    public static function getRandomRestaurants(\PDO $pdo): array
    {
        $query = "SELECT r.idRestaurants, 
                         r.owner_id, 
                         r.name, 
                         r.address, 
                         r.phone, 
                         r.description, 
                         COALESCE(p.photo_path, 'r_default.jpg') AS photo, 
                         r.created_at 
                  FROM restaurants r
                  LEFT JOIN restaurant_photos rp ON r.idRestaurants = rp.restaurant_id
                  LEFT JOIN photos p ON rp.photo_id = p.idPhoto
                  GROUP BY r.idRestaurants
                  ORDER BY RAND() 
                  LIMIT 5";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $restaurantsInfo = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $restaurants = [];
        foreach ($restaurantsInfo as $restaurant) {
            $newRestaurant = new Restaurant();
            $newRestaurant->setId($restaurant["idRestaurants"]);
            $newRestaurant->setOwner($restaurant["owner_id"]);
            $newRestaurant->setName($restaurant["name"]);
            $newRestaurant->setAddress($restaurant["address"]);
            $newRestaurant->setPhone($restaurant["phone"]);
            $newRestaurant->setDescription($restaurant["description"]);
            $newRestaurant->setPhoto($restaurant["photo"]);
            $newRestaurant->setCreatedAt($restaurant["created_at"]);

            $restaurants[] = $newRestaurant;
        }

        return $restaurants;
    }

    public static function getRandomReviews(\PDO $pdo, int $limit = 3): array
    {
        $query = "SELECT r.idReviews, 
                         r.rating, 
                         r.comment, 
                         r.created_at, 
                         u.firstName, u.lastName, 
                         COALESCE(uph.photo_path, 'u_default.jpg') AS userPhoto, 
                         res.idRestaurants, res.name AS restaurantName, 
                         COALESCE(rph.photo_path, 'r_default.jpg') AS restaurantPhoto
                  FROM reviews r
                  JOIN users u ON r.user_id = u.idUsers
                  LEFT JOIN user_photos up ON u.idUsers = up.user_id
                  LEFT JOIN photos uph ON up.photo_id = uph.idPhoto
                  JOIN restaurants res ON r.restaurant_id = res.idRestaurants
                  LEFT JOIN restaurant_photos rp ON res.idRestaurants = rp.restaurant_id
                  LEFT JOIN photos rph ON rp.photo_id = rph.idPhoto
                  WHERE r.idReviews >= (SELECT FLOOR(MAX(idReviews) * RAND()) FROM reviews)
                  LIMIT :limit";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        $reviewsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $reviews = [];
        foreach ($reviewsData as $review) {
            $reviews[] = (object) [
                'id' => $review['idReviews'],
                'rating' => $review['rating'],
                'content' => $review['comment'],
                'created_at' => $review['created_at'],
                'userName' => $review['firstName'] . ' ' . $review['lastName'],
                'userPhoto' => $review['userPhoto'],
                'restaurantId' => $review['idRestaurants'],
                'restaurantName' => $review['restaurantName'],
                'restaurantPicture' => $review['restaurantPhoto'],
            ];
        }

        return $reviews;
    }

    public static function getStars($rating)
    {
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5 ? '⯪' : ''; // Demi-étoile
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        return str_repeat('★', $fullStars) . $halfStar . str_repeat('☆', $emptyStars);
    }

    public function uploadPhoto($restaurantId, $file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Erreur lors du téléchargement de la photo.");
        }

        $targetDir = __DIR__ . "/../../public/uploads/restaurant_photos/";
        $targetFile = $targetDir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Vérification du type de fichier
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            throw new \Exception("Type de fichier non autorisé.");
        }

        // Déplacement du fichier téléchargé
        if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
            throw new \Exception("Erreur lors du déplacement du fichier.");
        }

        return $targetFile;
    }
}
