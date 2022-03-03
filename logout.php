<?php
session_start();
$_SESSION['loggedin'] = false;
session_destroy();
echo "<script language='javascript'>";
echo 'alert("Successfully logged out!");';
echo 'window.location.replace("mainScreen.php");';
echo "</script>";
