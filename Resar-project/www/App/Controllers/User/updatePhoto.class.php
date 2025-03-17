<?php

namespace App\Controllers\User;

use App\Models\user;
use App\Config\DbConnect;


class UpdatePhoto
{
    public function execute($postData, $filesData)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $pdo = DbConnect::getPDO();
        $photoProfil = new user($pdo);

        if (isset($postData['submit_photo'])) {
            $result = $photoProfil->uploadPhotoUser($_SESSION['user_id'], $filesData['profile_photo']);

            if (isset($result['error'])) {
                $error = $result['error'];
            } else {
                $success = $result['success'];
            }
        }

        require __DIR__ . '/../../Views/Profils/updatePhoto_view.php';
    }
}
