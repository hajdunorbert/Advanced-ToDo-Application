<?php

session_start();

$err = false;

if (isset($_SESSION['user_email'])) {
    $userEmail = htmlspecialchars($_SESSION['user_email']);
} else {
    $err = true;
}

if (isset($_POST['projectId'])) {
    $projectId = htmlspecialchars($_POST['projectId']);
} else {
    $err = true;
}

if (isset($_POST['role'])) {
    $role = htmlspecialchars($_POST['role']);
} else {
    $err = true;
}

if (isset($_POST['email'])) {
    $email = htmlspecialchars($_POST['email']);
} else {
    $err = true;
}

//If there was an error
if ($err) {
    print 'Something went wrong';
} else {
    //Adding the project to the database
    include '../../core/core.php';

    $checkInvitationQuery = "SELECT * FROM sharedprojects WHERE projectId = :projectId AND email = :email";
    $checkInvitationStmt = $pdo->prepare($checkInvitationQuery);
    $checkInvitationStmt->bindParam(':projectId', $projectId);
    $checkInvitationStmt->bindParam(':email', $email);
    $checkInvitationStmt->execute();

    if ($checkInvitationStmt->rowCount() > 0) {
        return;
    }

    // Check if the user owns the project
    $query = "SELECT * FROM projects WHERE id = :projectId AND projectOwner = :userEmail";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':projectId', $projectId);
    $stmt->bindParam(':userEmail', $userEmail);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // The user owns the project, so invite the user
        $insertQuery = "INSERT INTO sharedprojects (projectId, email, accessLevel) VALUES (:projectId, :email, :accessLevel)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->bindParam(':projectId', $projectId);
        $insertStmt->bindParam(':email', $email);
        $insertStmt->bindParam(':accessLevel', $role);

        $insertStmt->execute();

        //Send a notification to the user
        // Get the project title from the "projects" table
        $queryProjectTitle = "SELECT projectTitle FROM projects WHERE id = :projectId";
        $stmtProjectTitle = $pdo->prepare($queryProjectTitle);
        $stmtProjectTitle->bindParam(':projectId', $projectId);
        $stmtProjectTitle->execute();

        if ($stmtProjectTitle->rowCount() > 0) {
            $projectTitle = $stmtProjectTitle->fetch(PDO::FETCH_ASSOC)['projectTitle'];
        } else {
            $projectTitle = "Unnamed";
        }

        // Collect the message for the notification
        $notificationMessage = "You have been invited to project <b>$projectTitle</b> by <b>$userEmail</b>.";

        $insertNotificationQuery = "INSERT INTO notifications (owner, message, projectId) VALUES (:owner, :message, :projectId)";
        $insertNotificationStmt = $pdo->prepare($insertNotificationQuery);
        $insertNotificationStmt->bindParam(':owner', $email);
        $insertNotificationStmt->bindParam(':message', $notificationMessage);
        $insertNotificationStmt->bindParam(':projectId', $projectId);

        $insertNotificationStmt->execute();
    } else {
        print "You don't own this project!";
    }

    // Close the database connection
    $pdo = null;
}
