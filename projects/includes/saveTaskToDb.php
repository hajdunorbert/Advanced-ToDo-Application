<?php

session_start();

$err = false;

if (isset($_POST['newTaskName'])) {
    $newTaskName = htmlentities($_POST['newTaskName']);
} else {
    $err = true;
}
if (isset($_POST['newTaskDesc'])) {
    $newTaskDesc = htmlentities($_POST['newTaskDesc']);
}

if (isset($_POST['project'])) {
    $projectId = htmlentities($_POST['project']);
} else {
    $err = true;
}

if (isset($_POST['newTaskPriority'])) {
    $newTaskPriority = htmlentities($_POST['newTaskPriority']);
} else {
    $newTaskPriority = 'p0';
}

if (isset($_POST['date'])) {
    $date = htmlentities($_POST['date']);
}

if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];
}

//If there was an error
if ($err) {
    print 'Something went wrong';
} else {
    //Adding the project to the database
    include '../../core/core.php';

    $stmt = $pdo->prepare("SELECT projectOwner FROM projects WHERE id = ?");
    $stmt->execute([$projectId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        // Project doesn't exist
        echo "Project doesn't exist.";
    } elseif ($row['projectOwner'] == $userEmail) {
        // User is the owner, so insert the task details
        $insertStmt = $pdo->prepare("INSERT INTO tasks (projectId, taskName, taskDescription, taskPriority, taskDueDate) VALUES (?, ?, ?, ?, ?)");
        $insertStmt->execute([$projectId, $newTaskName, $newTaskDesc, $newTaskPriority, $date]);

        if ($insertStmt->rowCount() > 0) {
            // Task saved successfully
        } else {
            // Task save failed
            echo "Failed to save the task.";
        }
    } else {
        // User is not the owner, check sharedProjects
        $accessCheckStmt = $pdo->prepare("SELECT accessLevel FROM sharedprojects WHERE projectId = ? AND email = ?");
        $accessCheckStmt->execute([$projectId, $userEmail]);
        $accessRow = $accessCheckStmt->fetch(PDO::FETCH_ASSOC);

        if ($accessRow && $accessRow['accessLevel'] < 5) {
            // User has access level 4 or higher, so insert the task details
            $insertStmt = $pdo->prepare("INSERT INTO tasks (projectId, taskName, taskDescription, taskPriority, taskDueDate) VALUES (?, ?, ?, ?, ?)");
            $insertStmt->execute([$projectId, $newTaskName, $newTaskDesc, $newTaskPriority, $date]);

            if ($insertStmt->rowCount() > 0) {
                // Task saved successfully
            } else {
                // Task save failed
                echo "<ul class='list-group list-group-flush'><li class='list-group-item list-group-item-danger'>Failed to save the task.</li></ul>";
            }
        } else {
            // User does not have sufficient access level
            echo "<ul class='list-group list-group-flush'><li class='list-group-item list-group-item-danger'>You don't have sufficient access level to add tasks to this project.</li></ul>";
        }
    }

    // Close the database connection
    $pdo = null;
}
