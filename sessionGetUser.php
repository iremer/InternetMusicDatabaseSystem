<?php
if (!isset($_SESSION))
    session_start();
if (isset($_SESSION['username'])) {
    $userNme = $_SESSION['username'];
    $getUserID = "SELECT user_id FROM user WHERE username = ?";
    $userIDStatement = $connection->prepare($getUserID);
    $userIDStatement->execute([$userNme]);
    $IDresult = $userIDStatement->fetchAll(PDO::FETCH_ASSOC);
    $id = $IDresult[0]["user_id"];
}
