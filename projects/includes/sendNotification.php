<?php

session_start();

$err = false;

if (isset($_POST['projectId'])) {
    $projectId = htmlentities($_POST['projectId']);
} else {
    $err = true;
}

if (isset($_POST['priority'])) {
    $priority = htmlentities($_POST['priority']);
} else {
    $priority = 0;
}

if (isset($_POST['message'])) {
    $message = htmlentities($_POST['message']);
} else {
    $err = true;
}

if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];
} else {
    $err = true;
}

//If there was an error
if ($err) {
    print 'Something went wrong';
} else {
    //Adding the project to the database
    include '../../core/core.php';

    $stmt = $pdo->prepare("SELECT email FROM sharedprojects WHERE projectId = ?");
    $stmt->execute([$projectId]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sharedUserEmail = $row['email'];

        if($userEmail != $sharedUserEmail){

            $insertStmt = $pdo->prepare("INSERT INTO notifications (owner, message, projectId, priority) VALUES (?, ?, ?, ?)");
            $insertStmt->execute([$sharedUserEmail, $message, $projectId, $priority]);

            if ($insertStmt->rowCount() > 0) {
                // Notification sent successfully for this user
            } else {
                // Notification sending failed for this user
                echo "Failed to send the notification for $sharedUserEmail.";
            }
        }

    }

    // Close the database connection
    $pdo = null;
}
