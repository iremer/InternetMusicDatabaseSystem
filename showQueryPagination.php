<?php
include("databaseConnection.php");
include("sessionGetUser.php");
$showNotFound = true; // nothing is found

if (!empty($_GET)) {
    $data = $_GET['q'];
}

if (!empty($_GET)) {
    $data = $_GET['q'];
    $musicianQuery = "SELECT * FROM musician WHERE musician_name LIKE'%$data%'";
    $musicianStatement = $connection->prepare($musicianQuery);
    $musicianStatement->execute();
    $musicianResult = $musicianStatement->fetchAll(PDO::FETCH_ASSOC);
    $musician_count = count($musicianResult);
    $defaultMusician = count($musicianResult);
    if (isset($_GET['mscn']))
        $writtenMusician = $_GET['mscn'];
    else
        $writtenMusician = $musician_count;
}

if (!empty($_GET)) {
    $data = $_GET['q'];
    $musicQuery = "SELECT * FROM music WHERE music_name LIKE'%$data%'";
    $musicStatement = $connection->prepare($musicQuery);
    $musicStatement->execute();
    $musicResult = $musicStatement->fetchAll(PDO::FETCH_ASSOC);
    $music_count = count($musicResult);
    $defaultMusic = count($musicResult);
    if (isset($_GET['msc']))
        $writtenMusic = $_GET['msc'];
    else
        $writtenMusic = $music_count;
}

if (!empty($_GET)) {
    $data = $_GET['q'];
    $albumQuery = "SELECT * FROM album WHERE album_name LIKE'%$data%'";
    $albumStatement = $connection->prepare($albumQuery);
    $albumStatement->execute();
    $albumResult = $albumStatement->fetchAll(PDO::FETCH_ASSOC);
    $album_count = count($albumResult);
    $defaultAlbum = count($albumResult);
    if (isset($_GET['albm']))
        $writtenAlbum = $_GET['albm'];
    else
        $writtenAlbum = $album_count;
}

if (!empty($data)) {
    $sql = "SELECT M.music_id, M.music_name, M.length FROM music as M
    WHERE M.music_name LIKE '%$data%'
    UNION
    SELECT Mn.musician_id, Mn.musician_name, Mn.image_id FROM musician as Mn
    WHERE Mn.musician_name LIKE '%$data%'
    UNION
    SELECT A.album_id, A.album_name, A.image_id FROM album as A
    WHERE A.album_name LIKE '%$data%'";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results_per_page = 15;
    $number_of_total_data = count($resultSet);
    if ($number_of_total_data > 0) {
        $showNotFound = false; //some data has found
    }
    $number_of_pages = ceil($number_of_total_data / $results_per_page);
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }
    $starting_limit_number = ($page - 1) * $results_per_page;

    $sqlMain = "SELECT Mn.musician_id, Mn.musician_name, Mn.image_id FROM musician as Mn
    WHERE Mn.musician_name LIKE '%$data%'
    UNION
    SELECT M.music_id, M.music_name, M.length FROM music as M
    WHERE M.music_name LIKE '%$data%'
    UNION
    SELECT A.album_id, A.album_name, A.image_id FROM album as A
    WHERE A.album_name LIKE '%$data%'
    LIMIT " . $starting_limit_number . ',' . $results_per_page;
    $stmtMain = $connection->prepare($sqlMain);
    $stmtMain->execute();
    $dataSet = $stmtMain->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="styleQuery.css">
    <link rel="stylesheet" href="styleBackButton.css">
    <link rel="stylesheet" href="stylePagination.css">
</head>
<style>
    tr.bordered td {
        /* set border style for separated rows */
        border-bottom: 1px solid black;
        overflow: hidden;
    }

    table {
        /* make the border continuous (without gaps between columns) */
        border-collapse: collapse;
        page-break-after: auto
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto
    }

    td {
        page-break-inside: avoid;
        page-break-after: auto
    }

    thead {
        display: table-header-group
    }

    tfoot {
        display: table-footer-group
    }
</style>

<body style="background-color: black;">
    <?php include("searchBar.php"); ?>
    <div style="background-color: black!important;">
        <table style="background-color: #d4c77350; border-radius: 17px; color: #99895a; margin-left: auto; margin-right: auto; min-width: 950px; vertical-align: middle; margin-bottom: 10px;">
            <thead>
                <?php if (!$showNotFound) { ?>
                    <tr>
                        <th style="float: left; margin-left: 50px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Showing the most relevant results:</th>
                        <th style="text-align: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Click to add to your favorites</th>
                    </tr>
                <?php } ?>
            </thead>
            <tbody>
                <?php
                if (!empty($data) && !empty($dataSet)) {
                    $i = 0;
                    while ($writtenMusician > 0 && $i < $results_per_page) {
                        $rslt = $dataSet[$i]; ?>
                        <tr style="vertical-align: middle; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">
                            <td style="margin-bottom: -1px; align-items: center; padding: 10px 0; text-decoration: none; display: flex;">
                                <div style="margin-left: 50px; margin-right: 20px;">
                                    <?php
                                    $img_id2 = $rslt['image_id'];
                                    $sql2 = "SELECT image_path FROM images WHERE image_id = $img_id2";
                                    $stmt2 = $connection->prepare($sql2);
                                    $stmt2->execute();
                                    $imge2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                                    $pic2 = ($imge2[0]["image_path"]);
                                    echo "<img width='100' style='width: 80px; height: 80px; object-fit: cover; overflow: hidden; border-radius: 50%;'
                                        src='$pic2'>";
                                    ?>
                                </div>
                                <div style="margin-bottom: 25px;">
                                    <?php $mscnName = $rslt["musician_name"];
                                    $mscn_id = $rslt["musician_id"];
                                    echo "<h5 style='margin-top: 40px;'><a href='showInfoMusician.php?id=$mscn_id'>$mscnName</a></h5>"; ?>
                                </div>
                            </td>
                            <td class="favoriteHover">
                                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                                    $checkQuery1 = "SELECT musician_id FROM favorites WHERE user_id = $id";
                                    $checkQueryStatement1 = $connection->prepare($checkQuery1);
                                    $checkQueryStatement1->execute();
                                    $checkQueryresult1 = $checkQueryStatement1->fetchAll(PDO::FETCH_ASSOC);
                                    $flag1 = true;
                                    foreach ($checkQueryresult1 as $key) { // checking if the element is added to favorites before
                                        if ($key["musician_id"] === $mscn_id) {
                                            $flag1 = false;
                                            echo " <span style='margin-left: 200px; position: relative; margin-bottom: 25px;'>                                        
                                            <img src='images/heartClicked.png' width = '20px'>
                                            </span>";
                                        }
                                    }
                                    if ($flag1) { ?>
                                        <span style='margin-left: 200px; position: relative; margin-bottom: 25px;'>
                                            <iframe style='display:none;' name='target'></iframe>
                                            <a href='addToFavorites.php?id=<?php echo $mscn_id; ?>' target='target'><img src='images/heart.png' onclick="alert('Succesfully added to favorites!')" ; width='20px'></a>
                                        </span>
                                    <?php // logged in 
                                    }
                                } else { // not logged in
                                    ?>
                                    <span style='margin-left: 200px; position: relative; margin-bottom: 25px;'>
                                        <img src='images/heart.png' onclick="alert('You must be logged in to add this to your favorites!')" width='20px'>
                                    </span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                        $writtenMusician--;
                        $musician_count--;
                        $i++;
                    }
                    while ($writtenMusic > 0 && $i < $results_per_page) {
                        $rslt = $dataSet[$i]; ?>
                        <tr style="vertical-align: middle; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">
                            <td style="margin-bottom: -1px; align-items: center; padding: 10px 0; text-decoration: none; display: flex;">
                                <div style="margin-left: 50px; margin-right: 20px;">
                                    <img src="images/musicIcon.png" width='100' style='width: 80px; height: 80px; object-fit: cover; overflow: hidden; border-radius: 50%;'>
                                </div>
                                <div style="margin-bottom: 25px;">
                                    <?php $mscName = $rslt["musician_name"];
                                    $msc_id = $rslt["musician_id"];
                                    echo "<h5 style='margin-top: 40px;'><a href='showInfoMusic.php?id=$msc_id'>$mscName</a></h5>"; ?>
                                </div>
                            </td>
                            <td class="favoriteHover">
                                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                                    $checkQuery2 = "SELECT music_id FROM favorites WHERE user_id = $id";
                                    $checkQueryStatement2 = $connection->prepare($checkQuery2);
                                    $checkQueryStatement2->execute();
                                    $checkQueryresult2 = $checkQueryStatement2->fetchAll(PDO::FETCH_ASSOC);
                                    $flag2 = true;
                                    foreach ($checkQueryresult2 as $key) { // checking if the element is added to favorites before
                                        if ($key["music_id"] === $msc_id) {
                                            $flag2 = false;
                                            echo " <span style='margin-left: 200px; position: relative; margin-bottom: 25px;'>                                        
                                            <img src='images/heartClicked.png' width = '20px'>
                                            </span>";
                                        }
                                    }
                                    if ($flag2) { ?>
                                        <span style='margin-left: 200px; position: relative; margin-bottom: 25px;'>
                                            <iframe style='display:none;' name='target'></iframe>
                                            <a href='addToFavorites.php?id=<?php echo $msc_id; ?>' target='target'><img src='images/heart.png' onclick="alert('Succesfully added to favorites!')" ; width='20px'></a>
                                        </span>
                                    <?php // logged in 
                                    }
                                } else { // not logged in
                                    ?>
                                    <span style='margin-left: 200px; position: relative; margin-bottom: 25px;'>
                                        <img src='images/heart.png' onclick="alert('You must be logged in to add this to your favorites!')" width='20px'>
                                    </span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                        $writtenMusic--;
                        $music_count--;

                        $i++;
                    }
                    while ($writtenAlbum > 0 && $i < $results_per_page) {
                        $rslt = $dataSet[$i]; ?>
                        <tr style="vertical-align: middle; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">
                            <td style="margin-bottom: -1px; align-items: center; padding: 10px 0; text-decoration: none; display: flex;">
                                <div style="margin-left: 50px; margin-right: 20px;">
                                    <div>
                                        <?php
                                        $img_id3 = $rslt['image_id'];
                                        $sql3 = "SELECT image_path FROM images WHERE image_id = $img_id3";
                                        $stmt3 = $connection->prepare($sql3);
                                        $stmt3->execute();
                                        $imge3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                                        $pic3 = ($imge3[0]["image_path"]);
                                        echo "<img width='100' style='width: 80px; height: 80px; object-fit: cover; overflow: hidden; border-radius: 50%;'
                                        src='$pic3'>";
                                        ?>
                                    </div>
                                </div>
                                <div style="margin-bottom: 25px;">
                                    <?php $albumName = $rslt["musician_name"];
                                    $albm_id = $rslt["musician_id"];
                                    echo "<h5 style='margin-top: 40px;'><a href='showInfoAlbum.php?id=$albm_id'>$albumName</a></h5>"; ?>
                                </div>
                            </td>
                            <td class="favoriteHover">
                                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                                    $checkQuery3 = "SELECT album_id FROM favorites WHERE user_id = $id";
                                    $checkQueryStatement3 = $connection->prepare($checkQuery3);
                                    $checkQueryStatement3->execute();
                                    $checkQueryresult3 = $checkQueryStatement3->fetchAll(PDO::FETCH_ASSOC);
                                    $flag3 = true;
                                    foreach ($checkQueryresult3 as $key) { // checking if the element is added to favorites before
                                        if ($key["album_id"] === $albm_id) {
                                            $flag3 = false;
                                            echo " <span style='margin-left: 200px; position: relative; margin-bottom: 25px;'>                                        
                                            <img src='images/heartClicked.png' width = '20px'>
                                            </span>";
                                        }
                                    }
                                    if ($flag3) { ?>
                                        <span style='margin-left: 200px; position: relative; margin-bottom: 25px;'>
                                            <iframe style='display:none;' name='target'></iframe>
                                            <a href='addToFavorites.php?id=<?php echo $albm_id; ?>' target='target'><img src='images/heart.png' onclick="alert('Succesfully added to favorites!')" ; width='20px'></a>
                                        </span>
                                    <?php // logged in 
                                    }
                                } else { // not logged in
                                    ?>
                                    <span style='margin-left: 200px; position: relative; margin-bottom: 25px;'>
                                        <img src='images/heart.png' onclick="alert('You must be logged in to add this to your favorites!')" width='20px'>
                                    </span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                        $writtenAlbum--;
                        $album_count--;
                        $i++;
                    } ?>
            </tbody>
        </table>
    <?php }
                if ($showNotFound) { ?>
        <h5 style="padding-left: 15px; padding-top: 30px; text-align: center; background-color: #d4c77350; border-radius: 4px; color: #99895a; width: 50px;
  height: 50px; margin-left: auto; margin-right: auto; min-width: 950px; vertical-align: middle; margin-bottom: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Sorry, we couldn't find what you're looking for...</h5>
    <?php } ?>
    <div class="center">
        <div class="pagination">
            <?php
            if (!empty($data) && !$showNotFound) {
                echo '<a href="showQueryPagination.php?q=' . $data . '&page=1'  .  '&mscn=' . $defaultMusician .  '&msc=' . $defaultMusic .  '&albm=' . $defaultAlbum . '">&laquo; Back to first</a>';
                for ($pageNum = 1; $pageNum <= $number_of_pages; $pageNum++) {
                    if (($pageNum-$page)!=1||($pageNum-$page)==0) {
                        echo '<a class="active">' . $pageNum . '</a>';
                    } else {
                        echo '<a href="showQueryPagination.php?q=' . $data . '&page=' . $pageNum .  '&mscn=' . min($musician_count, $writtenMusician) .  '&msc=' . min($music_count, $writtenMusic) .  '&albm=' . min($album_count, $writtenAlbum) . '">' . $pageNum . '</a>';
                    }
                }
            }
            ?>
        </div>
    </div>
    </div>
</body>

</html>