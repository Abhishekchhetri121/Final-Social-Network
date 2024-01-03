<div id="posts" style="background-color:#eee">
    <div class="txt">
        <?php //
        $image = "../img/user_male.jpg";

        if ($row_user['gender'] == "female") {
            $image = "../img/user_female.jpg";
        }

        if (file_exists($row_user['profile_image'])) {

            $image_class = new Image();
            $image = $image_class->get_thumb_profile($row_user['profile_image']);
        }

        ?>
        <img src="<?php echo $image; ?>" id="img_post" alt="">
    </div>
    <div style="width:100%">
        <div class="poster">


            <?php

            echo "<a href='profile.php?id=$comment[userid]' style='text-decoration:none;'>" . htmlspecialchars($row_user['first_name']) . " " .         htmlspecialchars($row_user['last_name']) . "</a>";

            if ($comment['is_profile']) {
                $pronoun = "his";
                if ($row_user['gender'] == "female") {
                    $pronoun = "her";
                }
                echo "<span style='color:RGB(0,0,125); font-weight:normal;'> Updated $pronoun Cover image</span>";
            }

            if ($comment['is_cover']) {
                $pronoun = "his";
                if ($row_user['gender'] == "female") {
                    $pronoun = "her";
                }
                echo "<span style='color:RGB(0,0,125); font-weight:normal;'> Updated $pronoun Profile image</span>";
            }
            ?>
        </div>


        <?php echo check_tags($comment['post']); //treates special character 
        ?>
        <br><br>
        <?php
        if (file_exists($comment['image'])) {

            $image_class = new Image();

            $post_image = $image_class->get_thumb_post($comment['image']);

            echo "<img src='$post_image' style='width:100%' />";
        }
        ?>
        <br><br>
        <?php

        $likes = "";
        //if  statements in one line parameter condition is true than like = ?? ever given

        $likes = ($comment['likes'] > 0) ? "(" . $comment['likes'] . ")" : "";
        /*if ($comment['likes'] > 0) {
            $likes = $comment['likes'];
        } else {
            $likes = "";
        }*/
        ?>
        <a href="like.php?type=post&id=<?php echo $comment['userid'];  ?>">Like<?php echo  $likes; ?></a> .

        <!---<a href="single_post.php?id=<?php //echo $comment['userid'] 
                                            ?>">Comment</a> .--->
        <span>
            <?php
            $time = new Time();
            echo $time->get_time($comment['date']); ?>
        </span>

        <?php
        if ($comment['has_image']) {
            echo "<a href='image_view.php?id=$comment[userid]'>";
            echo ". View Full Image. ";
            echo "</a>";
        }
        ?>
        <span style="float:right">
            <?php

            //print_r($comment);
            $post = new Post();
            if ($post->owned_post($comment['userid'], $_SESSION['SOCIAL_userid'])) {



                echo "
                <a href='edit.php?id=$comment[userid]'>
                    Edit
                </a>. ";
            }


            if (i_own_content($comment)) {
                echo "<a href='delete.php?type=del&id=$comment[userid]'>
                Delete
            </a>";
            }
            ?>
        </span>
        <?php
        $i_liked = false;
        if (isset($_SESSION['Social_userid'])) {
            $db = new database();


            $sql = "SELECT likes FROM likes WHERE type = 'post' && contentid = '$comment[userid]' LIMIT 1 ";
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



        if ($comment['likes'] > 0) {

            echo "<br>";
            echo "<a href='likes.php?type=post&id=$comment[userid]'>";
            if ($comment['likes'] == 1) {

                if ($i_liked) {
                    echo "<br><div style='float:left'>You liked this comment</div>";
                } else {
                    echo "<br><div style='float:left'>1 person liked this comment</div>";
                }
            } else {

                if ($i_liked) {
                    $text = "others";
                    if ($comment['likes'] - 1 == 1) {
                        $text = "other";
                    }
                    echo "<br><div style='float:left'>You and " . ($comment['likes'] - 1) . "$text  liked this comment</div>";
                } else {
                    echo "<br><div style='float:left'>" . $comment['likes'] . " other liked this comment</div>";
                }
            }
            echo "</a>";
        }
        ?>
    </div>
</div>