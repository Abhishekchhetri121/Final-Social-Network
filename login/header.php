<?php

$corner_image = "../img/user_male.jpg";
//$login = new Login();
//$user_data = $login->check_login($_SESSION["SOCIAL_userid"]);
//$USER = $user_data;


if (isset($USER)) {
    if (file_exists($USER['profile_image'])) {


        //$USER = $user_data;
        $image_class = new Image();
        $corner_image = $image_class->get_thumb_profile($USER['profile_image']);
    } else {
        if ($USER['gender'] == "female") {
            $corner_image = "../img/user_female.jpg";
        }
    }
}





?>


<div class="blue_bar" style="display: flex; justify-content: center;">


    <div class="bar" style="height: 70px;
    width: 78px;">
        <div class="imgs" style="height: 70px;
    width: 78px;">
            <img src="../img/logos.jpg" style="height: 50px;
    width: 65px; position: relative; top:-1px; left:-322px">

        </div>&nbsp;

    </div>




    <!---------search starts------->


    <form action="search.php" method="get">
        <div class="stl">
            <a href="index.php?status=<?php echo $user_data['status'] ?>" style="position:relative;left:-20px"> Activities </a>

            &nbsp;&nbsp;<input type="text" name="find" id="search" placeholder="Search for people" style="position:relative; top:-3px;left:-20px">

            <!---------search starts complete------->

            &nbsp; &nbsp;
            <!-------notifications link starts------->
            <span id="links">
                <a href="notifications.php">
                    <img src=" ../img/earth.svg" alt="" srcset="" style="position:relative; left:-40px; height:50px; background-color:rgb(0,0,125); top:-10px; width:60px">
                </a>
                <?php
                $notif = check_notification();

                //echo $notif;
                ?>
                <?php

                if ($notif > 0) : ?>
                    <div id="red_button"><?= $notif; ?></div>
                <?php
                endif; ?>
            </span>

            <!----notifications link finish---->




            <!------- message notifications link starts------->
            <span id="links">
                &nbsp;&nbsp;
                <a href="messages.php">
                    <img src=" ../img/comment.png" alt="" srcset="" style="position:relative; left:-2px; top:-10px; background-color:rgb(0, 0, 125); height:40px; width:50px">






                </a>
                <?php
                $notif = check_messages();

                //echo $notif;
                ?>
                <?php

                if ($notif > 0) : ?>
                    <div id="red_button" style="top:-4px; margin-left: 10px;"><?= $notif; ?></div>
                <?php
                endif; ?>
            </span>


            <!---- message notifications link finish---->













            <!---------image icon display--------->
            &nbsp;
            <?php if (isset($USER)) : ?>

                <?php

                $link1 = "aa.php";

                $link2 = "profile.php";
                if ($USER['status'] == "Admin")
                    echo "
                        <a href='$link1'>
                    <img src=
                '$corner_image'  alt='img noo' style='height:40px; left-align:20px;position:relative; border-radius:50%;top:4px;left:20%;'>
                </a>

                ";

                else {
                    echo "
                        <a href='$link2'>
                    <img src=
                '$corner_image'  alt='img noo' style='height:40px;  border-radius:50%;position:relative;left:162px; top:4px'>
                </a>";
                }
                ?>







                &nbsp;
                <!----logout starts---->
                <span id="logout">
                    &nbsp;
                    <a href="logout.php" style="position:relative; top:-50px; left:37%; border-radius:50px;">

                        <img src=" ../img/finallog.svg" alt="" srcset="" style="position:relative;  height:42px; left:-1px">

                    </a>





                </span><!-------span doesn't creates new lines so used span--->
                <!--------logout finished--->
            <?php endif; ?>
        </div>
    </form>
</div>