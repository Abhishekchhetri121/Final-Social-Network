<div id="posts">
    <div class="txt">
        <?php //post area of home
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


            //print_r($row_user);
            //die;
            echo "<a href='profile.php?id=$row_user[userid]' style='text-decoration:none;'>" . htmlspecialchars($row_user['first_name']) . " " .         htmlspecialchars($row_user['last_name']) . "</a>";

            if ($row['is_profile']) {
                $pronoun = "his";
                if ($row_user['gender'] == "female") {
                    $pronoun = "her";
                }
                echo "<span style='color:RGB(0,0,125); font-weight:normal;'> Updated $pronoun Cover image</span>";
            }

            if ($row['is_cover']) {
                $pronoun = "his";
                if ($row_user['gender'] == "female") {
                    $pronoun = "her";
                }
                echo "<span style='color:RGB(0,0,125); font-weight:normal;'> Updated $pronoun Profile image</span>";
            }
            ?>
        </div>


        <?php echo htmlspecialchars($row['post']); //treates special character 
        ?>
        <br><br>
        <?php
        if (file_exists($row['image'])) {

            $image_class = new Image();

            $post_image = $image_class->get_thumb_post($row['image']);

            echo "<img src='$post_image' style='width:100%' />";
        }
        ?>
        <br><br>
        <?php

        $likes = "";
        //if  statements in one line parameter condition is true than like = ?? ever given



        $likes = ($row['likes'] > 0) ? "(" . $row['likes'] . ")" : "";

        /*if ($row['likes'] > 0) {
            $likes = $row['likes'];
        } else {
            $likes = "";
        }*/

        ?>
        <a onclick="like_post(event)" href="like.php?type=post&id=<?php echo $row['userid'];  ?>">Like<?php echo  $likes; ?></a>

        <?php
        $comments  = "";
        if ($row['comments'] > 0) {
            $comments = "(" . $row['comments'] . ")";
        }
        ?>
        <a href="single_post.php?id=<?php echo $row['userid'] ?>">Comment<?php echo $comments ?></a> .
        <span>
            <?php
            $time = new Time();

            //echo "<pre>";
            //echo ($row['date']);
            echo $time->get_time($row['date']);
            ?>
        </span>

        <?php
        if ($row['has_image']) {
            echo "<a href='image_view.php?id=$row[userid]'>";
            echo ". View Full Image. ";
            echo "</a>";
        }
        ?>

        <?php

        $i_liked = false;
        if (isset($_SESSION['Social_userid'])) {
            $db = new database();


            $sql = "SELECT likes FROM likes WHERE type = 'post' && contentid = '$row[userid]' LIMIT 1 ";
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


        //---id only
        echo "<a id='info_$row[userid]' href='likes.php?type=post&id=$row[userid]'>";
        if ($row['likes'] > 0) {

            echo "<br>";
            //echo "<a id='info_$row[userid]' href='likes.php?type=post&id=$row[userid]'>";
            if ($row['likes'] == 1) {

                if ($i_liked) {
                    echo "<br><div style='float:left'>You liked this posts</div>";
                } else {
                    echo "<br><div style='float:left'>1<sup>st</sup> person to liked this posts </div>";
                }
            } else {


                if ($i_liked) {
                    $text = "others";
                    if ($row['likes'] - 1 == 1) {
                        $text = "other";
                    }
                    echo "<br><div style='float:left'>You and " . ($row['likes'] - 1) . "$text  liked this posts</div>";
                } else {
                    echo "<br><div style='float:left'>" . $row['likes'] . " other liked this posts</div>";
                }
            }
        }
        echo "</a>";
        ?>
    </div>
</div>

<script type="text/javascript">
    function ajax_send(data, element) {

        //alert(result);
        //XMLRequest : 
        var ajax = new XMLHttpRequest();

        ajax.addEventListener('readystatechange', function() {

            // 200 means ok eg 404 error means server didn't found file and like 404 200 means all good
            if (ajax.readyState == 4 && ajax.status == 200) {

                response(ajax.responseText, element);

            }

        });
        data = JSON.stringify(data);

        //readstatechange a trigger 
        ajax.open("post", "ajax.php", true); //
        ajax.send(data);


    }

    function response(result, element) {


        // alert(result);


        //alert(result);
        if (result != "") {
            var obj = JSON.parse(result);

            if (typeof obj.action != 'undefined') {

                if (obj.action == "like_post") {

                    var likes = "";

                    if (typeof obj.likes != 'undefined') {
                        likes = (parseInt(obj.likes) > 0) ? "Like(" + obj.likes + ")" : "Like";
                        element.innerHTML = likes;
                    }
                    //2 //only
                    if (typeof obj.info != 'undefined') {
                        var info_element = document.getElementById(obj.id);
                        info_element.innerHTML = obj.info;
                    }

                }
            }
        }
    }

    function like_post(e) {

        e.preventDefault();
        var link = e.target.href;


        var data = {};
        data.link = link;
        data.action = "like_post";
        ajax_send(data, e.target);
        //alert(data);

    }
</script>