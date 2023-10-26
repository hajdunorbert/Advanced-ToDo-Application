<?php

session_start();

//Redirect user if already logged in
include '../includes/redirectUserIfLoggedIn.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../includes/header.html";
    ?>

    <style>
        body {
            background: linear-gradient(135deg, #ff5858, #8d82db);
            background-size: cover;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../">
                <img alt='home' width="50px" src="../images/logo.png">
                Nortaur
            </a>
        </div>
    </nav>

    <div class="mt-5">
        <div class="d-flex justify-content-center w-100">

            <div class="card">
                <div class="card-header h1">
                    Login
                </div>
                <div class="card-body">
                    <div class='border text-danger text-center'>
                        <?php

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $err = false;

                            //Getting and sanitizing the input values
                            isset($_POST['email']) ? $email = htmlentities($_POST['email']) : $err = true;
                            isset($_POST['password']) ? $password = htmlentities($_POST['password']) : $err = true;

                            if (!$err) {
                                if ($email != '' and $password != '') {
                                    //open the database connection
                                    include "../core/core.php";

                                    // Prepare and execute a query to retrieve the hashed password
                                    $stmt = $pdo->prepare("SELECT password FROM users WHERE email = :email");
                                    $stmt->bindParam(':email', $email);
                                    $stmt->execute();

                                    // Fetch the hashed password from the database
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    if ($row) {
                                        $hashedPasswordFromDB = trim($row['password']);
                                        // Verify if the provided password matches the hashed password
                                        if (password_verify($password, $hashedPasswordFromDB)) {
                                            print 'Helyes volt minden';
                                            // Passwords match, authentication successful
                                            //Set the session and then redirect the user
                                            $_SESSION['user_email'] = $email;
                                            if (!isset($_GET['redirectedFrom'])) {
                                                include '../includes/redirectUserIfLoggedIn.php';
                                            } else {
                                                //Redirecting user back to previous page

                                                $baseUrl = $_SERVER['HTTP_HOST'];

                                                include "../core/settings.php";

                                                if ($projectFolder == "") {
                                                    header("Location: http://$baseUrl/");
                                                } else {
                                                    header("Location: http://$baseUrl/$projectFolder/projects/");
                                                }

                                                exit();
                                            }
                                        } else {
                                            // Passwords do not match
                                            echo "Invalid username or password.";
                                        }
                                    } else {
                                        // Username not found in the database
                                        echo "Invalid username or password.";
                                    }
                                } else {
                                    //Username and password are empty
                                    echo "Username and Password can't be empty.";
                                }
                            }
                        }
                        ?>
                    </div>

                    <form method="post">

                        <sl-input label="Email" type="email" name="email" placeholder="Enter your email" size="medium">
                            <sl-icon name="envelope-at" slot="prefix"></sl-icon>
                        </sl-input>

                        <sl-input class="mb-2" label="Password" type="password" name="password" placeholder="Enter your password" size="medium" password-toggle>
                            <sl-icon name="key" slot="prefix"></sl-icon>
                        </sl-input>

                        <sl-button type="submit" name="login" variant="primary">Login</sl-button>

                    </form>

                    <label class="termsPolicy"><a href="../login">Forgot your password?</a></label>

                    <p class='termsPolicy'>
                        By continuing, you agree to Nortaur's
                        <a target=”_blank” href="../terms">Terms of Service</a> and
                        <a target=”_blank” href="../privacy">Privacy Policy</a>.
                    </p>

                    <label class="termsPolicy">Not a member yet? <a href="../signup">Sign Up</a></label>

                </div>
            </div>

        </div>
    </div>

</body>

</html>