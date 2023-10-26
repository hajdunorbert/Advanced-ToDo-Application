<?php

session_start();

include '../../core/core.php';

//Getting the projects for the user
//$sql = "SELECT * FROM projects WHERE id = :projectId";
$sql = "SELECT * 
        FROM projects
        WHERE id = :projectId 
        AND (projectOwner = :userEmail OR id IN (SELECT projectId FROM sharedprojects WHERE email = :userEmail))";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':projectId', $_POST['data'], PDO::PARAM_STR);
    $stmt->bindParam(':userEmail', $_SESSION['user_email'], PDO::PARAM_STR);
    $stmt->execute();

    // Fetch all the rows as an associative array
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$szam = 0;
foreach ($projects as $project) {
    // Access project details using $project['column_name']
    echo "<div class='renameProjectTitleContainer border mb-2'><input name='" . $project['projectTitle'] . "' data-value='" . $project['id'] . "'  id='renameProjectTitleButton' class='btn' type='button' value='" . $project['projectTitle'] . "'></div>";
    print "<div id='renameProjectTitleButtonContainer' class='renameProjectTitleButtonContainer'></div>";
    //Get the task from the project where the task is active
    // Define an SQL query to retrieve tasks associated with the project
    $taskSql = "SELECT * FROM tasks WHERE projectId = :projectId
                ORDER BY CASE WHEN taskStatus = 1 THEN 0 ELSE 1 END";

    try {
        $taskStmt = $pdo->prepare($taskSql);
        $taskStmt->bindParam(':projectId', $project['id'], PDO::PARAM_STR);
        $taskStmt->execute();

        // Fetch all the tasks associated with the project
        $tasks = $taskStmt->fetchAll(PDO::FETCH_ASSOC);

        // Iterate through the tasks and display them
        foreach ($tasks as $task) {
            if ($task['taskStatus'] == -1) {
                $athuzas = '<s>';
                $athuzasEND = '</s>';

                $checked = 'checked';
            } else {
                $checked = '';

                $athuzas = '';
                $athuzasEND = '';
            }

            //Priority
            if ($task['taskPriority'] == 'p1') {
                $style = "style='background-color:rgb(233, 139, 139);border: 1px solid rgb(181, 10, 10);'";
            } else  if ($task['taskPriority'] == 'p2') {
                $style = "style='background-color: rgb(241, 241, 149);border: 1px solid rgb(213, 213, 0);'";
            } else if ($task['taskPriority'] == 'p3') {
                $style = "style='background-color: rgb(119, 226, 253);border: 1px solid rgb(5, 155, 193);'";
            } else {
                $style = "";
            }
            //Priority END
            echo "<button class='btn taskButton mb-2' id='" . $task['id'] . "' class='border mt-2'>";
            echo "  <g class='taskName'>

                        <div class='row'>
                            <div class='col-1 taskCheckboxContainer'>
                                <label id='myCheckbox' style='margin-right:0px; padding-right:0px;'>
                                    <input $checked id='checkbox' type='checkbox' data-value='" . $task['id'] . "' class='taskRadionButton' name='drone'>
                                    <span class='checkmark' $style></span>
                                </label>
                            </div>

                            <div class='col-10' data-bs-toggle='modal' data-bs-target='#editTaskModal' id='editTaskButton' data-value='" . $task['id'] . "'>
                                $athuzas<div class='taskTitle'>" . $task['taskName'] . "</div>$athuzasEND
                            </div>

                            <div class='col-1 deleteTaskButtonContainer'>
                                <sl-tooltip content='Delete Task'>
                                    <div id='removeTask' data-value='" . $task['id'] . "' class='deleteTaskButton'><i class='fa-solid fa-xmark'></i></div>
                                </sl-tooltip>
                            </div>
                                
                        </div>

                        
                    </g>";
            if ($task['taskDescription'] != '') {
                echo "<g class='taskDescription'>" . $task['taskDescription'] . "</g>";
            }
            echo "</button>";

            // Add more code to display other task details as needed
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (empty($projects)) {
    print "Project does not exits or you don't have access to it.";
}

$pdo = null;
