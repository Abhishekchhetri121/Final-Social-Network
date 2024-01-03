<div id="posts">
    <div class="txt">
        <?php $image = "../img/user_male.png";

        if ($row_user['gender'] == "female") {
            $image = "../img/user_female.png";
        }

        $image_class = new Image();
        if (file_exists($row_user['profile_image'])) {


            $image = $image_class->get_thumb_profile($row_user['profile_image']);;
        }


        ?>
        <img src="<?php echo $image; ?>" id="img_post" alt="">
    </div>
    <div style="width:100%">
        <div class="poster">

            <?php
            echo htmlspecialchars($row_user['first_name']) . " " . htmlspecialchars($row_user['last_name']);

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

            $post_image = $image_class->get_thumb_post($row['image']);

            echo "<img src='$post_image' style='width:100%' />";
        }
        ?>


    </div>
</div>