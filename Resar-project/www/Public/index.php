<?php

//Empêcher le document d’expirer lorsque nous voulons revenir à une page avec une demande POST
ini_set('session.cache_limiter', 'public');
session_cache_limiter(false);

// var_dump($_SESSION);

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

use App\Controllers\{User, LoginUser, Restaurants, Home, Search};
use App\Controllers\Register\{RegisterUser, RegisterRestaurant};
use App\Config\DbConnect;

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
            (new Restaurants\Read())->execute($_POST);
            break;

        case 'restaurant-details':
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            (new Restaurants\Details())->execute($id);
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
            // var_dump($_POST);          
            (new RegisterRestaurant())->execute($_POST, $_FILES);
            require '../App/Views/Register/registerRestaurant_view.php';

            break;
            
            //----------------------------------------------------------------------------------


            // case 'login-owner':
            //     if (isset($_POST['loginSubmit'])) {
            //         $loginController = new LoginUser();
            //         $loginController->execute($_POST);
            //     }
            //     break;


            // case 'admin_restaurant':
            //     if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            //         require '../App/Views/admin_restaurant.php';
            //     } else {
            //         header("HTTP/1.1 403 Forbidden");
            //         echo "Accès refusé.";
            //     }
            //     break;

        case 'error':
            require '../App/Views/error.php';
            break;

        case 'success':
            require '../App/Views/success.php';
            break;

        case 'logout':
            session_start();  // Démarre la session pour s'assurer qu'elle est active
            session_unset();  // Libère toutes les variables de session
            session_destroy();  // Détruit la session
            header("Location: ?page=home");  // Redirige vers la page d'accueil
            exit;
            break;


        default:
            header("HTTP/1.1 404 Not Found");
            echo "Page non trouvée.";
            break;
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    require "../App/Views/error.php";
}
