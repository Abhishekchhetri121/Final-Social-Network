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




$db = new database();

$c1 = "SELECT COUNT(*) FROM POSTS";
$c2 = "SELECT COUNT(*) FROM content_i_follow";
$c3 = "SELECT COUNT(*) FROM likes WHERE type = 'post'";

$c1r = $db->read($c1);
$c2r = $db->read($c2);
$c3r = $db->read($c3);

//$no = $count[0]['COUNT(*)'];

$cl1 = $c1r[0]['COUNT(*)'];
$cl2 = $c1r[0]['COUNT(*)'];
$cl3 = $c1r[0]['COUNT(*)'];


$sql = "SELECT * FROM USERS WHERE status !='Admin' ORDER BY id ASC LIMIT 10";

$co = "SELECT COUNT(*) FROM USERS WHERE status !='Admin'";

$admi_sql = "SELECT status FROM USERS WHERE status = 'Admin' ";


$did = "";
if (isset($_GET["delid"])) {
    $did = $_GET['delid'];
}

$result = $db->read($sql);
$count = $db->read($co);

$no = $count[0]['COUNT(*)'];

$d1 = "DELETE  FROM USERS WHERE userid = '$did'";
$d2 = "DELETE  FROM posts WHERE postid = '$did'";
$d3 = "DELETE  FROM notification_seen WHERE userid = '$did'";

$d4 = "DELETE  FROM notification WHERE userid = '$did' OR content_owner = '$did'";

$d5 = "DELETE  FROM likes WHERE JSON_UNQUOTE(JSON_EXTRACT(likes, '$[0].userid')) = '$did'";
$d6 = "DELETE  FROM follow WHERE JSON_UNQUOTE(JSON_EXTRACT(following, '$[0].userid')) = '$did' OR userid = '$did'";

$d7 = "DELETE  FROM content_i_follow WHERE userid = '$did'";

$d8 = "DELETE FROM  messages WHERE sender = '$did' OR receiver = '$did'";

if (isset($d1) or isset($d2) or isset($d3) or isset($d4) or isset($d5) or isset($d6) or isset($d7) or ($d8)) {

    $yes = $db->delete($d1);
    $yes2 = $db->delete($d2);
    $yes3 = $db->delete($d3);
    $yes4 = $db->delete($d4);
    $yes5 = $db->delete($d5);
    $yes6 = $db->delete($d6);
    $yes7 = $db->delete($d7);
    $yes8 = $db->delete($d8);
}

$admin = $db->read($admi_sql);


?>
<html>

<head>

    <title>Document</title>
    <link rel="stylesheet" href="../css/p.css?v=1">
    <link rel="stylesheet" href="../css/admin_home.css?v=2">
</head>

<body>

    <?php include('header.php'); ?>

    <div id="containers">
        <div class="left_wing">

            <a href="../login/aa.php">Dashboard</a><br><br><br>
            <a href="profile.php">Edit Profile</a><br><br><br>



        </div>
        <div class="mp">

            <div class="count">

                <div class="count-user">


                    <h3>Total Users</h3>
                    <br>
                    <h3><?php echo $no; ?></h3>

                </div>

                <div class="count-posts">

                    <h3>Total Posts</h3>
                    <h3><?php echo $cl1; ?></h3>
                </div>

                <div class="comments-count">
                    <h3>Total comments</h3>
                    <h3><?php echo 0; ?></h3>
                </div>
                <div class="likes-count">
                    <h3>Total Likes</h3>
                    <h3><?php echo $cl3; ?></h3>
                    <br>
                </div>
            </div>
            <div class="tt">

                <table>
                    <tr>
                        <th>S.NO</th>
                        <th>User</th>
                        <th colspan="2">Action</th>
                    </tr>
                    <tr>

                        <?php

                        for ($i = 0; $i < $no; $i++) {

                        ?>
                            <td><?php echo $result[$i]['id'];  ?></td>
                            <td><?php
                                $corner_image = "../img/user_male.jpg";

                                if (isset($result)) {
                                    $first_name = $result[$i]['first_name'];
                                    $last_name = $result[$i]['last_name'];
                                    $name = $first_name . " " . $last_name;
                                    $email = $result[$i]['email'];
                                    if (file_exists($result[$i]['profile_image'])) {


                                        //$USER = $user_data;
                                        $image_class = new Image();
                                        $corner_image = $image_class->get_thumb_profile($result[$i]['profile_image']);
                                    } else {
                                        if ($result[$i]['gender'] == "female") {
                                            $corner_image = "../img/user_female.jpg";
                                        }
                                    }
                                }


                                echo "<img src=
                        '$corner_image'  alt='img noo' style='height:40px; position:relative; border-radius:50%;top:4px'>&nbsp&nbsp$name<br><br>$email";


                                ?>
                            </td>

                            <td><a href="aa.php?delid=<?php echo  $result[$i]['userid'] ?>">Delete</a></td>
                            <td><a href="index.php?status=<?php echo $admin[0]['status']






                                                            ?>&up&id=<?php echo  $result[$i]['userid']; ? >">Update Posts</a></td>


                    </tr>
                <?php } ?>
                </table>
            </div>
        </div>






    </div>
</body>

</html>