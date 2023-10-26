<?php
// Check if the user is logged in
if (isset($_SESSION['user_email'])) {
    $baseUrl = $_SERVER['HTTP_HOST'];

    include "../core/settings.php";

    if ($projectFolder == "") {
        header("Location: http://$baseUrl/projects/");
    } else {
        header("Location: http://$baseUrl/$projectFolder/projects/");
    }

    exit();
}
