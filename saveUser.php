<?php
include("databaseConnection.php");
//get username from form

if (!isset($_SESSION))
    session_start();

$username = $_POST['nameSignUp'];
//prepare the statement
$stmt = $connection->prepare("SELECT * FROM user WHERE username=?");
//execute the statement
$stmt->execute([$username]);
//fetch result
$user = $stmt->fetch();
if ($user) {
    // username already exists
    echo "<script language='javascript'>";
    echo 'alert("This username is already taken!");';
    echo 'window.location.replace("signUpLogin.php");';
    echo "</script>";
} else {
    // username is unique
    $username = $_POST['nameSignUp'];
    $password = $_POST['passwordSignUp'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $passwordRepeat = $_POST['passwordRepeatSignUp'];

    if (strcmp($password, $passwordRepeat) === 0) {
        // passwords match and the user is added to the database
        $insertQuery = "INSERT INTO user(username, password) VALUES(?, ?)";
        $statement1 = $connection->prepare($insertQuery);
        $statement1->execute([$username, $hashedPassword]);

        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        echo "<script language='javascript'>";
        echo 'alert("Successfully signed in!");';
        echo 'window.location.replace("mainScreen.php");';
        echo "</script>";
    }
}
