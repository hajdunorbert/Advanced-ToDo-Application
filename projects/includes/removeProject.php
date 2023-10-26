<?php

session_start();

$err = false;

include '../../core/core.php';

if (isset($_POST['projectId'])) {
    $projectId = htmlentities($_POST['projectId']);
} else {
    $err = true;
}

if ($err) {
    print 'Something went wrong';
} else {
    try {
        // Start a database transaction to ensure consistency
        $pdo->beginTransaction();

        // Define the SQL query to delete tasks associated with the project
        $deleteTasksSql = "DELETE FROM tasks WHERE projectId = :projectId";
        $stmtDeleteTasks = $pdo->prepare($deleteTasksSql);
        $stmtDeleteTasks->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmtDeleteTasks->execute();

        // Define the SQL query to delete the project based on its ID
        $deleteProjectSql = "DELETE FROM projects WHERE id = :projectId AND projectOwner = :user_email";
        $stmtDeleteProject = $pdo->prepare($deleteProjectSql);
        $stmtDeleteProject->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmtDeleteProject->bindParam(':user_email', $_SESSION['user_email'], PDO::PARAM_STR);
        $stmtDeleteProject->execute();

        // Commit the transaction if everything was successful
        $pdo->commit();

        // Check if the project was successfully deleted
        if ($stmtDeleteProject->rowCount() > 0) {
            echo "Project and associated tasks deleted successfully.";
        } else {
            echo "Project not found or not deleted.";
        }
    } catch (PDOException $e) {
        // Roll back the transaction and handle any exceptions
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $pdo = null;
}
