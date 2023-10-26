<?php

session_start();

$err = false;

include '../../core/core.php';

if (isset($_POST['taskId'])) {
    $taskId = htmlentities($_POST['taskId']);
} else {
    $err = true;
}

if (isset($_POST['status'])) {
    $status = htmlentities($_POST['status']);
    $status == 1 ? $status = -1 : $status = 1;
} else {
    $err = true;
}

if ($err) {
    print 'Something went wrong';
} else {

    $sql = "UPDATE tasks SET taskStatus = :newTaskStatus WHERE id = :taskId";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newTaskStatus', $status, PDO::PARAM_STR);
        $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
        $stmt->execute();

        // Check if the task was successfully updated
        if ($stmt->rowCount() > 0) {
            echo "Task status updated successfully.";
        } else {
            echo "Task not found or status not updated.";
            print "<br> The task id is $taskId <br> The status is: $status";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $pdo = null;
}
