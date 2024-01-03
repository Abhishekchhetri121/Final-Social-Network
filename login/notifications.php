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

$post = new POST();
$User = new User;
$image_class = new Image();

?>
<html>

<head>
    <title>Notifications | My Book</title>
    <link rel="stylesheet" href="../css/p.css?v=2">
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

                $db  = new database();
                $id = esc($_SESSION["SOCIAL_userid"]);

                $r2 = "SELECT userid FROM USERS WHERE status = 'Admin'";
                $op2 = $db->read($r2);
                $uid = json_decode($op2[0]['userid'], true);


                $follow = array();

                //check contents i follow

                $sql = "SELECT * FROM content_i_follow WHERE disabled =0 and userid = '$id' LIMIT 100";
                $i_follow = $db->read($sql);

                if (is_array($i_follow)) {
                    $follow = array_column($i_follow, "contentid");
                }

                if (count($follow) > 0) {
                    $str = "'" . implode("','", $follow) . "'";

                    $query = "SELECT * FROM NOTIFICATION WHERE (userid != '$id' and content_owner = '$id') OR (contentid in ($str)) OR content_owner='$uid'  ORDER 
                    BY ID DESC LIMIT 30";



                    $data = $db->read($query);
                } else {
                    $query = "SELECT * FROM NOTIFICATION WHERE userid != '$id' and content_owner = '$id' OR userid='$uid'   ORDER BY ID DESC LIMIT 30";

                    //$qu = "SELECT * FROM NOTIFICATION WHERE userid='$uid' ORDER BY ID DESC LIMIT 5 ";

                    //$note = $db->read($qu);

                    $data = $db->read($query);
                }


                ?>
                <?php

                if (is_array($data)) : ?>

                    <?php

                    foreach ($data as $notif_row) :

                        //print_r($notif_row);
                        include("single_notification.php");


                    endforeach; ?>


                <?php else : ?>

                    No notifications were found

                <?php endif; ?>






            </div>

        </div>
    </div>


</body>

</html>