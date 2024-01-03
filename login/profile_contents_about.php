<div id="d2" style="width: 97.5%;  text-align:center; background-color:white">
    <div style="padding :20px;  display: inline-block;"></div>

    <div id="d5">
        <?php
        $image = "../img/user_male.jpg";
        if ($user_data['gender'] == "female") {
            $image = "../img/user_female.jpg";
        }
        if (file_exists($user_data['profile_image'])) {
            echo "<style height:>";
            $image = $image_class->get_thumb_profile($user_data['profile_image']);

            echo "</style>";
        }
        ?>



        <img src="<?php echo $image; ?>" alt="" height="200px" width="200px" id="pics"><br>
        &nbsp;&nbsp;&nbsp;<a href="
                
                <?php
                $link1 = "admin_panel.php";

                $link2 = "profile.php";

                if ($user_data['status'] == "Admin") {
                    echo $link1;
                } else {
                    echo $link2;
                }
                ?>
                "><?php echo $user_data['first_name'] . " " . $user_data['last_name']; ?></a>

    </div>

    <form action="" method="post" enctype="multipart/form-data">

        <?php

        $about_class = new About();
        $about = $about_class->get_bio($_SESSION['SOCIAL_userid']);
        if (is_array($about)) {

            echo "<br>Name   :" . htmlspecialchars($about['first_name']) . " " . htmlspecialchars($about['last_name']) . "</i>";

            echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email  :    " . htmlspecialchars($about['email']) . "";
        }
        ?>
        <?php
        if (is_array($about) and $about['status'] != "Admin") {

            echo "<br><i>Semester :   " . htmlspecialchars($about['semester']) . "</i><br>";
        }
        ?>
        <?php
        if (is_array($about)) {
            echo "<br><i>Address : " . htmlspecialchars($about['address']) . "</i><br>";
        }
        ?>
</div>