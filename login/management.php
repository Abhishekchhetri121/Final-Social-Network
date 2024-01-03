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




$database = new database();
$sql = "SELECT * FROM USERS WHERE status !='Admin' ORDER BY id ASC LIMIT 10";
//echo $sql;


//echo $sql;
$result = $database->read($sql);





?>
<html>

<head>
    <title></title>
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
        <br><br>
        <div class="tables">
            <table>
                <tr>
                    <th>S.NO</th>
                    <th>User</th>
                    <th colspan="2">Action</th>
                </tr>
                <tr>

                    <?php
                    for ($i = 0; $i < 3; $i++) {

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
                        '$corner_image'  alt='img noo' style='height:40px; position:relative; border-radius:50%;top:4px'>&nbsp&nbsp$name<br>$email";


                            ?>
                        </td>

                        <td>Delete</td>
                        <td>Update</td>


                </tr>
            <?php } ?>
            </table>



        </div>
    </div>
</body>

</html>



</body>

</html>