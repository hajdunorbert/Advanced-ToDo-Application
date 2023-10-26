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
            background: linear-gradient(135deg, #ff9d6e, #ff6b6b);
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
                    Sign up
                </div>
                <div class="card-body">
                    <div class='border text-danger text-center'>
                        <?php
                        //If the register button was pressed
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            //open the database connection
                            include "../core/core.php";

                            $err        = false;

                            //Get the input values and sanitize them
                            $email      = htmlentities($_POST['email']);
                            $password   = htmlentities($_POST['password']);

                            //Check if the email is valid
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $err = true;
                            } else {
                                //if the email is valid
                                try {
                                    //User-provided email to check
                                    $emailToCheck = htmlspecialchars($email);

                                    //SQL query to check if the email exists
                                    $sql = "SELECT COUNT(*) FROM users WHERE email = :email";

                                    //Prepare the statement
                                    $stmt = $pdo->prepare($sql);

                                    //Bind the email parameter
                                    $stmt->bindParam(':email', $emailToCheck, PDO::PARAM_STR);

                                    //Execute the statement
                                    $stmt->execute();

                                    //Fetch the result
                                    $count = $stmt->fetchColumn();

                                    if ($count > 0) {
                                        $err = true;
                                    }
                                } catch (PDOException $e) {
                                    echo "Error: " . $e->getMessage();
                                }
                            }
                            //Check if the password is valid
                            //if the password is not empty
                            if ($password != '') {
                                //check password length
                                if (strlen($password) < 8) {
                                    $err = true;
                                } else {
                                    //Hash the password
                                    $password = password_hash($password, PASSWORD_DEFAULT);
                                }
                            } else {
                                //if the password is empty
                                $err = true;
                            }
                            //If there is some error
                            if ($err) {
                                print "Something went wrong, please make sure you've completed the fields correctly";
                            } else {
                                //If there are no error
                                //register user

                                //Create email verification token
                                $uniid = bin2hex(random_bytes(32));

                                //SQL query with placeholders
                                $sql = "INSERT INTO users (email, password, uniid) VALUES (:email, :password, :uniid)";

                                //Prepare the SQL statement
                                $stmt = $pdo->prepare($sql);

                                //Bind parameters to placeholders
                                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                                $stmt->bindParam(':uniid', $uniid, PDO::PARAM_STR);

                                //Execute the query
                                $stmt->execute();

                                //sending email with the verification link
                                // the message
                                $msg = "To verify your email open the link below
                                            https://hajdunorbert.com/todo/signup/?verify=$uniid";

                                // use wordwrap() if lines are longer than 70 characters
                                $msg = wordwrap($msg, 70);

                                // send email
                                //mail($email, "Verify your account", $msg);

                                //Redirect user to the login page
                                $baseUrl = $_SERVER['HTTP_HOST'];

                                include "../core/settings.php";

                                if ($projectFolder == "") {
                                    header("Location: http://$baseUrl/login/");
                                } else {
                                    header("Location: http://$baseUrl/$projectFolder/login/");
                                }
                                exit();

                                //close the connection
                                $pdo = null;
                            }
                        }
                        ?>
                    </div>

                    <form method="post">

                        <sl-input class="email-input" label="Email" type="email" name="email" placeholder="Enter your email" size="medium">
                            <sl-icon name="envelope-at" slot="prefix"></sl-icon>
                        </sl-input>
                        <ul id="emailError" class="text-danger"></ul>

                        <sl-input class="mb-2 password-input" label="Password" type="password" name="password" placeholder="Enter your password" size="medium" password-toggle>
                            <sl-icon name="key" slot="prefix"></sl-icon>
                        </sl-input>
                        <ul id="passError" class="text-danger"></ul>

                        <sl-button type="submit" name="register" class="signUpButton" variant="primary">Sign Up</sl-button>

                    </form>

                    <p class='termsPolicy'>
                        By continuing, you agree to Nortaur's
                        <a target=”_blank” href="../terms">Terms of Service</a> and
                        <a target=”_blank” href="../privacy">Privacy Policy</a>.
                    </p>
                    <label class="termsPolicy">You already have an account? <a href="../login">Sign in</a></label>

                </div>
            </div>

        </div>
    </div>

    <script src='core.js'></script>

</body>

</html>