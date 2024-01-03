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


if (isset($_SERVER["HTTP_REFERER"]) && !strstr($_SERVER["HTTP_REFERER"], "delete.php")) {
    $_SESSION['return_to'] = $_SERVER["HTTP_REFERER"];
}

$error = "";
$Post = new Post();
$msg_class = new Messages();
//echo $_GET['id'];

if (isset($_GET['id'])) {

    if ($_GET['type'] == "del") {

        $row = $Post->get_single_posts($_GET['id']);

        //show($post);
        //die;
        if (!$row) {
            $error = "Access denied you cannot delete this Posts!!";
        }
    } elseif ($_GET['type'] == "msg") {

        $message = $msg_class->read_1($_GET['id']);

        if (!$message) {
            $error = "Access denied you cannot delete this message!!";
        }
    } else
        //duplicated
        if ($_GET['type'] == "thread") {

            $message = $msg_class->read_one_thread($_GET['id']);

            if (!$message) {
                $error = "Access denied you cannot delete this threads!!";
            }
        } else {


            $row = $Post->get_single_posts($_GET['id']);

            if (!$row) {
                $error = "No such posts found!!";
            } else {

                if (!i_own_content($row)) {

                    $error = "Access denied!!!";
                }
            }
        }
} else {
    $error = "No such posts found!!";
}




// if something was posted
if ($_SERVER['REQUEST_METHOD'] == "POST") {


    if ($_GET['type'] == "msg") {


        $msg_class->delete_one($_POST['id']);
    } elseif ($_GET['type'] == "thread") {


        $msg_class->delete_one_thread($_GET['id']);
    } else {



        $Post->delete_post($_POST['postid']);
    }
    header("Location: " . $_SESSION['return_to']);
    die();
}



?>
<html>

<head>
    <title>Delete | My Book</title>
    <style type="text/css">
        #message_left {
            padding: 4px;
            font-size: 13px;
            display: flex;
            margin: 8px;
            width: 60%;
            background-color: #cccc;
            float: left;
            color: black;
            border-radius: 10px;
        }
    </style>
    <link rel="stylesheet" href="../css/p.css">
    <link rel="stylesheet" href="../css/pp.css?v=1">
</head>

<body>
    <!----nav bar -->
    <?php include('header.php'); ?>
    <!----cover area--->
    <div id="container">
        <!-----below - cover--->

        <!---posts area--->
        <div id="d2">
            <div id="id2">
                <form action="" method="post">


                    <?php




                    if ($error != "") {
                        echo $error;
                    } else {
                        if (isset($_GET['type']) and $_GET['type'] == "msg") {

                            echo "Are you sure you wanna delete this message?<br><br>";

                            $user = new User();
                            $row_user = $user->get_user($message['sender']);

                            include("message_left.php");
                            echo "
                        <input type='hidden' name='id' value='$message[id]'>";
                            echo "<input type='submit' id='post_btn' value='Delete'><br><br>";
                        } else

                            if (isset($_GET['type']) and $_GET['type'] == "thread") {

                            echo "Are you sure you wanna delete this thread?<br><br>";

                            $user = new User();
                            $row_user = $user->get_user($message['sender']);

                            include("message_left.php");
                            echo "
                            <input type='hidden' name='id' value='$message[msgid]'>";
                            echo "<input type='submit' id='post_btn' value='Delete'><br><br>";
                        } else {

                            echo "Are you sure you wanna delete this post?<br><br>";

                            $user = new User();
                            $row_user = $user->get_user($row['postid']);

                            include("post_delete.php");
                            echo "
                        <input type='hidden' name='postid' value='$row[userid]'>";
                            echo "<input type='submit' id='post_btn' value='delete'><br><br>";
                        }
                    }
                    ?>

                    <br style="clear:both;">
                </form>
            </div>

        </div>
    </div>
    </div>
</body>

</html>