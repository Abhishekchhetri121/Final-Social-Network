<?php

include("../classes/loader.php");

$login = new Login();
$user_data = $login->check_login($_SESSION["SOCIAL_userid"]);

$USER = $user_data;

$profile = new Profile();
if (isset($_GET['id']) and is_numeric($_GET['id'])) {
    $profile_data = $profile->get_profile($_GET['id']);


    if (is_array($profile_data)) {
        $user_data = $profile_data[0];
    }
}

$error = "";
$Post = new Post();

if (isset($_GET['id'])) {
    $row = $Post->get_single_posts($_GET['id']);
    if (!$row) {

        $error = "No such posts found!!";
    } else {
        if ($row['postid'] !=  $_SESSION['SOCIAL_userid'] and  $_GET['status'] != "Admin") {

            $error = "Access denied!!";
        }
    }
} else {
    $error = "No such posts found!!";
}

$_SESSION['return_to'] = "profile.php";
if (isset($_SERVER["HTTP_REFERER"]) && !strstr($_SERVER["HTTP_REFERER"], "edit.php")) {
    $_SESSION['return_to'] = $_SERVER["HTTP_REFERER"];
}
// if something was posted
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $Post->edit_post($_POST, $_FILES);


    header("Location: " . $_SESSION['return_to']);
    die();
}



?>
<html>

<head>
    <title>Delete | My Book</title>
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
                <form action="" method="post" enctype="multipart/form-data">



                    <?php

                    if ($error != "") {
                        echo $error;
                    } else {


                        echo "Are you sure you wanna edit this post?<br><br>";

                        echo '
                        <textarea name="post" placeholder="Whats on Your mind?">' . $row['post'] . '</textarea>
                        <input type="file" name="file">';



                        if (file_exists($row['image'])) {

                            $image_class = new Image();
                            $post_image = $image_class->get_thumb_post($row['image']);

                            echo "<img src='$post_image' style='width:50%' />";
                        }

                        echo "
                        <input type='hidden' name='postid' value='$row[userid]'>";
                        echo "<div style = 'text-align:center'><br> <input type='submit' id='post_btn' value='save'></div><br><br>";
                    }
                    ?>

                </form>
            </div>

        </div>
    </div>
    </div>
</body>

</html>