<div id="friends" style="display:inline-block; width: 200px; background-color: #eee;">
    <?php


    if (isset($row) and !empty($row)) {
        $image = "../img/user_male.jpg";

        if ($row['gender'] == "female") {
            $image = "../img/user_female.jpg";
        }
        if (file_exists($row['profile_image'])) {

            $image_class = new Image();
            $image = $image_class->get_thumb_profile($row['profile_image']);
        }
    }
    ?>

    <a href="profile.php?id=<?php echo $row['userid']; ?>">
        <img id="fimg" src="<?php echo $image; ?>" alt="" srcset=""><br>

        <?php
        if (!empty($row)) {
            echo $row['first_name'] . " " . $row['last_name'] . "<br>";
        } else {

            echo "Unknown user";
        }
        ?>
        <?php
        $online = "Last seen : <br>Unknown";
        if ($row['online'] > 0) {


            $time = new Time();
            $online = $row['online'];


            $current_time = time();

            $threshold = 60 * 2; //2 minutes

            if (($current_time - $online) < $threshold) {
                $online = "<span style='color:RGB(0,0,0);'>Status<div style='
                border : 5px solid white;height: 12px;width: 10px;margin: auto; position: relative;
                top: -2px;float: right;left: -8px;background-color: RGB(0,241,0);border-radius: 32%;'></div></span>";
                //$online = "<div style='border : 3px solid black; height:5px; width:6px; margin:auto'; float:right; fill:RGB(0,250,0);></div>";
            } else {

                $online = "Last seen : <br>" . $time->get_time($row['date'], $online);
            }
        }
        ?>

        <br>
        <span style="color:gray; font-size: 12px;"><?php echo $online ?></span>
    </a>
</div>