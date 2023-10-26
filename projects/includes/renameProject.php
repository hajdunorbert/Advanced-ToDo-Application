<?php

session_start();

$err = false;

if (isset($_POST['data'])) {
    $projectId = htmlentities($_POST['data']);
} else {
    $err = true;
}

if (isset($_POST['newName'])) {
    $newProjectName = htmlentities($_POST['newName']);
} else {
    $err = true;
}

//If there was an error
if ($err) {
    print 'err';
} else {
    //Include the connection data
    include '../../core/core.php';

    $stmt = $pdo->prepare("SELECT projectTitle, projectOwner FROM projects WHERE id = ?");
    $stmt->execute([$projectId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        // Project doesn't exist
        echo "Project not found!";
    } else {
        //Check if the project owner is the user
        $projectOwner = $row['projectOwner'];
        if ($projectOwner === $_SESSION['user_email']) {
            // Update the project title
            $updateStmt = $pdo->prepare("UPDATE projects SET projectTitle = ? WHERE id = ?");
            if ($updateStmt->execute([$newProjectName, $projectId])) {
                // Project title updated successfully
                print $projectId;
            } else {
                // Error occurred during the update
                echo "err";
            }
        }
    }

    // Close the database connection
    $pdo = null;
}
