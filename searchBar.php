<?php include("sessionGetUser.php"); ?>
<div style="background-color: black!important;">
    <img src="images/Logo_HQ.gif" width="80" style="float: left;">
    <div style="float: right; margin-right: 12px; margin-top: 10px;">
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { // logged in
            echo "<button class='btn-grad' style='margin-left: 3px;' id = 'profileButton'><span> $userNme </span></button>";
        } ?>
        <button class="btn-grad" style="margin-left: 3px;" onclick="window.open('mainScreen.php','_self')"><span>Home</span></button>

    </div>
    <form id="queryForm">
        <input type="text" autocomplete="off" placeholder="Search for musics, albums, musicians on I-MuuDS" name="q" onkeypress="checkQuery()" style="background-color:rgb(0, 0, 0); border: solid 1px #5e5e5ebe; height: 25px; width: 300px; font-size:12px; color:rgb(248, 212, 112);
            border-radius: 15px; margin-top: 10px; padding-left: 15px; margin-left: 15px;">
        <script>
            function checkQuery() {
                // checking if the entered value is empty or not
                let words = document.getElementsByName("q")[0];
                if (words.value !== "" && words.value !== " " && words.value !== "+") {
                    let validation = document.getElementById("queryForm");
                    validation.action = "showQueryPagination.php";
                    validation.method = "GET";
                    validation.target = "_self";
                }
            }
        </script>
        <script>
            document.getElementById("profileButton").onclick = function() {
                window.open("profileScreen.php?id=$id","_self");
            }
        </script>
    </form>
    <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #ccc; margin: 1em 0; padding: 0; border-color: #3b3935;">
</div>