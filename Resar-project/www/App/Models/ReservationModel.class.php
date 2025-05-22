<?php

namespace App\Models;

use App\Config\DbConnect;

class ReservationModel
{
    private int $userId;
    private string $date;
    private string $heure;
    private int $nombrePersonnes;
    private int $restaurantId;

    public function setUserId(int $id): void
    {
        $this->userId = $id;
    }
    public function setDate(string $date): void
    {
        $this->date = $date;
    }
    public function setHeure(string $heure): void
    {
        $this->heure = $heure;
    }
    public function setNombrePersonnes(int $nombre): void
    {
        $this->nombrePersonnes = $nombre;
    }
    public function setRestaurantId(int $id): void
    {
        $this->restaurantId = $id;
    }

    public function save(): void
    {
        $pdo = DbConnect::getPDO();
        $reservationTime = $this->date . ' ' . $this->heure;

        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, restaurant_id, reservation_time, guests)
                           VALUES (:user_id, :restaurant_id, :reservation_time, :guests)");
        $stmt->execute([
            ':user_id' => $this->userId,
            ':restaurant_id' => $this->restaurantId,
            ':reservation_time' => $reservationTime,
            ':guests' => $this->nombrePersonnes,
        ]);
    }
}
