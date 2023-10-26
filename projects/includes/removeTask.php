<?php

session_start();

$err = false;

include '../../core/core.php';

if (isset($_POST['taskId'])) {
    $taskId = htmlentities($_POST['taskId']);
} else {
    $err = true;
}

if ($err) {
    print 'Something went wrong';
} else {

    // Define the SQL query to delete the task based on its ID
    $sql = "DELETE FROM tasks WHERE id = :taskId";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT); // Assuming 'id' is an integer in the tasks table
        $stmt->execute();

        // Check if the task was successfully deleted
        if ($stmt->rowCount() > 0) {
            echo "Task deleted successfully.";
        } else {
            echo "Task not found or not deleted.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $pdo = null;
}
