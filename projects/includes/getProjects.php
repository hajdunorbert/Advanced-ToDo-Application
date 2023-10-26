<?php

session_start();

include '../../core/core.php';

if (isset($_POST['projectId'])) {
    $projectId = $_POST['projectId'];
} else {
    $projectId = null;
}

//Getting the projects for the user
$sql = "SELECT * FROM projects WHERE projectOwner = :username";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $_SESSION['user_email'], PDO::PARAM_STR);
    $stmt->execute();

    // Fetch all the rows as an associative array
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

foreach ($projects as $project) {
    if ($projectId == $project['id']) {
        $className = 'projectNameButtonSelected';
    } else {
        $className = 'projectNameButton';
    }
    echo "<li class='nav-item'>

            <button value='" . $project['id'] . "'
            id='projectButton'
            class='$className w-100 text-left'>

                <label class='projectName'><i style='color: " . $project['projectColor'] . "; margin-right:5px;' class='fa-solid fa-circle'></i>" . $project['projectTitle'] . "</label>

                <sl-tooltip content='Delete Project'>
                    <label data-value='" . $project['id'] . "' id='deleteProject' class='projectsDeleteProject'>X</label>
                </sl-tooltip

            </button>
        </li>";
}

$pdo = null;
