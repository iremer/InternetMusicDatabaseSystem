<?php
include("databaseConnection.php");
session_start();
if (isset($_SESSION['username'])) {
    $userNme = $_SESSION['username'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="music, music database, album, song, musician, singer, composer, music awards">
    <title>I-MuuDS</title>
    <link rel="stylesheet" href="styleCentralizedLogo.css">
</head>

<body style="background-color:rgb(0, 0, 0); text-align: center;">
    <div class="centralized">
        <div class="signupImageHover">
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
                <!-- logged in -->
                <img src="images/userIcon.png" width="30" style="float: right; margin-right: 15px; margin-left: 3px;" id="profileButton">
                <?php echo "<label for='userWelcome' style = 'color: wheat; float: right; margin-top: 5px;'>$userNme |</label>"; ?>
            <?php } else { ?>
                <!-- not logged in -->
                <img src="images/userIcon.png" width="30" style="float: right; margin-right: 15px;" id="signUpButton">
            <?php } ?>
        </div>
        <script>
            document.querySelector("#signUpButton").onclick = function() {
                window.open("signUpLogin.php", "_self")
            }
        </script>
        <script>
            document.querySelector("#profileButton").onclick = function() {
                window.open("profileScreen.php", "_self")
            }
        </script>
        <br>
        <hr>
        <img src="images/Logo_HQ.gif" width="300" style="margin-top: 150px;">
        <form id="queryForm">
            <input type="text" autocomplete="off" placeholder="Search for musics, albums, musicians on I-MuuDS" name="q" onkeypress="checkQuery()" style="background-color:rgb(0, 0, 0); border: solid 1px #5e5e5ebe; height: 35px; width: 500px; font-size:16px; color:rgb(248, 212, 112);
            border-radius: 15px; padding-left: 15px;">
            <script>
                function checkQuery() {
                    // checking if the entered value is empty or not
                    let words = document.getElementsByName("q")[0];
                    if (words.value !== "" && words.value !== " ") {
                        let validation = document.getElementById("queryForm");
                        validation.action = "showQueryPagination.php";
                        validation.method = "GET";
                        validation.target = "_self";
                    }
                }
            </script>
        </form>
    </div>
</body>

</html>