<?php

include("../classes/loader.php");

$error = "";
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


$msg_class = new Messages();
// new message check if thread already exists

if (isset($_GET['type']) and $_GET['type'] == 'new') {
    $old_thread = $msg_class->read($_GET['id']);
    if (is_array($old_thread)) {
        //redireect users
        header("Location: messages.php?type=read&id=" . $_GET['id']);
        die;
    }
}
// if a message was posted
if ($error == "" and $_SERVER['REQUEST_METHOD'] == "POST") {



    //$Post->delete_post($_POST['postid']);

    //show($_POST);
    //show($_FILES);


    $user_class = new User();


    if (is_array($user_class->get_user($_GET['id']))) {


        $error = $msg_class->send($_POST, $_FILES, $_GET['id']);


        header("Location: messages.php?type=read&id=" . $_GET['id']);
        die();
    } else {
        $error = "The requested user counld not be found";
    }
}



?>
<html>

<head>
    <title>Messages | My Book</title>
    <link rel="stylesheet" href="../css/p.css?v=1">
    <link rel="stylesheet" href="../css/pp.css?v=1">

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

        #message_right {
            padding: 4px;
            font-size: 13px;
            display: flex;
            margin: 8px;
            width: 60%;
            background-color: #ccc;
            float: right;
            color: black;
            font-weight: 50%;
            border-radius: 10px;
        }

        #message_left>a {
            color: white;
        }



        #message>.txt {
            margin-right: 5px;
        }

        #message .poster {
            color: rgb(225, 65, 102);
            font-weight: bold;
        }

        #message_thread {
            padding: 4px;
            font-size: 13px;
            display: flex;
            margin: 8px;


            position: relative;
            background-color: #eee;
            color: black;
            border-radius: 10px;
        }

        #message_thread>#ribbon {
            background-color: #ff00b3;
            height: 90%;

            border-top-right-radius: 50%;
            border-bottom-right-radius: 50%;

            cursor: pointer;
            width: 50px;
            position: absolute;
            right: 10px;
        }
    </style>

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
                <form action="" method="post" enctype='multipart/form-data'>


                    <?php




                    if ($error != "") {
                        echo $error;
                    } else {



                        if (isset($_GET['type']) and $_GET['type'] == 'read') {

                            //$msg_class = new Messages();
                            //$msg_class->read($_GET['id']);

                            echo "Chatting with:<br><br>";

                            if (isset($_GET['id']) and is_numeric($_GET['id'])) {

                                //$msg_class = new Messages();
                                $data = $msg_class->read($_GET['id']);


                                $user = new User();
                                $row = $user->get_user($_GET['id']);

                                include("user.php");

                                echo "<a href='messages.php'>";

                                echo "
                                <input type='button' style='width:auto; cursor:pointer; margin:4px;' id='post_btn' value='All messages'>";

                                echo "</a>";

                                if (!empty($data)) {

                                    if (is_array($data)) {
                                        echo "<a href='" . 'delete.php?type=thread&id=' . $data[0]['msgid'] . "'>";

                                        echo "
                                <input type='button' style='width:auto; cursor:pointer;background-color:RGB(0,0,125); margin:4px;' id='post_btn' value='Delete Threads'>";

                                        echo "</a>";
                                    }
                                }



                                echo "

                                <div>";

                                if (isset($data) and is_array($data)) {
                                    foreach ($data as $message) {
                                        # code...
                                        //show($message);

                                        $user = new User();
                                        $row_user = $user->get_user($message['sender']);

                                        if (i_own_content($message)) {
                                            include("message_right.php");
                                        } else {
                                            include("message_left.php");
                                        }
                                    }
                                }
                                echo "
                                </div>
                                ";
                                echo "
                                    <div id='id2'>
            


                                    <textarea name='message' placeholder='Write Your message here'></textarea>
                                    <input type='file' name='file'>
                                    <input type='submit' id='post_btn' value='send'><br><br>









                
                                                
                                    </div>";
                            } else {
                                echo "user not found";
                            }

                            //<!----just added fin--->












                        } else
                        
                            if (isset($_GET['type']) and $_GET['type'] == 'new') {




                            echo "Start New Message with:<br><br>";

                            if (isset($_GET['id']) and is_numeric($_GET['id'])) {
                                $user = new User();
                                $row = $user->get_user($_GET['id']);
                                include("user.php");

                                echo '<br>
                                        <div id="id2">
            
                                            
                                        <textarea name="message"placeholder="Write Your message here"></textarea>
                                        <input type="file" name="file">
                                        <input type="submit" id="post_btn" value="send"><br><br>
                                            
                                        </div>';
                            } else {
                                echo "user not found";
                            }
                        } else {

                            //who sent messages starts //
                            echo "Messages<br><br>";
                            $data  = $msg_class->read_threads();

                            $user = new User();
                            $me = esc($_SESSION['SOCIAL_userid']);

                            if (is_array($data)) {
                                foreach ($data as $message) {

                                    $myid = ($message['sender'] == $me) ?  $message['receiver'] : $message['sender'];


                                    $row_user = $user->get_user($myid);
                                    include("thread.php");
                                }
                            } else {
                                echo "You have no messages";
                            }
                            echo "<br style='clear:both;'>";
                        }
                        //sender known
                    }
                    ?>

                </form>
            </div>

        </div>
    </div>
    </div>
</body>

</html>