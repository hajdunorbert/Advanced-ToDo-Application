<?php

session_start();

$err = false;

if (isset($_POST['projectName'])) {
    $projectName = htmlentities($_POST['projectName']);
    $projectName = trim($_POST['projectName']);
} else {
    $err = true;
}

if ($projectName == '' or strlen($projectName) < 3) {
    $err = true;
}

//Get the project color
if (isset($_POST['projectColor'])) {
    $projectColor = htmlentities($_POST['projectColor']);
} else {
    $projectColor = '#36454F';
}

//If there was an error
if ($err) {
} else {
    //Adding the project to the database
    include '../../core/core.php';

    $sql = "INSERT INTO projects (projectTitle, projectOwner, projectColor) VALUES (:pTitle, :pOwner, :pColor)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pTitle', $projectName, PDO::PARAM_STR);
    $stmt->bindParam(':pOwner', $_SESSION['user_email'], PDO::PARAM_STR);
    $stmt->bindParam(':pColor', $projectColor, PDO::PARAM_STR);

    try {
        $stmt->execute();
        $projectId = $pdo->lastInsertId();
        print $projectId;
    } catch (PDOException $e) {
    }

    $pdo = null;
}
