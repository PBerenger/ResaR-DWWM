<?php
function showAlertInHTML(): string
{
    $alertType = "";
    $message = "";
    if (isset($_SESSION["successMessage"])) {
        $alertType = "success";
        $message = $_SESSION["successMessage"];
    } else if (isset($_SESSION["errorMessage"])) {
        $alertType = "error";
        $message = $_SESSION["errorMessage"];
    }
    
    unset($_SESSION["errorMessage"], $_SESSION["successMessage"]);
    return "<div class='alert $alertType'>$message</div>";
}

/**
 * Return a div to show the error and unset the error
 * @param array $errors array containing the errors
 * @param string $key which message of the array to show
 * @return string|null returns null if the array or key doesn't exists, otherwise return a div.class containing the error message
 */

function showError(?array &$errors, string $key) : ?string {

    $message = $errors[$key] ?? null;
    if ($message) {
        unset($errors[$key]);
        return "<div class='error'>$message</div>";
    } else {
        return null;
    }

}