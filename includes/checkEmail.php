<?php

include '../core/core.php';

try {
    //User-provided email to check
    $emailToCheck = htmlentities($_POST['email']);

    //SQL query to check if the email exists
    $sql = "SELECT COUNT(*) FROM users WHERE email = :email";

    //Prepare the statement
    $stmt = $pdo->prepare($sql);

    //Bind the email parameter
    $stmt->bindParam(':email', $emailToCheck, PDO::PARAM_STR);

    //Execute the statement
    $stmt->execute();

    //Fetch the result
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "e";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

//close the connection
$pdo = null;
