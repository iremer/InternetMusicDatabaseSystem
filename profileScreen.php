<?php
include("databaseConnection.php");
include("sessionGetUser.php");
?>

<!DOCTYPE html>
<html lang="en">
<style>
    a:link {
        color: black;
        background-color: transparent;
        text-decoration: none;
    }

    a:visited {
        color: black;
        background-color: transparent;
        text-decoration: none
    }
</style>

<head>
    <title>Profile</title>
    <link rel="stylesheet" href="styleCentralizedLogo.css">
    <link rel="stylesheet" href="styleBackButton.css">
    <link rel="stylesheet" href="styleTabs.css">
</head>

<body style="background-color:rgb(0, 0, 0);">
    <div class="centralized">
        <button class="btn-grad" style="float: left; margin-left: 12px; margin-top: 20px;" onclick="history.back()"><span>Back</span></button>
        <div style="float: right; margin-right: 12px; margin-top: 20px;">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { // logged in
                echo "<label for='userWelcome' style = 'color: wheat;'>$userNme | </label>";
            } ?>
            <button class="btn-grad" onclick="window.open('logout.php', '_self')"><span>Logout</span></button>
        </div>
        <img src="images/Logo_HQ.gif" width="100" style="margin-left: auto; margin-right: auto; display: flex;">
        <hr>

        <div>
            <button type="button" class="collapsible">Favorite Songs</button>
            <div class="content">
                <div style="display: flex; flex-direction: row; text-align: left; margin-top: 10px; margin-bottom: 10px;">
                    <span class="idd">
                        <?php
                        $i = 1;
                        $checkQuery = "SELECT music_id FROM favorites WHERE user_id = $id";
                        $checkQueryStatement = $connection->prepare($checkQuery);
                        $checkQueryStatement->execute();
                        $checkQueryresult = $checkQueryStatement->fetchAll(PDO::FETCH_ASSOC);
                        $flag1 = false; //checks if data exists
                        if (!empty($checkQueryresult)) { ?>
                            <table>
                                <tbody>
                                    <?php
                                    foreach ($checkQueryresult as $key) {
                                        $msc_id = $key["music_id"];
                                        if ($msc_id !== NULL) {
                                            $flag1 = true;
                                            $q1 = "SELECT music_name, music_id FROM music WHERE music_id = $msc_id";
                                            $s1 = $connection->prepare($q1);
                                            $s1->execute();
                                            $r = $s1->fetchAll(PDO::FETCH_ASSOC);
                                            $msc_nme = $r[0]["music_name"]; ?>
                                            <tr>
                                                <?php
                                                if ($i % 2 === 0) {
                                                    echo "<td style='background-color:gray;'>$i - <a href='showInfoMusic.php?id=$msc_id'>$msc_nme</a></td>";
                                                } else {
                                                    echo "<td> $i - <a href='showInfoMusic.php?id=$msc_id'>$msc_nme</a></td>";
                                                }
                                                $i++; ?>
                                            </tr>
                                    <?php
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        <?php
                        }
                        if (!$flag1) echo "<p>You haven't add a favorite yet!</p>";
                        ?>
                    </span>
                </div>
            </div>

            <button type="button" class="collapsible">Favorite Albums</button>
            <div class="content">
                <div style="display: flex; flex-direction: row; text-align: left; margin-top: 10px; margin-bottom: 10px;">
                    <span class="idd">
                        <?php
                        $i = 1;
                        $checkQuery2 = "SELECT album_id FROM favorites WHERE user_id = $id";
                        $checkQueryStatement2 = $connection->prepare($checkQuery2);
                        $checkQueryStatement2->execute();
                        $checkQueryresult2 = $checkQueryStatement2->fetchAll(PDO::FETCH_ASSOC);
                        $flag2 = false; //checks if data exists
                        if (!empty($checkQueryresult2)) { ?>
                            <table>
                                <tbody>
                                    <?php
                                    foreach ($checkQueryresult2 as $key) {
                                        $albm_id = $key["album_id"];
                                        if ($albm_id !== NULL) {
                                            $flag2 = true;
                                            $q2 = "SELECT album_name FROM album WHERE album_id = $albm_id";
                                            $s2 = $connection->prepare($q2);
                                            $s2->execute();
                                            $r2 = $s2->fetchAll(PDO::FETCH_ASSOC);
                                            $albm_nme = $r2[0]["album_name"]; ?>
                                            <tr>
                                                <?php
                                                if ($i % 2 === 0) {
                                                    echo "<td style='background-color:gray;'>$i - <a href='showInfoAlbum.php?id=$albm_id'>$albm_nme</a></td>";
                                                } else {
                                                    echo "<td>$i - <a href='showInfoAlbum.php?id=$albm_id'>$albm_nme</a></td>";
                                                }
                                                $i++; ?>
                                            </tr>
                                    <?php
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        <?php
                        }
                        if (!$flag2) echo "<p>You haven't add a favorite yet!</p>";
                        ?>
                    </span>
                </div>
            </div>

            <button type="button" class="collapsible">Favorite Musicians</button>
            <div class="content">
                <div style="display: flex; flex-direction: row; text-align: left; margin-top: 10px; margin-bottom: 10px;">
                    <span class="idd">
                        <?php
                        $i = 1;
                        $checkQuery3 = "SELECT musician_id FROM favorites WHERE user_id = $id";
                        $checkQueryStatement3 = $connection->prepare($checkQuery3);
                        $checkQueryStatement3->execute();
                        $checkQueryresult3 = $checkQueryStatement3->fetchAll(PDO::FETCH_ASSOC);
                        $flag3 = false; //checks if data exists
                        if (!empty($checkQueryresult3)) { ?>
                            <table>
                                <tbody>
                                    <?php
                                    foreach ($checkQueryresult3 as $key) {
                                        $mscn_id = $key["musician_id"];
                                        if ($mscn_id !== NULL) {
                                            $flag3 = true;
                                            $q3 = "SELECT musician_name FROM musician WHERE musician_id = $mscn_id";
                                            $s3 = $connection->prepare($q3);
                                            $s3->execute();
                                            $r3 = $s3->fetchAll(PDO::FETCH_ASSOC);
                                            $mscn_nme = $r3[0]["musician_name"]; ?>
                                            <tr>
                                                <?php
                                                if ($i % 2 === 0) {
                                                    echo "<td style='background-color:gray;'> $i - <a href='showInfoMusician.php?id=$mscn_id'>$mscn_nme</a></td>";
                                                } else {
                                                    echo "<td> $i - <a href='showInfoMusician.php?id=$mscn_id'>$mscn_nme</a></td>";
                                                }
                                                $i++; ?>
                                            </tr>
                                    <?php
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        <?php
                        }
                        if (!$flag3) echo "<p>You haven't add a favorite yet!</p>";
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