<?php


include("../classes/loader.php");

$login = new Login();
$_SESSION["SOCIAL_userid"] = isset($_SESSION["SOCIAL_userid"]) ? $_SESSION["SOCIAL_userid"] : 0;
$user_data = $login->check_login($_SESSION["SOCIAL_userid"], false);



$USER = $user_data;

$profile = new Profile();
if (isset($_GET['id']) and is_numeric($_GET['id'])) {
    $profile_data = $profile->get_profile($_GET['id']);


    if (is_array($profile_data)) {
        $user_data = $profile_data[0];
    }
}


//posting starts here

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    include("change_pics.php");
    if (isset($_POST['first_name'])) {

        $setting_class = new Settings();
        $setting_class->save_settings($_POST, $_SESSION['SOCIAL_userid']);
    } else {
        $post = new POST();
        $id = $_SESSION['SOCIAL_userid'];
        $result = $post->create_post($id, $_POST, $_FILES);


        if ($result == "") {
            header("Location: profile.php");
            die();
        } else {

            echo "<div style='text-align:center; font-size:12px; background-color:gray; color:white '>";
            echo "The following error occured<br>";
            echo $result;
            echo "</div>";
        }
    }
}

//posts collection
$p = new POST();
$id = $user_data['userid'];
$result = $p->get_posts($id);

//friends collection
$user = new User();

$friends = $user->get_following($user_data['userid'], "user");

$image_class = new Image();


if (isset($_GET['notif'])) {

    notification_seen($_GET['notif']);
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile | My Book</title>
    <link rel="stylesheet" href="../css/p.css?v=1">
    <!---<link rel="stylesheet" href="../css/pp.css">--->
</head>

<body>
    <!----nav bar -->
    <?php include('header.php'); ?>

    <!--------change profile image area---->
    <div id="change_profile_image" style="display:none;position:absolute; width:100%; height:100%; background-color:#000000aa">
        <div id="id2" style="max-width:600px; min-height:400px;padding:20px; margin:auto; ">
            <form action="profile.php?change=profile" method="post" enctype="multipart/form-data">
                <input type="file" name="file">
                <input type="submit" id="post_btn" style="width:120px;" value="Change"><br><br>
                <div id="old_pic"><br>
                    <?php
                    echo  "<img src ='$user_data[profile_image]' style='max-width:500px' >";
                    ?>
                </div>
            </form>
        </div>

    </div>

    <!--------change cover image area---->
    <div id="change_cover_image" style="display:none;position:absolute; width:100%; height:100%; background-color:#000000aa">
        <div id="id2" style="max-width:600px; min-height:400px;padding:20px; margin:auto; ">
            <form action="profile.php?change=cover" method="post" enctype="multipart/form-data">
                <input type="file" name="file">
                <input type="submit" id="post_btn" style="width:120px;" value="Change"><br><br>
                <div id="old_pic"><br>

                    <?php
                    echo  "<img src ='$user_data[cover_image]' style='max-width:500px' >";

                    ?>
                </div>
            </form>
        </div>

    </div>

    <!----cover area-->
    <div id="container">

        <!------header below starts--->
        <div id="imgh">
            <?php
            $image = "../img/cover_image.jpg";

            if (file_exists($user_data['cover_image'])) {
                $image = $image_class->get_thumb_cover($user_data['cover_image']);

                //echo $image;
            }
            ?>


            <img src="<?php echo $image ?>" alt="">

            <?php
            $image = "../img/user_male.jpg";
            if ($user_data['gender'] == "female") {
                $image = "../img/user_female.jpg";
            }
            if (file_exists($user_data['profile_image'])) {
                $image = $image_class->get_thumb_profile($user_data['profile_image']);
            }
            ?>
            <img src="<?php echo $image ?>" alt="" id="pics">
            <br>


            <?php if (profile_visit($user_data)) :  ?>
                <a onclick="show_change_profile_image(event)" href="change_profile.php?change=profile">Change Profile</a> |
                <a onclick="show_change_cover_image(event)" href="change_profile.php?change=cover">Change Cover</a>
            <?php endif;  ?>

            <div id="nn">
                <a href="profile.php?id=<?php echo $user_data['url_address'] ?>" style="text-decoration:none"><?php echo $user_data['first_name'] . " " . $user_data['last_name']; ?></a>
            </div>
            <br>

            <!-----header below finished---->


            <!------followers start----->
            <?php
            $my_likes = "";

            //if ($user_data['likes'] > 0) {

            //$my_likes = "(" . $user_data['likes'] . " Followers)";
            $my_likes = $user_data['likes'];
            //}
            ?>
            <a href="like.php?type=user&id=<?php echo $user_data['userid']; ?>">

                <input type="submit" id="post_btn" value="Follow(<?php echo $my_likes; ?> Followers)" style="margin-top:32px; margin-right:10px">

            </a><br><br>
            <!----followers ending----->

            <!-----menu start---->
            <div id="menus"><a href='index.php'>Timeline</a></div>

            <div id="menus"><a href='profile.php?section=followers&id=<?php echo $user_data['userid'] ?>'>Followers</a></div>

            <div id="menus"><a href='profile.php?section=photos&id=<?php echo $user_data['userid'] ?>'>Photos</a></div>

            <div id="menus"><a href='management.php?section=photos&id=<?php echo $user_data['userid'] ?>'>Management </a></div>
            <div id="menus">

                <?php
                if ($user_data['userid'] == $_SESSION['SOCIAL_userid']) {
                    echo '<a href="profile.php?section=settings&id=' . $user_data['userid'] . '">Settings</a>';
                }
                ?></div>
        </div>

        <!---- menu ends--->
        <!-----below - cover--->
        <?php
        $section = "default";
        if (isset($_GET['section'])) {
            $section = $_GET['section'];
        }
        if ($section == "default") {
            include("profile_contents_default.php");
        } elseif ($section == "following") {

            include("profile_contents_following.php");
        } elseif ($section == "photos") {

            include("profile_contents_photos.php");
            # code...
        } elseif ($section == "followers") {

            include("profile_contents_followers.php");
            # code...
        } elseif ($section == "manage") {

            include("management.php");
            # code...
        } elseif ($section == "settings") {

            include("profile_contents_setting.php");
            # code...
        }
        ?>

    </div>
    <!---<div id="d8">
            <h3><i>Followers</i></h3>
            <?php
            /*
            //print_r($op);
            $post_class = new Post();
            $followers = $post_class->get_likes($user_data['userid'], "user");
            //$image_class = new Image();
            $user_class = new User();
            if (is_array($followers)) {

                foreach ($followers as $follower) {

                    $row = $user_class->get_user($follower['userid']);
                    include("user.php");
                    echo "<br>";
                }
            } else {
                echo "No followers found";
            }*/
            ?>
        </div>--->

</body>

</html>
<script type="text/javascript">
    //change profile dialog box
    function show_change_profile_image(event) {

        event.preventDefault();
        var profile_image = document.getElementById("change_profile_image");
        profile_image.style.display = "block";

    }

    function hide_change_profile_image() {
        var profile_image = document.getElementById("change_profile_image");
        profile_image.style.display = "none";

    }

    function show_change_cover_image(event) {

        event.preventDefault();
        var cover_image = document.getElementById("change_cover_image");
        cover_image.style.display = "block";

    }

    function hide_change_cover_image() {
        var cover_image = document.getElementById("change_cover_image");
        cover_image.style.display = "none";

    }
    window.onkeydown = function(key)

    {
        if (key.keyCode == 27) {
            //alert(key.keyCode);

            hide_change_profile_image();
            hide_change_cover_image();
        }
    }
</script>