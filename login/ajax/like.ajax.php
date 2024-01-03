<?php

//$URL = split_url_from_string($data->link);
//$_GET['type'] = isset($URL[6]) ? $URL[6] : null;
//$_GET['id'] = isset($URL[7]) ? $URL[7] : null;


//include("../classes/loader.php");
$_SESSION["SOCIAL_userid"] = isset($_SESSION["SOCIAL_userid"]) ? $_SESSION["SOCIAL_userid"] : 0;
$login = new Login();
$user_data = $login->check_login($_SESSION['SOCIAL_userid'], false);
//$login = new Login();
//check if not logged in 
if ($_SESSION["SOCIAL_userid"] == 0) {

    $obj = (object)[];


    $obj->action = "like_post";



    echo json_encode($obj);
    die;
}
if (!isset($_SESSION["SOCIAL_userid"])) {
    die;
}

$query_string = explode("?", $data->link);
$query_string = end($query_string);



$str = explode("&", $query_string);


foreach ($str as $value) {

    $value = explode("=", $value);
    $_GET[$value[0]] = $value[1];
}

$_GET['id'] = addslashes($_GET['id']);

$_GET['type'] = addslashes($_GET['type']);




$post = new POST();

if (isset($_GET['type']) && isset($_GET['id'])) {
    if (is_numeric($_GET["id"])) {

        $allowed[] = "post";
        $allowed[] = "user";
        $allowed[] = "comment";
        //$allowed[] = "like";


        if (in_array($_GET['type'], $allowed)) {


            $user_class = new User();
            $post->likes_post($_GET['id'], $_GET['type'], $_SESSION["SOCIAL_userid"]);

            //$single_post = $post->get_single_posts($_GET['id']);



            if ($_GET['type'] == "user") {
                $user_class->follow_user($_GET['id'], $_GET['type'], $_SESSION["SOCIAL_userid"]);

                //$single_post = $user_class->get_user($_GET['id']);
            }




            //  add notification

            //add_notification($_SESSION['SOCIAL_userid'], "following", $single_post);
        }
    }
    //read likes
    $likes = $post->get_likes($_GET['id'], $_GET['type']);

    // create info

    //////////////////
    //begins from here

    $likes = array();
    $info = "";
    $i_liked = false;
    if (isset($_SESSION['Social_userid'])) {
        $db = new database();


        $sql = "SELECT likes FROM likes WHERE type = 'post' && contentid = '$_GET[id]' LIMIT 7 ";
        $result = $db->read($sql);

        if (is_array($result)) {
            //already liked posts ar eny other
            $likes = json_decode($result[0]['likes'], true);

            $user_ids = array_column($likes, "userid");


            if (in_array($_SESSION['SOCIAL_userid'], $user_ids)) {
                $i_liked = true;
            }
        }
    }


    $like_count = count($likes);

    if ($like_count > 0) {


        $info .= "<br>";

        if ($like_count == 7) {

            if ($i_liked) {
                $info .= "<br><div style='float:left'>You liked this posts</div>";
            } else {
                $info .= "<br><div style='float:left'>7<sup>st</sup> person to like this posts </div>";
            }
        } else {


            if ($i_liked) {
                $text = "others";
                if ($like_count - 7 == 7) {
                    $text = "other";
                }
                $info .= "<br><div style='float:left'>You and " . ($like_count - 7) . "$text  liked this posts</div>";
            } else {
                $info .= "<br><div style='float:left'>" . $like_count . " other liked this posts</div>";
            }
        }
    }
    //till here likes related
    //////////////////////////
    //$likes = $post->get_likes($_GET['id'], $_GET['type']);
    $obj = (object)[];

    $obj->likes = count($likes);
    $obj->action = "like_post";
    $obj->info = "$info";
    $obj->id = "info_$_GET[id]";

    echo json_encode($obj);
}
