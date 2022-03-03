<?php
include("databaseConnection.php");
include("sessionGetUser.php");

$musicBaseNumber = 50000;
$albumBaseNumber = 10000;
$musicianBaseNumber = 60000;
$data = $_GET['id'];

if ($data >= $musicBaseNumber && $data < ($musicBaseNumber + 10000)) {
    $checkQuery1 = "SELECT music_id FROM favorites WHERE user_id = $id";
    $checkQueryStatement1 = $connection->prepare($checkQuery1);
    $checkQueryStatement1->execute();
    $checkQueryresult1 = $checkQueryStatement1->fetchAll(PDO::FETCH_ASSOC);
    $flag1 = true;
    foreach ($checkQueryresult1 as $key) { // checking if the element is added to favorites before
        if ($key["music_id"] === $data) {
            $flag1 = false;
        }
    }
    if ($flag1) {
        $favQuery1 = "INSERT INTO favorites(fav_id, album_id, music_id, musician_id, user_id) VALUES(?, ?, ?, ?, ?)";
        $favStatement1 = $connection->prepare($favQuery1);
        $favStatement1->execute([NULL, NULL, $data, NULL, $id]);
    }
} if ($data >= $albumBaseNumber && $data < ($albumBaseNumber + 10000)) {
    $checkQuery2 = "SELECT album_id FROM favorites WHERE user_id = $id";
    $checkQueryStatement2 = $connection->prepare($checkQuery2);
    $checkQueryStatement2->execute();
    $checkQueryresult2 = $checkQueryStatement2->fetchAll(PDO::FETCH_ASSOC);
    $flag2 = true;
    foreach ($checkQueryresult2 as $key) { // checking if the element is added to favorites before
        if ($key["album_id"] === $data) {
            $flag2 = false;
        }
    }
    if ($flag2) {
        $favQuery2 = "INSERT INTO favorites(fav_id, album_id, music_id, musician_id, user_id) VALUES(?, ?, ?, ?, ?)";
        $favStatement2 = $connection->prepare($favQuery2);
        $favStatement2->execute([NULL, $data, NULL, NULL, $id]);
    }
} if ($data >= $musicianBaseNumber && $data < ($musicianBaseNumber + 10000)) {
    $checkQuery3 = "SELECT musician_id FROM favorites WHERE user_id = $id";
    $checkQueryStatement3 = $connection->prepare($checkQuery3);
    $checkQueryStatement3->execute();
    $checkQueryresult3 = $checkQueryStatement3->fetchAll(PDO::FETCH_ASSOC);
    $flag3 = true;
    foreach ($checkQueryresult3 as $key) { // checking if the element is added to favorites before
        if ($key["musician_id"] === $data) {
            $flag3 = false;
        }
    }
    if ($flag3) {
        $favQuery3 = "INSERT INTO favorites(fav_id, album_id, music_id, musician_id, user_id) VALUES(?, ?, ?, ?, ?)";
        $favStatement3 = $connection->prepare($favQuery3);
        $favStatement3->execute([NULL, NULL, NULL, $data, $id]);
    }
}
