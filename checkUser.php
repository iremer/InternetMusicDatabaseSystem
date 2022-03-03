<?php
include("databaseConnection.php");
if (!isset($_SESSION))
    session_start();

//to check if the username exists in the database
//then check if the username-password is true
$username = $_POST['nameLogin'];
$password = $_POST['passwordLogin'];
$nameCheckQuery = "SELECT username FROM user WHERE username = '$username'";
$passwordCheckQuery = "SELECT password FROM user WHERE username = '$username'";

$nameStatement = $connection->prepare($nameCheckQuery);
$nameStatement->execute();
$count = count($nameStatement->fetchAll());
if ($count > 0) { // means username exists in database
    $passwordStatement = $connection->prepare($passwordCheckQuery);
    $passwordStatement->execute();
    $pssWrd = $passwordStatement->fetchAll(PDO::FETCH_ASSOC);
    $dneme = password_verify($password, $pssWrd[0]["password"]);
    if (password_verify($password, $pssWrd[0]["password"])) { // password is matching
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        echo "<script language='javascript'>";
        echo 'window.location.replace("mainScreen.php");';
        echo "</script>";
    } else { // false password
        echo "<script language='javascript'>";
        echo 'alert("Please check your information and try again!");';
        echo 'window.location.replace("signUpLogin.php");';
        echo "</script>";
    }
} else { // no such username in database
    echo "<script language='javascript'>";
    echo 'alert("Please check your username and try again!");';
    echo 'window.location.replace("signUpLogin.php");';
    echo "</script>";
}
