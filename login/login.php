<?php


include("../classes/loader.php");
/*
$db = new database();
$sql = "SELECT * FROM USERS";
$result = $db->read($sql);

foreach ($result as $row) {
    $id = $row['id'];
    $password = hash("sha1", $row['password']);
    $sql = "UPDATE USERS SET password = '$password'  WHERE id = '$id' LIMIT 1";
    //echo $sql;
    $db->save($sql);
}
die;*/


$email = "";
$password = "";




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = new Login();
    $result = $login->evaluate($_POST);
    //echo $result . "yoo";


    if ($result != "") {

        echo "<div style='text-align:center; font-size:12px; background-color:gray; color:white '>";
        echo "The following error occured<br>";
        echo $result;
        echo "</div>";
    } else {


        header("Location: profile.php");
        die;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
}

?>

<html>

<head>

    <title>Document</title>
    <link rel="stylesheet" href="../css/log.css?v=1">

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
            <form method="post" action="">
                Log in as Student<br><br>
                <input name="email" value="<?php echo $email; ?>" type="text" id="t1" placeholder="Email address or Phone number"><br><br>
                <input name="password" value="<?php echo $password; ?>" type="password" id="t1" placeholder="Password"><br><br>
                <input type="submit" name="submit" id="btn" value="Log in"><br>
                <a href="forgot.php">Forgot Password?</a>

            </form>
        </div>

    </div>
</body>

</html>