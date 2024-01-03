<div id="d2" style="width: 97.5%;  text-align:center; background-color:white">
    <div style="padding :20px"></div>

    <?php
    //print_r($op);
    //$post_class = new Post();

    $user_class = new User();
    $following = $user_class->get_following($user_data['userid'], "user");
    //$image_class = new Image();

    if (is_array($following)) {

        foreach ($following as $follow) {

            $row = $user_class->get_user($follow['userid']);

            include("user.php");
        }
    } else {
        echo "No followers found";
    }
