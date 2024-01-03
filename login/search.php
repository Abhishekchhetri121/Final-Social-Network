<?php

include("../classes/loader.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['SOCIAL_userid']);

if (isset($_GET['find'])) {
    $find = addslashes($_GET['find']);
    //% says loook for any character i.s abh than abhishek or abhi
    $sql = "SELECT * FROM USERS WHERE first_name like '%$find%' || last_name like '%$find%' LIMIT 30 ";

    $db = new database();
    $result = $db->read($sql);
}



?>
<html>

<head>
    <title>People | My Book</title>
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
                <?php

                $User = new User();
                $image_class = new Image();
                if (is_array($result)) {
                    foreach ($result as $row) {

                        $row = $User->get_user($row['userid']);
                        include("user.php");
                    }
                } else {
                    echo "No results were found";
                }
                ?>
                <br style="clear:both">
            </div>

        </div>
    </div>
    </div>
</body>

</html>