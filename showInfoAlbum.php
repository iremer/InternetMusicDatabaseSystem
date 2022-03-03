<?php
include("databaseConnection.php");
include("sessionGetUser.php");

$data = $_GET['id'];
$qry = "SELECT * FROM album WHERE album_id = $data";
$stmnt = $connection->prepare($qry);
$stmnt->execute();
$album = $stmnt->fetchAll(PDO::FETCH_ASSOC);
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
    <div style="margin-top: 10px; margin-bottom: 4px; padding: 3px; display: flex; justify-content: center;">
        <div class="card" style="padding: 4px; margin-top: 10px;">
            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin-top: 20px;" class="image">
                <?php
                $img_id = $album[0]['image_id'];
                $sql = "SELECT image_path FROM images WHERE image_id = $img_id";
                $stmt = $connection->prepare($sql);
                $stmt->execute();
                $imge = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $pic = ($imge[0]["image_path"]);
                echo "<img width='200' src='$pic' style='border-radius: 50%; overflow: hidden;'>"; ?>
                <span class="name" style="margin-top: 10px;"><?php echo $album[0]["album_name"] ?></span>
                <span class="idd">Year: <?php echo $album[0]["album_year"]; ?></span>
                <span class="idd">By:
                    <?php
                    $musician_id = $album[0]["musician_id"];
                    $musicianSql = "SELECT musician_name FROM musician WHERE musician_id = $musician_id";
                    $musicianStmt = $connection->prepare($musicianSql);
                    $musicianStmt->execute();
                    $musician = $musicianStmt->fetchAll(PDO::FETCH_ASSOC);
                    $musician_name = $musician[0]["musician_name"];
                    echo "<span class='idd'><a style='color:black;' href='showInfoMusician.php?id=$musician_id'>$musician_name</a></span>";
                    echo "<br>";
                    ?>
                </span>
                <div style="display: flex; flex-direction: row; justify-content: center; align-items: center; margin-top: 3px;">
                    <span class="number"><?php echo $album[0]["song_count"] ?> <span class="follow">Songs</span></span>
                </div>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    $checkQuery3 = "SELECT album_id FROM favorites WHERE user_id = $id";
                    $checkQueryStatement3 = $connection->prepare($checkQuery3);
                    $checkQueryStatement3->execute();
                    $checkQueryresult3 = $checkQueryStatement3->fetchAll(PDO::FETCH_ASSOC);
                    $flag3 = true;
                    foreach ($checkQueryresult3 as $key) { // checking if the element is added to favorites before
                        if ($key["album_id"] === $data) {
                            $flag3 = false;
                            echo " <span style='position: relative;'>                                        
                                            <img src='images/heartClicked.png' width = '20px'>
                                            </span>";
                        }
                    }
                    if ($flag3) { ?>
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
            <button type="button" class="collapsible">Songs</button>
            <div class="content">
                <div style="display: flex; flex-direction: row; text-align: left; margin-top: 10px; margin-bottom: 10px;">
                    <span class="idd">
                        <?php
                        $songSql = "SELECT m.music_name, m.music_id FROM music AS m
                        JOIN album AS a
                        ON a.album_id=m.album_id AND a.album_id=$data
                        WHERE m.musician_id = $musician_id";
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