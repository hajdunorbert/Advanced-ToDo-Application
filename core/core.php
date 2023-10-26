<?php

include "settings.php";

//Creating Database
try {
    // Connect to MySQL (you can change the driver if you use a different DBMS)
    $pdo = new PDO("mysql:host=$host", $username, $pass);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create a new database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $pdo->exec($sql);
} catch (PDOException $e) {
    echo "Error creating database: " . $e->getMessage();
}

//Creating tables
try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $pass);

    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    // Check if tables exist and create them if they don't
    $tables = [
        [
            'name' => $usersTable,
            'sql' => "CREATE TABLE IF NOT EXISTS $usersTable (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50),
                password VARCHAR(255),
                email VARCHAR(100),
                verified TINYINT(1) DEFAULT 0,
                uniid VARCHAR(55),
                projectCount INT(11)
            )"
        ],
        [
            'name' => $projectsTable,
            'sql' => "CREATE TABLE IF NOT EXISTS $projectsTable (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                projectTitle VARCHAR(255),
                projectOwner varchar(255),
                projectColor varchar(55)
            )"
        ],
        [
            'name' => $tasksTable,
            'sql' => "CREATE TABLE IF NOT EXISTS $tasksTable (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                projectId INT(11),
                taskDescription TEXT,
                taskDueDate DATE,
                taskPriority VARCHAR(2),
                taskStatus TINYINT(1),
                taskSubTaskCount INT(255),
                taskName VARCHAR(255)
            )"
        ],
        [
            'name' => $notificationsTable,
            'sql' => "CREATE TABLE IF NOT EXISTS $notificationsTable (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                owner VARCHAR(55),
                priority VARCHAR(2),
                shown TINYINT(1),
                message TEXT,
                projectId VARCHAR(55)
            )"
        ],
        [
            'name' => $sharedprojectsTable,
            'sql' => "CREATE TABLE IF NOT EXISTS $sharedprojectsTable (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                projectId VARCHAR(55),
                email varchar(255),
                accessLevel TINYINT(1),
                pending TINYINT(1)
            )"
        ]
    ];

    foreach ($tables as $tableInfo) {
        $tableName = $tableInfo['name'];
        $sql = $tableInfo['sql'];

        $pdo->exec($sql);
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . " (SQLSTATE: " . $e->errorInfo[1] . ")");
}

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $pass);

    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
