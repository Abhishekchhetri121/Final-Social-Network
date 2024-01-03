<?php

include("../classes/loader.php");

$login = new Login();
$user_data = $login->check_login($_SESSION["SOCIAL_userid"]);

if (isset($_SERVER["HTTP_REFERER"])) {
    $return_to = $_SERVER["HTTP_REFERER"];
} else {

    $return_to = "profile.php";
}


if (isset($_GET['type']) && isset($_GET['id'])) {
    if (is_numeric($_GET["id"])) {

        $allowed[] = "post";
        $allowed[] = "user";
        $allowed[] = "comment";
        $allowed[] = "follower";

        $user_class = new User();
        $post = new Post();
        //    echo ($_GET['type']);

        if (in_array($_GET['type'], $allowed)) {


            $post->likes_post($_GET['id'], $_GET['type'], $_SESSION["SOCIAL_userid"]);
            $post->like_post($_GET['id'], $_GET['type'], $_SESSION["SOCIAL_userid"]);
            if ($_GET['type'] == "user") {
                $user_class->follow_user($_GET['id'], $_GET['type'], $_SESSION["SOCIAL_userid"]);
            }
        }
    }
}

header("Location: " . $return_to);
die;
