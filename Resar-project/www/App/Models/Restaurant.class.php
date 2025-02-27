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
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getDescription(): string
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
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
    public function setDescription(string $description): void
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


    public function createRestaurant(int $owner_id, string $name, ?string $phone, ?string $description, string $address, string $city, string $zip_code, string $country, ?float $latitude, ?float $longitude, ?string $photo): bool
    {
        $query = "INSERT INTO Restaurants (
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
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                NOW())";

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$owner_id, $name, $phone, $description, $address, $city, $zip_code, $country, $latitude, $longitude, $photo]);
    }

    public function update(int $idRestaurants, int $owner_id, string $name, ?string $phone, ?string $description, string $address, string $city, string $zip_code, string $country, ?float $latitude, ?float $longitude, ?string $photo): bool
    {
        $stmt = $this->pdo->prepare("UPDATE Restaurants 
                                    SET owner_id = ?, 
                                    name = ?, phone = ?, 
                                    description = ?, 
                                    address = ?, 
                                    city = ?, 
                                    zip_code = ?, 
                                    country = ?, 
                                    latitude = ?, 
                                    longitude = ?, 
                                    photo = ? 
                                    WHERE idRestaurants = ?");

        return $stmt->execute([$owner_id, $name, $phone, $description, $address, $city, $zip_code, $country, $latitude, $longitude, $photo, $idRestaurants]);
    }

    public function delete(int $idRestaurants): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Restaurants WHERE idRestaurants = ?");
        return $stmt->execute([$idRestaurants]);
    }

    public function getRestaurantFindById(int $idRestaurants): ?Restaurant
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Restaurants WHERE idRestaurants = ?");
        $stmt->execute([$idRestaurants]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            $restaurant = new Restaurant();
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
        $stmt = $this->pdo->query("SELECT * FROM Restaurants");

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
}