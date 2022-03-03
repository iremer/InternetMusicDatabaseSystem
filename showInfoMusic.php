<?php
include("databaseConnection.php");
include("sessionGetUser.php");

$data = $_GET['id'];
$qry = "SELECT * FROM music WHERE music_id = $data";
$stmnt = $connection->prepare($qry);
$stmnt->execute();
$msc = $stmnt->fetchAll(PDO::FETCH_ASSOC);
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
                $video_id = $msc[0]['video_id'];
                $sql = "SELECT video_path FROM video WHERE video_id = $video_id";
                $stmt = $connection->prepare($sql);
                $stmt->execute();
                $video = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $vid = ($video[0]["video_path"]);
                echo "$vid"; ?>
                <span class="name" style="margin-top: 10px;"><?php echo $msc[0]["music_name"] ?></span>
                <span class="idd">Length:
                    <?php
                    $length = explode(":", $msc[0]["length"]);
                    echo  $length[0] . ":" . $length[1]; ?></span>
                <!--in database we mistyped the lengths so just showing the needed parts-->

                <span class="idd">Genre: <?php echo $msc[0]["genre"] ?></span>
                <?php if ($msc[0]["album_id"] !== NULL) { ?>
                    <span class="idd">Album: <span>
                            <?php
                            $album_id = $msc[0]["album_id"];
                            $qry5 = "SELECT album_name FROM album WHERE album_id = $album_id";
                            $stmnt5 = $connection->prepare($qry5);
                            $stmnt5->execute();
                            $albm = $stmnt5->fetchAll(PDO::FETCH_ASSOC);
                            if (!empty($albm)) {
                                $albm = $albm[0]["album_name"];
                                echo $albm;
                            } ?>
                        </span></span>
                <?php } ?>
                <span class="idd">By: <span>
                        <?php
                        $mscn_id = $msc[0]["musician_id"];
                        $qry6 = "SELECT musician_name FROM musician WHERE musician_id = $mscn_id";
                        $stmnt6 = $connection->prepare($qry6);
                        $stmnt6->execute();
                        $mscn = $stmnt6->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($mscn)) {
                            $mscn = $mscn[0]["musician_name"];
                            echo "<span class='idd'><a style='color:black;' href='showInfoMusician.php?id=$mscn_id'>$mscn</a></span>";
                            echo "<br>";
                        } ?>
                    </span></span>
                <?php
                if ($msc[0]["feat"] !== NULL) { ?>
                    <span class="idd">Feat: <span>
                            <?php $qry2 = "SELECT musician_id FROM featmusicians WHERE music_id = $data";
                            $stmnt2 = $connection->prepare($qry2);
                            $stmnt2->execute();
                            $feater_id = $stmnt2->fetchAll(PDO::FETCH_ASSOC);
                            $feater_id = $feater_id[0]["musician_id"];
                            $qry3 = "SELECT musician_name FROM musician WHERE musician_id = $feater_id";
                            $stmnt3 = $connection->prepare($qry3);
                            $stmnt3->execute();
                            $feat_musician = $stmnt3->fetchAll(PDO::FETCH_ASSOC);
                            if (!empty($feat_musician)) {
                                $ft = $feat_musician[0]["musician_name"];
                                echo $ft;
                            } ?>
                        </span></span>
                <?php } ?>

                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    $checkQuery2 = "SELECT music_id FROM favorites WHERE user_id = $id";
                    $checkQueryStatement2 = $connection->prepare($checkQuery2);
                    $checkQueryStatement2->execute();
                    $checkQueryresult2 = $checkQueryStatement2->fetchAll(PDO::FETCH_ASSOC);
                    $flag2 = true;
                    foreach ($checkQueryresult2 as $key) { // checking if the element is added to favorites before
                        if ($key["music_id"] === $data) {
                            $flag2 = false;
                            echo " <span style='position: relative;'>                                        
                                            <img src='images/heartClicked.png' width = '20px'>
                                            </span>";
                        }
                    }
                    if ($flag2) { ?>
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
                <button type="button" class="collapsible">Lyrics</button>
                <div class="content">
                    <div style="display: flex; flex-direction: row; justify-content: center; align-items: center; margin-top: 10px; margin-bottom: 10px;">
                        <span class="idd">
                            <?php
                            $lyrics = preg_split('/\r\n|\n|\r/', $msc[0]["lyrics"]);
                            for ($i = 0; $i < count($lyrics); $i++) {
                                echo "<br>";
                                echo $lyrics[$i];
                            }
                            ?>
                        </span>
                    </div>
                </div>
                <?php
                if (!empty($msc[0]["music_info"])) { ?>
                    <button type="button" class="collapsible">Info</button>
                    <div class="content" style="margin-top: 10px; margin-bottom: 10px;">
                        <span class="idd"><?php echo $msc[0]["music_info"] ?></span>
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