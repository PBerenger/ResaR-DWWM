<?php

namespace App\Controllers\User;

use App\Models\User;
use App\Config\DbConnect;

class UpdatePhoto
{
    private $db;
    private $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->db = DbConnect::getPDO();
        $this->userModel = new User($this->db);
    }

    public function index()
    {
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["profile_pic"])) {
            $userId = $_SESSION['user_id'] ?? null;

            if (!$userId) {
                $message = "Utilisateur non connecté.";
            } else {
                $message = $this->userModel->uploadPhoto($userId, $_FILES["profile_pic"]);
            }
        }

        require __DIR__ . "/../../Views/Profils/updatePhoto_view.php";
    }
}
