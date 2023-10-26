<?php

session_start();

$err = false;
$notifications = array();
$count = 0;

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

    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE owner = ? ORDER BY shown ASC, id DESC");
    $stmt->execute([$userEmail]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        if ($row['projectId'] != "") {
            $href = "href='?project=" . $row['projectId'] . "'";
        } else {
            $href = "";
        }

        $text = "<li style='font-size:16px;'>
                <a class='dropdown-item' data-value='" . $row['id'] . "' $href>
                    " . $row['message'] . "
                </a>
            </li>";

        if ($row['shown'] == 0) {
            $count++;
        }

        array_push($notifications, $text);
    }

    // Close the database connection
    $pdo = null;

    $response = array(
        'count' => $count,
        'notifications' => $notifications
    );

    ksort($response); // Sort the keys alphabetically
    echo json_encode($response);
}
