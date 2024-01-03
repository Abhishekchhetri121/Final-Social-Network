<?php

/*class POST
{

    private $error = "";
    public function create_post($userid, $data, $files)
    {
        $image_class = new Image();
        if (
            !empty($data['post']) || !empty($files['file']['name']) ||
            isset($data['is_profile']) || isset($data['is_cover'])
        ) {

            $myimage = "";
            $has_image = 0;
            $is_cover = 0;
            $is_profile = 0;
            if (isset($data['is_profile']) || isset($data['is_cover'])) {
                $myimage = $files;
                $has_image = 1;
                if (isset($data['is_cover'])) {
                    $is_cover = 1;
                }
                if (isset($data['is_profile'])) {
                    $is_profile = 1;
                }
            } else {
                //from
                if (!empty($files['file']['name'])) {


                    $folder = "uploads/" . $userid . "/";
                    //$filename = "uploads/" . $_FILES['file']['name'];

                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true); //making of folder true b/c if     uploads doesn't exists then creates
                    }


                    $myimage = $folder . $image_class->generate_filename(15) . ".jpg";
                    move_uploaded_file($_FILES['file']['tmp_name'], $myimage);

                    $image_class->resize_image($myimage, $myimage, 1500, 1500);



                    $has_image = 1;
                }
            }


            //till here

            $post = "";
            if (isset($data['post'])) {
                $post = addslashes($data['post']);
            }

            $tags = array();
            $tags = get_admin_tag($post);

            $tags = json_encode($tags);

            $postid = $this->create_postid();
            $parent = 0;

            $db = new database();
            if (isset($data['parent']) and is_numeric($data['parent'])) {

                $parent = $data['parent'];

                $mypost = $this->get_single_posts($data['parent']);

                if (is_array($mypost) and $mypost['postid'] != $userid) {

                    //following items


                    content_i_follow($userid, $mypost);




                    //add notification
                    add_notification($_SESSION['SOCIAL_userid'], "comment", $mypost);
                    //add_notification($_SESSION['SOCIAL_userid'], "notice", $mypost);
                }
                $sql = "UPDATE POSTS SET comments = comments + 1 WHERE userid = '$parent' LIMIT 1";
                $db->save($sql);
            }

            show($tags);
            die;
            $query = "INSERT INTO posts (postid,userid,post,image,has_image,is_profile,is_cover,parent,admin_tag) VALUES('$userid','$postid','$post','$myimage','$has_image','$is_cover','$is_profile','$parent','$tags')";

            $db->save($query);
            //notify that those were tagged
            admin_tag($postid);
        } else {

            $this->error .= "Please type something else to post!!<br>";
        }
        return $this->error;
    }
}
*/