<?php
$color = "#eee";

if (check_seen_threads($message['msgid']) > 0) {
    $color = "#e1d3d3";
}

?>
<div id="message_thread" style="background-color: <?= $color ?>;">
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

        <img src="<?php echo  $image; ?>" id="img_post" alt="">
    </div>
    <div style="width:100%">
        <div class="poster">


            <?php

            echo "<a href='profile.php?id=$message[msgid]' style='text-decoration:none; color:black;'>" . htmlspecialchars($row_user['first_name']) . " " .         htmlspecialchars($row_user['last_name']) . "</a>";


            ?>
        </div>
        <br>


        <?php echo check_tags($message['message']); //treates special character 
        ?>

        <?php
        if (file_exists($message['file'])) {

            $image_class = new Image();

            $post_image = $image_class->get_thumb_post($message['file']);

            echo "<img src='$post_image' style='width:60px' />&nbsp";
        }
        ?>
        <br><br>

        <span>
            <?php
            $time = new Time();
            echo $time->get_time($message['date']); ?>
        </span>



    </div>


    <div id="ribbon">


        <a href="messages.php?type=read&id=<?= $myid; ?>">

            <img src="../img/iconmonstr-care-right-thin-240.png" alt="" height="26" width="26" style="position: relative; top:20px; left:22px; ">
        </a>
    </div>
</div>