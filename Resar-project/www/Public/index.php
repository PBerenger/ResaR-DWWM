<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//Empêcher le document d’expirer lorsque nous voulons revenir à une page avec une demande POST
ini_set('session.cache_limiter', 'public');
session_cache_limiter(false);

// Autoload des classes avec le namespace comme chemin
spl_autoload_register(function ($class) {
    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    $path = "../$class.class.php";
    // echo $path;
    if (file_exists($path)) {
        include $path;
    }
});

require_once "../Config/DbConnect.php";

use App\Config\DbConnect;

use App\Controllers\{home, LoginRestaurant, LoginUser, Search};
use App\Controllers\Admin\{ReadAdmin, UpdateByAdmin, DeleteByAdmin};
// use App\Controllers\Owner\{ReadOwner};
use App\Controllers\Register\{RegisterUser, RegisterRestaurant};
use App\Controllers\Restaurants\{ReadRestaurant, Details};
use App\Controllers\User\{ReadUser, UpdateUser, UpdatePhoto, DeleteUser};
use App\Models\User;

$pdo = DbConnect::getPDO();

try {
    $url = parse_url($_SERVER["REQUEST_URI"]);
    parse_str($url["query"] ?? '', $query);
    $page = $query['page'] ?? 'home';

    switch ($page) {
        case 'home':
            $restaurantModel = new Home();
            $restaurants = Home::getRandomRestaurants($pdo);
            $reviews = Home::getRandomReviews($pdo);
            require '../App/Views/home_view.php';
            break;

        //case search

        case 'restaurants-list':
            (new ReadRestaurant())->execute($_POST);
            break;

        case 'restaurant-details':
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            (new Details())->execute($id);
            break;

        case 'register-user':
            (new RegisterUser())->execute($_POST);
            break;

        case 'login-user':
            if (isset($_POST['loginSubmit'])) {
                $loginController = new LoginUser();
                $loginController->execute($_POST);
            }
            break;

        case 'register-restaurant':
            (new RegisterRestaurant())->execute($_POST);
            require '../App/Views/Register/registerRestaurant_view.php';
            break;


        case 'profil-user':
            (new ReadUser())->execute();
            break;

        case 'update-user':
            $controller = new UpdateUser();
            $controller->execute($_POST);
            break;

        case 'update-photo':
            $controller = new UpdatePhoto();
            $controller->index();
            break;

        case "delete-user":
            $controller = new DeleteUser();
            $controller->execute();
            break;

        case 'admin-home':
            (new ReadAdmin())->execute();
            break;

        case 'update-by-admin':
            $controller = new UpdateByAdmin();
            $controller->execute($_POST);
            break;

        case "delete-by-admin":
            $controller = new DeleteByAdmin();
            $controller->execute();
            break;


        // case 'owner-home':
        //     (new ReadOwner())->execute();
        //     break;





        //----------------------------------------------------------------------------------



        case 'error':
            require '../App/Views/error.php';
            break;

        case 'success':
            require '../App/Views/success.php';
            break;

        case 'logout':
            session_start();
            session_unset();
            session_destroy();
            header("Location: ?page=home");
            exit;
            break;


        default:
            header("HTTP/1.1 404 Not Found");
            echo "Page non trouvée.";
            break;
    }
} catch (Exception $e) {
    error_log($e->getMessage()); // Log l'erreur dans le fichier d'erreurs PHP
    $errorMessage = $e->getMessage();
    echo "<h1>Erreur rencontrée</h1>";
    echo "<p>{$errorMessage}</p>";
    require "../App/Views/error.php";
}
