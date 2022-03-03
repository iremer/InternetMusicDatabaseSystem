<?php
include("databaseConnection.php");
include("sessionGetUser.php");

$data = $_GET['id'];
$qry = "SELECT * FROM musician WHERE musician_id = $data";
$stmnt = $connection->prepare($qry);
$stmnt->execute();
$mscn = $stmnt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Info</title>
    <link rel="stylesheet" href="styleTabs.css">
    <link rel="stylesheet" href="styleDisplayMusician.css">
    <link rel="stylesheet" href="styleBackButton.css">
</head>

<body>
    <?php include("searchBar.php"); ?>
    <div namee="tabsDiv" style="margin-top: 10px; margin-bottom: 4px; padding: 3px; display: flex; justify-content: center;">
        <div class="card" style="padding: 4px; margin-top: 10px;">
            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin-top: 20px;" class="image">
                <?php
                $img_id = $mscn[0]['image_id'];
                $sql = "SELECT image_path FROM images WHERE image_id = $img_id";
                $stmt = $connection->prepare($sql);
                $stmt->execute();
                $imge = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $pic = ($imge[0]["image_path"]);
                echo "<img width='200' src='$pic' style='border-radius: 50%; overflow: hidden;'>"; ?>
                <span class="name" style="margin-top: 10px;"><?php echo $mscn[0]["musician_name"] ?></span>
                <span class="idd">Birthdate: <?php echo $mscn[0]["musician_birthdate"] ?></span>
                <span class="idd">Country: <?php echo $mscn[0]["country"] ?></span>
                <div style="display: flex; flex-direction: row; justify-content: center; align-items: center; margin-top: 3px;">
                    <span class="number"><?php echo $mscn[0]["album_count"] ?> <span class="follow">Albums</span></span>
                </div>

                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    $checkQuery1 = "SELECT musician_id FROM favorites WHERE user_id = $id";
                    $checkQueryStatement1 = $connection->prepare($checkQuery1);
                    $checkQueryStatement1->execute();
                    $checkQueryresult1 = $checkQueryStatement1->fetchAll(PDO::FETCH_ASSOC);
                    $flag1 = true;
                    foreach ($checkQueryresult1 as $key) { // checking if the element is added to favorites before
                        if ($key["musician_id"] === $data) {
                            $flag1 = false;
                            echo " <span style='position: relative;'>                                        
                                            <img src='images/heartClicked.png' width = '20px'>
                                            </span>";
                        }
                    }
                    if ($flag1) { ?>
                        <span style='position: relative;'>
                            <iframe style='display:none;' name='target'></iframe>
                            <a href='addToFavorites.php?id=<?php echo $data; ?>' target='target'><img src='images/heart.png' onclick="alert('Succesfully added to favorites!')" ; width='20px'></a>
                        </span>
                    <?php // logged in 
                    }
                } else { // not logged in
                    ?>
                    <span style='position: relative;'>
                        <img src='images/heart.png' onclick="alert('You must be logged in to add this to your favorites!')" width='20px'>
                    </span>
                <?php } ?>
            </div>
            <div>
                <button type="button" class="collapsible">Albums</button>
                <div class="content">
                    <div style="display: flex; flex-direction: row; text-align: left; margin-top: 10px; margin-bottom: 10px;">
                        <span class="idd">
                            <?php
                            $albumSql = "SELECT album_name, album_id FROM album WHERE musician_id = $data";
                            $albumStmt = $connection->prepare($albumSql);
                            $albumStmt->execute();
                            $albums = $albumStmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($albums as $album) {
                                $albmName = $album["album_name"];
                                $albmId = $album["album_id"];
                                echo "<a style='color:black;' href='showInfoAlbum.php?id=$albmId'>$albmName</a>";
                                echo "<br>";
                            }
                            ?>
                        </span>
                    </div>
                </div>
                <button type="button" class="collapsible">Songs</button>
                <div class="content">
                    <div style="display: flex; flex-direction: row; text-align: left; margin-top: 10px; margin-bottom: 10px;">
                        <span class="idd">
                            <?php
                            $songSql = "SELECT music_name, music_id FROM music WHERE musician_id = $data";
                            $songStmt = $connection->prepare($songSql);
                            $songStmt->execute();
                            $songs = $songStmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($songs as $song) {
                                $songName = $song["music_name"];
                                $songId = $song["music_id"];
                                echo "<a style='color:black;' href='showInfoMusic.php?id=$songId'>$songName</a>";
                                echo "<br>";
                            }
                            ?>
                        </span>
                    </div>
                </div>
                <?php
                $awardSql = "SELECT award_name, award_year FROM awards WHERE musician_id = $data";
                $awardStmt = $connection->prepare($awardSql);
                $awardStmt->execute();
                $awards = $awardStmt->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($awards)) { ?>
                    <button type="button" class="collapsible">Awards</button>
                    <div class="content">
                        <div style="display: flex; flex-direction: row; text-align: left; margin-top: 10px; margin-bottom: 10px;">
                            <span class="idd">
                                <?php

                                foreach ($awards as $award) {
                                    $awardName = $award["award_name"];
                                    $awardYear = $award["award_year"];
                                    echo "<a style='color:black;'>$awardName - $awardYear</a>";
                                    echo "<br>";
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    }
</script>

</html>