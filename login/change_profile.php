<?php

include("../classes/loader.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['SOCIAL_userid']);

//print_r($_GET);

if ($_SERVER['REQUEST_METHOD'] == "POST") {


    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {


        if ($_FILES['file']['type'] == "image/jpeg") {
            $allowed_size = (1024 * 1024) * 7;

            if ($_FILES['file']['size'] < $allowed_size) {

                //fine
                //creation of folder based on user
                $folder = "uploads/" . $user_data['userid'] . "/";
                //$filename = "uploads/" . $_FILES['file']['name'];

                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true); //making of folder true b/c if     uploads doesn't exists then creates


                }

                $image = new Image();
                $filename = $folder . $image->generate_filename(15) . ".jpg";
                move_uploaded_file($_FILES['file']['tmp_name'], $filename);


                $change = "profile";


                //check for mode
                if (isset($_GET['change'])) {
                    $change = $_GET['change'];
                }

                //once profile or cover changed then delete old one

                if ($change == "cover") {

                    if (file_exists($user_data['cover_image'])) {
                        unlink($user_data['cover_image']);
                    }
                    $image->resize_image($filename, $filename, 1500, 1500);
                } else {
                    if (file_exists($user_data['profile_image'])) {
                        unlink($user_data['profile_image']);
                    }
                    $image->resize_image($filename, $filename, 1500, 1500);
                }



                if (file_exists($filename)) {

                    $id = $user_data['userid'];

                    if ($change == "cover") {
                        $query = "UPDATE USERS SET cover_image = '$filename' WHERE userid = '$id' LIMIT 1 ";
                        $_POST['is_cover'] = 1;
                    } else {

                        $query = "UPDATE USERS SET profile_image = '$filename' WHERE userid = '$id' LIMIT 1 ";
                        $_POST['is_profile'] = 1;
                    }

                    $db = new database();
                    $db->save($query);

                    //create a post
                    $post = new POST();
                    $post->create_post($id, $_POST, $filename);


                    header(('Location:profile.php'));
                    die();
                }
            } else {
                echo "<div style='text-align:center; font-size:12px; background-color:gray; color:white '>";
                echo "The following error occured<br>";
                echo "size=3MB or lower allowed";
                echo "</div>";
            }
        } else {
            echo "<div style='text-align:center; font-size:12px; background-color:gray; color:white '>";
            echo "The following error occured<br>";
            echo "only images of jpeg allowed";
            echo "</div>";
        }
    } else {
        echo "<div style='text-align:center; font-size:12px; background-color:gray; color:white '>";
        echo "The following error occured<br>";
        echo "please add a valid images";
        echo "</div>";
    }
}

?>
<html>

<head>
    <title>Change Profile | My Book</title>
    <link rel="stylesheet" href="../css/p.css">

</head>

<body>
    <!----nav bar -->
    <?php include('header.php'); ?>
    <!----cover area--->
    <div id="container">

        <!-----below - cover--->
        <div id="mid-panel">
            <!---mates-->

            <!---posts area--->
            <div id="d2">
                <div id="id2">
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="file" name="file">
                        <input type="submit" id="post_btn" value="Change"><br><br>
                        <div id="old_pic"><br>

                            <?php

                            //check for mode
                            if (isset($_GET['change']) && $_GET['change'] == "cover") {
                                $change = $_GET['change'];
                                echo  "<img src ='$user_data[cover_image]' style='max-width:500px' >";
                            } else {
                                echo  "<img src ='$user_data[profile_image]' style='max-width:500px' >";
                            }

                            ?>
                        </div>
                    </form>
                </div>


                <!----posts --->

            </div>
        </div>
    </div>


</body>

</html>