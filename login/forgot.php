<?php
include("../classes/loader.php");
//session_start();

require("../classes/mail.php");
$error = array();
$db = new database();
$mode = "enter_email";
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
}
//something is posted

if (count($_POST) > 0) { // someone enetered email
    switch ($mode) {
        case 'enter_email':

            $email = $_POST['email'];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error[] = "Please enter a valid email";
            } elseif (!$db->valid_email($email)) {
                $error[] = "That email was not found";
            } else {

                $_SESSION['forgot']['email'] = $email;

                $db->send_email($email);

                //


                header("Location: forgot.php?mode=enter_code");
                die;
            }
            break;
        case 'enter_code':
            // code...

            $code = $_POST['code'];
            $result = $db->is_code_correct($code);
            if ($result == "the code is correct") {

                $_SESSION['forgot']['code'] = $code;
                header("Location: forgot.php?mode=enter_password");
                die;
            } else {
                $error[] = $result;
            }

            break;


        case 'enter_password':
            // code...

            $login = new Login();

            $password = $_POST['password'];

            $password2 = $_POST['password2'];

            echo $password . " " . $password2;

            if ($password != $password2) {
                $error[] = "Passwords do not match";
            } elseif (!isset($_SESSION['forgot']['email']) or !isset($_SESSION['forgot']['code'])) {
                header("Location: forgot.php");
                die;
            } else {

                $db->save_password($login->encrypt_text($password));
                if (isset($_SESSION['forgot'])) {
                    unset($_SESSION['forgot']);
                }

                header("Location: login.php");
                die;
            }
            break;

        default:
            // code...
            break;
    }
}



?>
<html>

<head>

    <title>Document</title>
    <link rel="stylesheet" href="../css/log.css?v=1">

    <style type="text/css">

    </style>

</head>

<body>
    <header>
        <nav>
            <div class="bar">
                <div class="imgs">
                    <img src="../img/logos.jpg">

                </div>&nbsp;
                College Social Network
                <p style="position:relative; top:-5px"><br>Where College Connections Thrive</p>


            </div>

            <div class="signs">
                <a href="admin.php">Admin</a>
            </div>
            <div class="sign">
                <a href="signup.php">SignUp</a>
            </div>

        </nav>
    </header>
    <div id="outer">
        <div class="main">

            <?php

            switch ($mode) {
                case 'enter_email':
                    // code...
            ?>

                    <form method="POST" action="forgot.php?mode=enter_email">
                        <h3>Forgot Password</h3><br>
                        <h3>Enter your Email below</h3>

                        <span style="font-size: 12px; color:red">
                            <?php
                            foreach ($error as $err) {
                                // code...
                                echo $err . "<br>";
                            }

                            ?></span>

                        <input class="textbox" type="email" name="email" placeholder="Email" id="t1"><br>

                        <br style="clear:both;">
                        <input type="submit" value="Next" id="btn"><br><br>
                        <div><a href="login.php">Login</a></div>

                    </form>
                <?php
                    break;
                case 'enter_code':
                    // code...
                ?>
                    <form method="POST" action="forgot.php?mode=enter_code">
                        <h1>Forgot Password</h1>
                        <h3>Enter code sent to your Email </h3>
                        <span style="font-size: 12px; color:red">
                            <?php
                            foreach ($error as $err) {
                                // code...
                                echo $err . "<br>";
                            }

                            ?></span>
                        <input class="textbox" id="t1" type="text" name="code" placeholder="12345"><br>
                        <br>
                        <input type="submit" id="t1" value="Next">

                        <br><br>
                        <a href="forgot.php">
                            <input type="button" value="Start Over" id="btn">//restart process
                        </a>
                        <br><br>
                        <div><a href="login.php">Login</a></div>

                    </form>

                <?php

                    break;

                case 'enter_password':
                    // code...
                ?>
                    <form method="POST" action="forgot.php?mode=enter_password">
                        <h1>Forgot Password</h1>
                        <h3>Enter new password</h3>
                        <span style="font-size: 12px; color:red">
                            <?php
                            foreach ($error as $err) {
                                // code...
                                echo $err . "<br>";
                            }

                            ?></span>
                        <input class="textbox" type="text" name="password" placeholder="Password" id="t1"><br><br>
                        <input class="textbox" type="text" id="t1" name="password2" placeholder="Retype Password"><br>
                        <br>
                        <input type="submit" value="Next" id="btn">
                        <br><br>
                        <a href="forgot.php">
                            <input type="button" value="Start Over" id="btn">
                        </a>
                        <br><br>
                        <div><a href="login.php">Login</a></div>

                    </form>
            <?php

                    break;

                default:
                    // code...
                    break;
            }

            ?>




        </div>

    </div>
</body>

</html>