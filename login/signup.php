<?php
include("../classes/loader.php");

$first_name = "";
$last_name = "";
$email = "";
$gender = "";
$semester = "";
$address = "";
$password = "";
$result = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $signup = new SignUp();
    $result = $signup->evaluate($_POST);


    if ($result != "") {

        //echo "<div style='text-align:center; font-size:12px; background-color:gray; color:white '>";
        //echo "The following error occured<br>";
        //echo $result;
        //echo "</div>";
    } else {
        header("Location:login.php");
        die();
    }


    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $semester = $_POST['semester'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
}





?>
<html>

<head>

    <title>Document</title>
    <link rel="stylesheet" href="../css/log.css?v=1">
    <style type="text/css">
        #scroll_bar {

            width: 60%;
            margin: auto;
            height: 80%;
            overflow: auto;
        }

        p {


            margin: auto;
            width: 60%;
            text-align: justify;


        }

        #scroll_bar::-webkit-scrollbar {
            width: 12px;
            background-color: RGB(0, 0, 100);
        }

        #scroll_bar::-webkit-scrollbar-track {}
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="bar">
                <div class="imgs">
                    <img src="../img/logos.jpg">

                </div>&nbsp;&nbsp;
                College Social Network<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Where College Connections Thrive



            </div>
            <div class="sign"><a href="login.php">Home</a></div>
        </nav>
    </header>
    <div id="outer" style="overflow-y:auto">
        <div class="main" style="position: relative; top:20px;">

            <div id="scroll_bar">
                SignUp to Social Site<br><br>
                <form method="POST">
                    <input value="<?php echo $first_name ?>" type="text" name="first_name" id="t1" placeholder="First name"><br><br>
                    <input value="<?php echo $last_name ?>" type="text" name="last_name" id="t1" placeholder="Last name"><br><br>
                    <span>Gender:</span><br>
                    <select name="gender" id="t1">
                        <option><?php echo $gender ?></option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>

                    <br>
                    <br>

                    <input value="<?php echo $semester ?>" type="text" name="semester" id="t1" placeholder="Enter ur semester"><br><br>

                    <input value="<?php echo $address ?>" type="text" name="address" id="t1" placeholder="Enter ur address"><br><br>

                    <input value="<?php echo $email ?>" type="text" name="email" id="t1" placeholder="Enter ur email"><br><br>
                    <input type="password" name="password" id="t1" placeholder="Enter ur password" value="<?php isset($password) ?: $password ?>"><br>

                    <div class="p">
                        <?php
                        if ($result) {
                            echo "Enter proper " . $result;
                        } ?>

                    </div>

                    <br>
                    <input type="password" name="password" id="t1" placeholder="Retype Password"><br><br>
                    <input type="submit" name="submit" id="btn" value="Sign up">
                </form>

            </div>
        </div>

    </div>

</body>

</html>