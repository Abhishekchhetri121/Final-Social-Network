<?php


$actor = $User->get_user($notif_row['userid']);
$owner = $User->get_user($notif_row['content_owner']);
$id = esc($_SESSION["SOCIAL_userid"]);



$link = "";


if ($notif_row['content_type'] == "post") {

    $link = "single_post.php?id=" . $notif_row['contentid'] . "&notif=" . $notif_row['id'];
} else
if ($notif_row['content_type'] == "profile") {
    $link = "profile.php?id=" . $notif_row['userid'] . "&notif=" . $notif_row['id'];
}

if ($notif_row['content_type'] == "notice") {
    $link = "single_post.php?id=" . $notif_row['userid'] . "&notif=" . $notif_row['id'];
}

if ($notif_row['content_type'] == "comment") {
    $link = "single_post.php?id=" . $notif_row['contentid'] . "&notif=" . $notif_row['id'];

    //checking notification seen or not
}
$db = new database();
//$notification_id = $notif_row['id'];
//$userid = esc($_SESSION["SOCIAL_userid"]);

$query = "SELECT * FROM notification_seen WHERE userid = '$id' and notification_id = '$notif_row[id]' LIMIT 1";

$seen = $db->read($query);

//    print_r($seen);
$color = "";
if (is_array($seen)) {
    $color = "#eee";
} else {
    $color = "#dfcccc";
}

//$color = "#dfcccc";
?>
<a href="<?php echo $link; ?>" style="text-decoration:none">
    <div id="notifications" style="background-color: <?= $color ?>">

        <?php



        if (is_array($actor) and is_array($owner)) {

            $image = "../img/user_male.jpg";

            if ($actor['gender'] == "female") {
                $image = "../img/user_female.jpg";
            }

            if (file_exists($actor['profile_image'])) {

                //$image_class = new Image();
                $image = $image_class->get_thumb_profile($actor['profile_image']);
            }

            echo "<img src=$image style='width:36px; margin:4px; float:left' />";



            if ($actor['userid'] != $id) {
                echo $actor['first_name'] . " " . $actor['last_name'];
            } else {
                echo "You ";
            }
            if ($notif_row['activity'] == "like") {
                echo " liked ";
            } else
            if ($notif_row['activity'] == "following") {
                echo " started following ";
            } else
            if ($notif_row['activity'] == "comment") {
                echo " commented ";
            } else
            if ($notif_row['activity'] == "notice") {
                echo " posted  ";
            } else
            if ($notif_row['activity'] == "tag") {
                echo " tagged ";
            }




            //echo $owner['userid'] . "<br>" . $id;


            if ($owner['userid'] != $id and $notif_row['activity'] != "tag" and  $notif_row['activity'] != "notice") {
                echo $owner['first_name'] . " " . $owner['last_name'] . "'s ";
            } elseif ($notif_row['activity'] == "tag") {

                echo " you in a ";
            } elseif ($notif_row['activity'] == "notice") {

                echo "";
            } else {
                echo " your ";
            }



            $content_row = $post->get_single_posts($notif_row['contentid']);


            //posting starts it can be image or post 
            if ($notif_row['content_type'] == "post") {

                //image posts starting
                if ($content_row['has_image']) {
                    echo "image";

                    if (file_exists($content_row['image'])) {

                        $post_image = $image_class->get_thumb_post($content_row['image']);

                        echo "<img src='$post_image' style='width:40px; float:right' />";
                    }
                }
                //image post complete 
                //text posts starts here
                //new added for notice
                elseif ($notif_row['activity'] == "notice") {


                    echo $notif_row['activity'];


                    echo "
                    <span style='float:right; color:RGB(0,0,255);margin:4px'>'" . htmlspecialchars(substr($content_row['post'], 0, 50)) . "'</span>
            ";
                } //notice complete
                else {




                    echo $notif_row['content_type'];


                    echo "
                    <span style='float:right; color:RGB(0,0,255);margin:4px'>'" . htmlspecialchars(substr($content_row['post'], 0, 50)) . "'</span>
            ";
                }
            } else {
                echo $notif_row['content_type'];
            }





            /*

            if ($notif_row['content_type'] == "post" and $notif_row['activity'] == "admin") {

                //image posts starting
                if ($content_row['has_image']) {
                    echo "image";

                    if (file_exists($content_row['image'])) {

                        $post_image = $image_class->get_thumb_post($content_row['image']);

                        echo "<img src='$post_image' style='width:40px; float:right' />";
                    }
                }
                //image post complete 
                //text posts starts here
                else {


                    echo $notif_row['content_type'];


                    echo "
                    <span style='float:right; color:RGB(0,0,255);margin:4px'>'" . htmlspecialchars(substr($content_row['post'], 0, 50)) . "'</span>
            ";
                }
            } else {
                echo $notif_row['content_type'];
            }
*/




            $date = date("jS M Y H:i:s", strtotime($notif_row['date']));
            echo "<br>

                <span style='color:RGB(0,0,255);margin:4px'>$date</span>
        ";
        }






        ?>



    </div>

</a>