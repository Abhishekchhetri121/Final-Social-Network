<div id="message_left">
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

            echo "<a href='profile.php?id=$message[msgid]' style='text-decoration:none; color:white;'>" . htmlspecialchars($row_user['first_name']) . " " .         htmlspecialchars($row_user['last_name']) . "</a>";


            ?>
        </div>
        <br>


        <?php echo check_tags($message['message']); //treates special character 
        ?>

        <?php
        if (file_exists($message['file'])) {

            $image_class = new Image();

            $post_image = $image_class->get_thumb_post($message['file']);

            echo "<img src='$post_image' style='width:100%' />&nbsp";
        }
        ?>
        <br><br>

        <span>
            <?php
            $time = new Time();
            echo $time->get_time($message['date']); ?>
        </span>

        <?php
        if (file_exists($message['file'])) {
            echo "<a href='image_view.php?type=msg&id=$message[id]'>";
            echo ". View Full Image. ";
            echo "</a>";
        }
        ?>
        <span style="float:right">
            <?php

            //print_r($message);
            $post = new Post();



            echo "<a href='delete.php?type=msg&id=$message[id]' >";

            echo '
            <img src="data:image/svg+xml;base64,PHN2ZyBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGZpbGwtcnVsZT0iZXZlbm9kZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgc3Ryb2tlLW1pdGVybGltaXQ9IjIiIHZpZXdCb3g9IjAgMCAyNCAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJtMTIuMDAyIDIuMDA1YzUuNTE4IDAgOS45OTggNC40OCA5Ljk5OCA5Ljk5NyAwIDUuNTE4LTQuNDggOS45OTgtOS45OTggOS45OTgtNS41MTcgMC05Ljk5Ny00LjQ4LTkuOTk3LTkuOTk4IDAtNS41MTcgNC40OC05Ljk5NyA5Ljk5Ny05Ljk5N3ptMCA4LjkzMy0yLjcyMS0yLjcyMmMtLjE0Ni0uMTQ2LS4zMzktLjIxOS0uNTMxLS4yMTktLjQwNCAwLS43NS4zMjQtLjc1Ljc0OSAwIC4xOTMuMDczLjM4NC4yMTkuNTMxbDIuNzIyIDIuNzIyLTIuNzI4IDIuNzI4Yy0uMTQ3LjE0Ny0uMjIuMzQtLjIyLjUzMSAwIC40MjcuMzUuNzUuNzUxLjc1LjE5MiAwIC4zODQtLjA3My41My0uMjE5bDIuNzI4LTIuNzI4IDIuNzI5IDIuNzI4Yy4xNDYuMTQ2LjMzOC4yMTkuNTMuMjE5LjQwMSAwIC43NS0uMzIzLjc1LS43NSAwLS4xOTEtLjA3My0uMzg0LS4yMi0uNTMxbC0yLjcyNy0yLjcyOCAyLjcxNy0yLjcxN2MuMTQ2LS4xNDcuMjE5LS4zMzguMjE5LS41MzEgMC0uNDI1LS4zNDYtLjc1LS43NS0uNzUtLjE5MiAwLS4zODUuMDczLS41MzEuMjJ6IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48L3N2Zz4=" style="height:40px; float:right; color:orange">';
            echo "
            
            </a>";

            ?>
        </span>

    </div>
</div>