<?php

include("../classes/loader.php");

$login = new Login();
$user_data = $login->check_login($_SESSION["SOCIAL_userid"]);

$USER = $user_data;

$profile = new Profile();
//echo $_GET['id'] . " " . $_GET['type'];
if (isset($_GET['id']) and is_numeric($_GET['id'])) {
    $profile_data = $profile->get_profile($_GET['id']);


    if (is_array($profile_data)) {
        $user_data = $profile_data[0];
    }
}

$Post = new Post();
$likes = false;
$error = "";
if (isset($_GET['id']) && isset($_GET['type'])) {

    $likes = $Post->get_likes($_GET['id'], $_GET['type']);
} else {
    $error = "No data was found!!";
}



?>
<html>

<head>
    <title>People | My Book</title>
    <link rel="stylesheet" href="../css/p.css">
    <link rel="stylesheet" href="../css/pp.css?v=1">
</head>

<body>
    <!----nav bar -->
    <?php include('header.php'); ?>
    <!----cover area--->
    <div id="container">
        <!-----below - cover--->

        <!---posts area--->
        <div id="d2">
            <div id="id2">
                <?php


                $User = new User();
                $image_class = new Image();

                if (is_array($likes)) {
                    foreach ($likes as $lirow) {

                        $row = $User->get_user($lirow['userid']);
                        include("user.php");
                    }
                }
                ?>
                <br style="clear:both">
            </div>

        </div>
    </div>
    </div>
</body>

</html>