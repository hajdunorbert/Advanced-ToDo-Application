<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_email'])) {
    session_destroy();

    $baseUrl = $_SERVER['HTTP_HOST'];

    include "../core/settings.php";

    if ($projectFolder == "") {
        header("Location: http://$baseUrl/");
    } else {
        header("Location: http://$baseUrl/$projectFolder/");
    }

    exit(); // Make sure to stop the script execution after the redirect
}
