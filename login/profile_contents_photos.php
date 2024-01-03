<div id="d2" style="width: 97.5%;  text-align:center; background-color:white">
    <div style="padding :20px"></div>

    <?php

    $database = new database();
    $sql = "SELECT image,userid FROM posts WHERE has_image = 1 && postid =  $user_data[userid] ORDER BY id DESC LIMIT 30";
    //echo $sql;


    //echo $sql;
    $images = $database->read($sql);


    $image_class = new Image();
    //print_r($images);
    if (is_array($images)) {

        //print_r($images);
        foreach ($images as $image_row) {

            //echo $image_row['userid'] . "<br>";

            //echo $image_class->get_thumb_profile($image_row['image']);


            echo "<a href='single_post.php?id=$image_row[userid]' style='text-decoration:none'>";
            echo "<img src='" . $image_class->get_thumb_profile($image_row['image']) . "' style='height:200px' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            echo "</a>";
        }
    } else {
        echo "No images were posted";
    }
