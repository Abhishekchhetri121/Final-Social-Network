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
if ($_SERVER['REQUEST_METHOD'] == "POST") {


    $post = new POST();
    $id = $_SESSION['SOCIAL_userid'];
    $result = $post->create_post($id, $_POST, $_FILES);


    if ($result == "") {
        header("Location: single_post.php?id=$_GET[id]");
        die();
    } else {

        echo "<div style='text-align:center; font-size:12px; background-color:gray; color:white '>";
        echo "The following error occured<br>";
        echo $result;
        echo "</div>";
    }
}

$Post = new Post();
$row = false;
$error = "";
if (isset($_GET['id'])) {

    $row = $Post->get_single_posts($_GET['id']);
} else {
    $error = "No post was found!!";
}



?>
<html>

<head>
    <title>single | My Book</title>
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

                //checking if this is from notification
                if (isset($_GET['notif'])) {

                    notification_seen($_GET['notif']);
                }


                $User = new User();
                $image_class = new Image();
                if (is_array($row)) {

                    $row_user = $User->get_user($row['postid']);

                    //print_r($row_user);

                    if ($row['parent'] == 0) {

                        include("post.php");
                    } else {
                        $comment = $row;
                        include("comment.php");
                    }
                }
                ?>


                <?php if ($row['parent'] == 0) : ?>
                    <br style="clear:both">
                    <div id="id2">
                        <form action="" method="post" enctype="multipart/form-data">
                            <textarea name="post" placeholder="Post a comment"></textarea>
                            <input type="hidden" name="parent" value="<?php echo $row['userid'] ?>">
                            <input type="file" name="file">
                            <input type="submit" id="post_btn" value="post"><br><br>
                        </form>
                    </div>

                <?php else : ?>
                    <a href="single_post.php?id=<?php echo $row['parent'] ?>">
                        <input type="button" id="post_btn" style="position: relative;top:-18px; float:left" value="Back to main post">



                    </a>
                <?php endif; ?>
                <?php

                $post = new POST();

                $comments = $post->get_comments($row['userid']);

                if (is_array($comments)) {
                    foreach ($comments as $comment) {

                        $row_user = $User->get_user($comment['postid']);
                        //print_r($row_user);
                        include('comment.php');
                    }
                }
                //get current url
                $pg = paination_link();
                ?>

                <?php if ($row['parent'] == 0) : ?>


                    <a href="<?= $pg['next_page'] ?>">
                        <input type="button" id="post_btn" value="Next Page" style="position:relative; top:40px; cursor:pointer;"><br><br>
                    </a>
                    <a href="<?= $pg['prev_page'] ?>">
                        <input type="button" id="post_btn" value="Prev Page" style="float:left;"><br><br>
                    </a>

                <?php endif; ?>

            </div>

        </div>
    </div>
    </div>
</body>

</html>