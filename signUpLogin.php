<?php
include("databaseConnection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sign Up-Login</title>
    <link rel="stylesheet" href="styleSignUpLogin.css">
</head>

<body>
    <div class="formPage">
        <div class="formBox">
            <div class="buttonBox">
                <div id="buttons"></div>
                <button type="button" class="toggleButton" onclick="login()" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    font-weight: 700;">Log In</button>
                <button type="button" class="toggleButton" onclick="signUp()" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    font-weight: 700;">Register</button>
            </div>
            <form id="login" class="inputGroup" action="checkUser.php" method="POST">
                <input type="text" class="inputField" name="nameLogin" placeholder="Enter Username" required autocomplete="off">
                <input type="password" class="inputField" name="passwordLogin" placeholder="Enter Password" required autocomplete="off">
                <button type="submit" class="submitButton" style="margin-top: 50px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    font-weight: 700;">Log In</button>
            </form>
            <form id="signUp" class="inputGroup">
                <input type="text" class="inputField" name="nameSignUp" placeholder="Enter Username" required autocomplete="off">
                <input type="password" class="inputField" name="passwordSignUp" placeholder="Enter Password" required autocomplete="off">
                <input type="password" class="inputField" name="passwordRepeatSignUp" onkeyup="checkPassword()" placeholder="Enter Password Again" required autocomplete="off">
                <input type="checkbox" id="termsCheckBox" name="termsCheckBox" class="checkBox" required oninvalid="this.setCustomValidity('To continue you must agree to the terms and conditions.')" onchange="this.setCustomValidity('')">
                <span><label style="font-size: 12px;" for="termsCheckBox">I agree to the <a href="termsAndConditions.php">terms & conditions</a></label></span>
                <button type="submit" class="submitButton" onclick="postData()" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    font-weight: 700;"> Sign Up</button>
                <script>
                    function checkPassword() {
                        // checking if the passwords match
                        let password1 = document.getElementsByName("passwordSignUp")[0];
                        let password2 = document.getElementsByName("passwordRepeatSignUp")[0];
                        if (password1.value !== password2.value) {
                            password2.setCustomValidity("Passwords Don't Match");
                        } else if (password1.value === password2.value) {
                            password2.setCustomValidity("");
                        }
                    }
                    password1.onchange = checkPassword();
                    password2.onkeyup = checkPassword();

                    function postData() {
                        // updating the form features
                        const chck = document.getElementById("termsCheckBox").checked;
                        if (chck) {
                            let validation = document.getElementById("signUp");
                            validation.action = "saveUser.php";
                            validation.method = "POST";
                        }
                    }
                </script>
            </form>
        </div>
    </div>
    <script>
        // for sliding the login/sign up form
        let x = document.getElementById("login");
        let y = document.getElementById("signUp");
        let z = document.getElementById("buttons");

        function signUp() {
            x.style.left = "-400px";
            y.style.left = "50px";
            z.style.left = "110px";
        }

        function login() {
            x.style.left = "50px";
            y.style.left = "450px";
            z.style.left = "0px";
        }
    </script>
</body>

</html>