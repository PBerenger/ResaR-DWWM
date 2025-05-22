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

    /**
     * Récupère tous les restaurants associés à un propriétaire spécifique.
     *
     * Cette méthode exécute une requête SQL pour sélectionner tous les enregistrements
     * de la table `restaurants` où la colonne `owner_id` correspond à l'identifiant
     * du propriétaire passé en paramètre.
     *
     * @param int $ownerId L'identifiant du propriétaire des restaurants à récupérer.
     * 
     * @return array Un tableau associatif contenant toutes les lignes des restaurants
     *               correspondant au propriétaire, ou un tableau vide si aucun restaurant
     *               n'est trouvé.
     */
    public function getRestaurantByOwnerId($ownerId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM restaurants WHERE owner_id = ?");
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouveau restaurant dans la base de données.
     *
     * Cette méthode insère un nouvel enregistrement dans la table `restaurants` avec les
     * informations fournies, telles que le propriétaire, le nom, le téléphone, la description,
     * l'adresse, la ville, le code postal, le pays, les coordonnées géographiques, et la photo.
     * La date de création est automatiquement ajoutée avec la fonction `NOW()` dans la base de données.
     *
     * @param int $owner_id L'identifiant du propriétaire du restaurant.
     * @param string $name Le nom du restaurant.
     * @param string|null $phone Le numéro de téléphone du restaurant (peut être nul).
     * @param string|null $description La description du restaurant (peut être nulle).
     * @param string $address L'adresse complète du restaurant.
     * @param string $city La ville où se trouve le restaurant.
     * @param string $zip_code Le code postal du restaurant.
     * @param string $country Le pays du restaurant.
     * @param float|null $latitude La latitude du restaurant (peut être nulle).
     * @param float|null $longitude La longitude du restaurant (peut être nulle).
     * @param string|null $photo Le chemin ou l'URL de la photo du restaurant (peut être nul).
     * 
     * @return bool Retourne `true` si l'insertion dans la base de données est réussie, 
     *              sinon `false` en cas d'échec.
     */
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

    /**
     * Met à jour les informations d'un restaurant dans la base de données.
     *
     * Cette méthode met à jour les détails d'un restaurant, y compris le propriétaire,
     * le nom, le téléphone, la description, l'adresse, la ville, le code postal, le pays,
     * les coordonnées géographiques, et la photo (si une nouvelle photo est fournie).
     * Si une nouvelle photo est fournie, elle est insérée dans la table `photos`, et
     * l'association entre le restaurant et la photo est mise à jour dans la table `restaurant_photos`.
     *
     * @param int $idRestaurants L'identifiant du restaurant à mettre à jour.
     * @param int $owner_id L'identifiant du propriétaire du restaurant.
     * @param string $name Le nom du restaurant.
     * @param string|null $phone Le numéro de téléphone du restaurant (peut être nul).
     * @param string|null $description La description du restaurant (peut être nulle).
     * @param string $address L'adresse complète du restaurant.
     * @param string $city La ville où se trouve le restaurant.
     * @param string $zip_code Le code postal du restaurant.
     * @param string $country Le pays du restaurant.
     * @param float|null $latitude La latitude du restaurant (peut être nulle).
     * @param float|null $longitude La longitude du restaurant (peut être nulle).
     * @param string|null $photoPath Le chemin de la nouvelle photo du restaurant (peut être nul).
     * 
     * @return bool Retourne `true` si la mise à jour est réussie, sinon `false` en cas d'échec.
     */
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
        if ($photoPath) {
            $stmt = $this->pdo->prepare("INSERT INTO photos (photo_path) VALUES (:photo_path)");
            $stmt->bindValue(':photo_path', $photoPath, \PDO::PARAM_STR);
            $stmt->execute();

            $photoId = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("REPLACE INTO restaurant_photos (restaurant_id, photo_id) 
                                         VALUES (:restaurant_id, :photo_id)");
            $stmt->bindValue(':restaurant_id', $idRestaurants, \PDO::PARAM_INT);
            $stmt->bindValue(':photo_id', $photoId, \PDO::PARAM_INT);
            $stmt->execute();
        }

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

    /**
     * Supprime un restaurant de la base de données en fonction de son identifiant.
     *
     * Cette méthode vérifie d'abord si un restaurant existe avec l'ID spécifié. Si le restaurant
     * n'est pas trouvé, elle enregistre une erreur dans les logs et retourne `false`. Si le restaurant
     * est trouvé, elle procède à la suppression du restaurant de la table `restaurants`.
     * En cas d'erreur lors de la suppression, un message d'erreur SQL est enregistré dans les logs.
     *
     * @param int $idRestaurants L'identifiant du restaurant à supprimer.
     * 
     * @return bool Retourne `true` si la suppression du restaurant a été effectuée avec succès,
     *              sinon `false` si le restaurant n'a pas été trouvé ou si une erreur est survenue.
     */
    public function deleteRestaurantById(int $idRestaurants): bool
    {
        $stmt = $this->pdo->prepare("SELECT idRestaurants FROM restaurants WHERE idRestaurants = ?");
        $stmt->execute([$idRestaurants]);
        $restaurant = $stmt->fetch();

        if (!$restaurant) {
            error_log("Restaurant non trouvé avec l'ID : $idRestaurants");
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM restaurants WHERE idRestaurants = ?");
        if (!$stmt->execute([$idRestaurants])) {
            error_log("Erreur SQL [deleteRestaurantById] : " . implode(" | ", $stmt->errorInfo()));
            return false;
        }

        return true;
    }

    /**
     * Récupère un restaurant à partir de son identifiant.
     *
     * Cette méthode cherche un restaurant dans la base de données en utilisant son identifiant (`idRestaurants`).
     * Si un restaurant est trouvé, elle crée une instance de la classe `Restaurant` et lui attribue les valeurs récupérées 
     * de la base de données. Les valeurs sont assignées aux propriétés correspondantes de l'objet `Restaurant`.
     * Si aucun restaurant n'est trouvé, la méthode retourne `null`.
     *
     * @param int $idRestaurants L'identifiant du restaurant à récupérer.
     * 
     * @return Restaurant|null Retourne une instance de `Restaurant` si un restaurant est trouvé, sinon `null`.
     */
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

    /**
     * Récupère tous les restaurants de la base de données.
     *
     * Cette méthode exécute une requête SQL pour obtenir toutes les lignes de la table `restaurants`.
     * Elle crée une instance de l'objet actuel pour chaque ligne récupérée et lui attribue les valeurs 
     * des colonnes correspondantes. Ensuite, elle retourne un tableau contenant tous les restaurants récupérés.
     *
     * @return Restaurant[] Un tableau d'instances de la classe `Restaurant` représentant tous les restaurants.
     */
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

    /**
     * Récupère un nombre aléatoire de restaurants de la base de données.
     *
     * Cette méthode exécute une requête SQL pour obtenir un nombre aléatoire de restaurants
     * à partir de la table `restaurants`. Elle crée une instance de l'objet actuel pour chaque
     * ligne récupérée et lui attribue les valeurs des colonnes correspondantes. Ensuite, elle
     * retourne un tableau contenant les restaurants récupérés.
     *
     * @param \PDO $pdo L'instance PDO pour exécuter la requête.
     * 
     * @return Restaurant[] Un tableau d'instances de la classe `Restaurant` représentant les restaurants récupérés.
     */
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

    /**
     * Récupère un nombre aléatoire de critiques de la base de données.
     *
     * Cette méthode exécute une requête SQL pour obtenir un nombre aléatoire de critiques
     * à partir de la table `reviews`. Elle crée un tableau d'objets contenant les informations
     * des critiques récupérées et les retourne.
     *
     * @param \PDO $pdo L'instance PDO pour exécuter la requête.
     * @param int $limit Le nombre maximum de critiques à récupérer (par défaut 3).
     * 
     * @return array Un tableau d'objets contenant les informations des critiques récupérées.
     */
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

    /**
     * Récupère les étoiles correspondant à une note donnée.
     *
     * Cette méthode prend une note (rating) en entrée et retourne une chaîne de caractères
     * représentant les étoiles (pleines, vides et demi-étoiles) en fonction de la note.
     *
     * @param float $rating La note à convertir en étoiles.
     * 
     * @return string Une chaîne de caractères contenant les étoiles correspondantes à la note.
     */
    public static function getStars($rating)
    {
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5 ? '⯪' : '';
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        return str_repeat('★', $fullStars) . $halfStar . str_repeat('☆', $emptyStars);
    }

    /**
     * Upload une photo de restaurant.
     *
     * Cette méthode gère le téléchargement d'une photo pour un restaurant spécifique.
     * Elle vérifie si le fichier a été téléchargé sans erreur, valide le type de fichier,
     * et déplace le fichier téléchargé vers un répertoire cible. Si tout se passe bien,
     * elle retourne le chemin du fichier cible.
     *
     * @param int $restaurantId L'identifiant du restaurant.
     * @param array $file Le tableau contenant les informations sur le fichier téléchargé.
     * 
     * @return string Le chemin du fichier cible après téléchargement.
     */
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

    // upload restaurants photos

    /**
     * Télécharge une photo de restaurant et l'associe à un utilisateur.
     *
     * Cette méthode permet de télécharger une image pour un restaurant en vérifiant que le fichier respecte certaines conditions
     * (type d'image autorisé, taille maximale, et existence du fichier). Elle déplace ensuite l'image dans un répertoire dédié,
     * enregistre le chemin de l'image dans la base de données, puis associe la photo au restaurant via une relation dans la table
     * `restaurant_photos`.
     *
     * @param int $userId L'identifiant de l'utilisateur (restaurant) auquel la photo doit être associée.
     * @param array $file Les informations du fichier téléchargé, comme 'name', 'type', 'size', etc.
     * 
     * @return string Un message indiquant le résultat de l'opération (succès ou erreur).
     */
    public function uploadRestaurantPhoto($userId, $file)
    {
        if (!isset($file['name']) || empty($file['name'])) {
            return "Aucune image sélectionnée.";
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return "Format d'image non autorisé.";
        }

        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return "L'image dépasse la taille maximale autorisée (2 Mo).";
        }

        $uploadDir = 'uploads/restaurant_pics/';
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

            // Lier la photo au restaurant
            $stmt = $this->pdo->prepare("INSERT INTO restaurant_photos (restaurant_id, photo_id) VALUES (:restaurant_id, :photo_id)");
            $stmt->bindValue(':restaurant_id', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':photo_id', $photoId, \PDO::PARAM_INT);
            $stmt->execute();

            return "Photo mise à jour avec succès.";
        } else {
            return "Erreur lors du téléchargement de l'image.";
        }
    }

    // upload restaurants photos

    /**
     * Télécharge une photo de plat et l'associe à un plat dans la base de données.
     *
     * Cette méthode permet de télécharger une image pour un plat en vérifiant que le fichier respecte certaines conditions
     * (type d'image autorisé, taille maximale, et existence du fichier). Elle déplace ensuite l'image dans un répertoire dédié,
     * enregistre le chemin de l'image dans la base de données, puis associe la photo au plat via une relation dans la table
     * `dishes_photos`.
     *
     * @param int $userId L'identifiant du plat auquel la photo doit être associée.
     * @param array $file Les informations du fichier téléchargé, comme 'name', 'type', 'size', etc.
     * 
     * @return string Un message indiquant le résultat de l'opération (succès ou erreur).
     */
    public function uploadDishPhoto($userId, $file)
    {
        if (!isset($file['name']) || empty($file['name'])) {
            return "Aucune image sélectionnée.";
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return "Format d'image non autorisé.";
        }

        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return "L'image dépasse la taille maximale autorisée (2 Mo).";
        }

        $uploadDir = 'uploads/dish_pics/';
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

            // Lier la photo au restaurant
            $stmt = $this->pdo->prepare("INSERT INTO dishes_photos (dish_id, photo_id) VALUES (:dish_id, :photo_id)");
            $stmt->bindValue(':dish_id', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':photo_id', $photoId, \PDO::PARAM_INT);
            $stmt->execute();

            return "Photo mise à jour avec succès.";
        } else {
            return "Erreur lors du téléchargement de l'image.";
        }
    }
}
