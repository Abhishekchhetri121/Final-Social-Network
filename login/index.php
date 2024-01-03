<?php
include("../classes/loader.php");

$login = new Login();
$user_data = $login->check_login($_SESSION["SOCIAL_userid"]);

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

    if (isset($_POST['first_name'])) {

        $setting_class = new Settings();
        $setting_class->save_settings($_POST, $_SESSION['SOCIAL_userid']);
    } else {
        $post = new POST();
        $id = $_SESSION['SOCIAL_userid'];
        $result = $post->create_post($id, $_POST, $_FILES);


        if ($result == "") {
            header("Location: index.php");
            die();
        } else {

            echo "<div style='text-align:center; font-size:12px; background-color:gray; color:white '>";
            echo "The following error occured<br>";
            echo $result;
            echo "</div>";
        }
    }
}


$p = new POST();
$id = $_SESSION['SOCIAL_userid'];
$result = $p->get_posts($id);


?>
<html>

<head>
    <title>Timeline | My Book</title>
    <link rel="stylesheet" href="../css/p.css">
    <link rel="stylesheet" href="../css/pp.css?v=1">
</head>

<body>
    <!----nav bar -->

    <?php include('header.php'); ?>
    <!----cover area--->
    <div id="container">

        <!-----below - cover--->
        <div id="mid-panel">
            <!---mates-->
            <div id="d1">
                <?php
                $image = "../img/user_male.jpg";
                if ($user_data['gender'] == "female") {
                    $image = "../img/user_female.jpg";
                }
                if (file_exists($user_data['profile_image'])) {
                    $image = $image_class->get_thumb_profile($user_data['profile_image']);
                }
                ?>



                <img src="<?php echo $image; ?>" alt="" id="pics"><br>
                &nbsp;&nbsp;&nbsp;<a href="
                
                <?php
                $link1 = "admin_panel.php";

                $link2 = "profile.php";

                if ($user_data['status'] == "Admin") {
                    echo "$link1";
                } else {
                    echo "$link2";
                }
                ?>
                "><?php echo $user_data['first_name'] . " " . $user_data['last_name']; ?></a>

            </div>

            <!---posts area--->
            <div id="d2">
                <div id="id2">
                    <form action="" method="post" enctype="multipart/form-data">
                        <textarea name="post" placeholder="What's on Your mind?"></textarea>
                        <input type="file" name="file">
                        <input type="submit" id="post_btn" value="post"><br><br>
                    </form>
                </div>
                <!----posts --->

                <div id="post_bar">
                    <?php


                    $page_number = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                    $page_number = ($page_number < 1) ?  1 : $page_number;

                    $limit = 10;
                    $offset = ($page_number - 1) * $limit;

                    $db = new database();
                    $user_class = new User();
                    //$user_class = new Image();

                    $followers =  $user_class->get_following($_SESSION['SOCIAL_userid'], "user");
                    $followers_ids = false;

                    if (is_array($followers)) {
                        $followers_ids = array_column($followers, "userid");
                        $followers_ids = implode("','", $followers_ids);
                    }

                    //print_r($followers_ids);

                    if (isset($_GET['status']) and $_GET['status'] == "Admin" and isset($_GET['id'])) {

                        $ids = $_GET['id'];


                        $myuserid = $_SESSION['SOCIAL_userid'];
                        $sql = "SELECT * from  posts where parent =  0 and ( postid = '$ids' )  ORDER BY id DESC LIMIT $limit offset $offset";



                        $result = $db->read($sql);
                    } elseif (isset($_GET['status']) and $_GET['status'] == "Admin") {
                        $myuserid = $_SESSION['SOCIAL_userid'];
                        $sql = "SELECT * from  posts where parent =  0 and (postid = '$myuserid' OR postid != '$myuserid' )  ORDER BY id DESC LIMIT $limit offset $offset";



                        $result = $db->read($sql);
                    } else {
                        if ($followers_ids) {
                            $myuserid = $_SESSION['SOCIAL_userid'];
                            $sql = "SELECT * from  posts where parent =  0 and (postid = '$myuserid' or postid   in('" . $followers_ids . "')) ORDER BY id DESC LIMIT $limit offset $offset";



                            $result = $db->read($sql);
                            //print_r($op);

                        }
                    }

                    if ($result) {
                        foreach ($result as $row) {
                            $user_class = new User();
                            $row_user = $user_class->get_user($row['postid']);

                            include('post.php');
                        }
                    }

                    //get current url
                    $pg = paination_link();
                    ?>
                    <a href="<?= $pg['next_page'] ?>">
                        <input type="button" id="post_btn" value="Next Page" style="position:relative; top:40px; cursor:pointer;"><br><br>
                    </a>
                    <a href="<?= $pg['prev_page'] ?>">
                        <input type="button" id="post_btn" value="Prev Page" style="float:left;"><br><br>
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>