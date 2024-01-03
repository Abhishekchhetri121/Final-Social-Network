<?php

class POST
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

                    $allowed[] = "image/jpeg";
                    if (in_array($_FILES['file']['type'], $allowed)) {
                        $myimage = $folder . $image_class->generate_filename(15) . ".jpg";
                        move_uploaded_file($_FILES['file']['tmp_name'], $myimage);

                        $image_class->resize_image($myimage, $myimage, 1500, 1500);



                        $has_image = 1;
                    } else {
                        $this->error .= "The selected image is not valid type.!<br>";
                    }
                }
            }


            //till here

            $post = "";
            if (isset($data['post'])) {
                $post = addslashes($data['post']);
            }

            if ($this->error == "") {
                $tags = array();
                $tags = get_tags($post);

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




                $query = "INSERT INTO posts (postid,userid,post,image,has_image,is_profile,is_cover,parent,tags) VALUES('$userid','$postid','$post','$myimage','$has_image','$is_cover','$is_profile','$parent','$tags')";

                $db->save($query);
                //notify that those were tagged
                tag($postid);







                $user = new User();

                $res = "SELECT status FROM USERS WHERE status = 'Admin'";
                $op = $db->read($res);
                // $ps = "SELECT postid FROM POSTS WHERE postid ='$op'";

                $r2 = "SELECT userid FROM USERS WHERE status = 'Admin'";
                $op2 = $db->read($r2);


                $uid = json_decode($op2[0]['userid'], true);



                //$s = json_decode($op[0]['status'], true);



                $s = implode(" ", $op[0]);


                //add_notification($_SESSION['SOCIAL_userid'], "notice", $postid);



                $single_post = $this->get_single_posts($postid);


                /* if ($single_post['postid'] == $uid) {
                add_notification($_SESSION['SOCIAL_userid'], "notice", $single_post);
           }*/
            }
        } else {

            $this->error .= "Please type something else to post!!<br>";
        }
        return $this->error;
    }

    private function create_postid()
    {

        $length = rand(4, 19);
        $number = "";
        for ($i = 0; $i < $length; $i++) {

            $new_rand = rand(0, 9);
            $number = $number . $new_rand;
            # code...
        }
        return $number;
    }


    public function get_comments($id)
    {
        $page_number = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $page_number = ($page_number < 1) ?  1 : $page_number;

        $limit = 5;
        $offset = ($page_number - 1) * $limit;

        $query = "SELECT * FROM posts WHERE parent = '$id' ORDER BY id ASC LIMIT $limit offset $offset ";
        $db = new database();
        $result = $db->read($query);

        if ($result) {

            return $result;
        } else {
            return false;
        }
    }
    public function get_posts($id)
    {

        $page_number = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $page_number = ($page_number < 1) ?  1 : $page_number;

        $limit = 5;
        $offset = ($page_number - 1) * $limit;
        $query = "SELECT * FROM posts WHERE parent =0 and  postid = '$id' ORDER BY id DESC LIMIT $limit offset $offset ";
        $db = new database();
        $result = $db->read($query);

        if ($result) {

            return $result;
        } else {
            return false;
        }
    }

    public function get_single_posts($postid)
    {

        if (!is_numeric($postid)) {
            return false;
        }

        $query = "SELECT * FROM posts WHERE userid = '$postid'  LIMIT 1";
        $db = new database();
        $result = $db->read($query);

        if ($result) {

            return $result[0];
        } else {
            return false;
        }
    }

    public function delete_post($postid)
    {


        if (!is_numeric($postid)) {
            return false;
        }
        $db = new database();
        $Post = new Post();
        $sql = "SELECT parent FROM POSTS WHERE userid = '$postid' LIMIT 1";
        $result = $db->read($sql);



        $one_post = $Post->get_single_posts($postid);

        if (is_array($result)) {
            if ($result[0]['parent'] > 0) {

                $parent = $result[0]['parent'];

                $sql = "UPDATE POSTS SET comments = comments - 1 WHERE userid = '$parent' LIMIT 1";
                $db->save($sql);
            }
        }
        //elete any images and thumbnails
        $query = "DELETE  FROM posts WHERE userid = '$postid'  LIMIT 1";

        $db->delete($query);


        if ($one_post['image'] != "" and file_exists($one_post['image'])) {
            unlink($one_post['image']);
        }

        if ($one_post['image'] != "" and file_exists($one_post['image'] . "_post_thumb")) {
            unlink($one_post['image'] . "_post_thumb");
        }

        if ($one_post['image'] != "" and file_exists($one_post['image'] . "_cover_thumb")) {
            unlink($one_post['image'] . "_cover_thumb");
        }

        //delete all coments
        //$query = "DELETE  FROM posts WHERE parent = '$postid'";

        //$db->delete($query);
    }

    public function owned_post($postid, $SOCIAL_userid, $status = "") //0 b/c optional
    {
        if (!is_numeric($postid)) {
            return false;
        }
        $query = "SELECT *  FROM posts WHERE userid = '$postid'  LIMIT 1";
        $db = new database();
        $result = $db->read($query);
        if (is_array($result)) {

            //print_r($result);
            if ($result[0]['postid'] == $SOCIAL_userid) {
                return true;
            }
        }
        return false;
    }

    public function overall_post($postid, $SOCIAL_userid, $status) //0 b/c optional
    {
        if (!is_numeric($postid)) {
            return false;
        }
        $query = "SELECT *  FROM posts WHERE userid = '$postid'  LIMIT 1";
        $db = new database();
        $result = $db->read($query);
        if (is_array($result)) {

            //print_r($result);
            if ($result[0]['postid'] != $SOCIAL_userid) {
                return true;
            }
        }
        return false;
    }



    public function like_post($id, $type, $SOCIAL_userid)
    {

        $db = new database();
        //save likes details
        $sql = "SELECT likes FROM likes WHERE type = '$type' && contentid = '$id' LIMIT 1 ";
        $result = $db->read($sql);


        if (is_array($result)) {
            //already liked posts ar eny other
            $likes = json_decode($result[0]['likes'], true);

            $user_ids = array_column($likes, "userid");

            if (!in_array($SOCIAL_userid, $user_ids)) {

                $arr["userid"] = $SOCIAL_userid;
                $arr["date"] = date("Y-m-d H:i:s");

                $likes[] = $arr;

                $likes_string = json_encode($likes); //json : js object notation way of converting array into string
                $sql = "UPDATE likes SET likes = '$likes_string' WHERE type = '$type' && contentid = '$id' LIMIT 1 ";
                $db->save($sql);

                //increment the right table
                $sql = "UPDATE {$type}s SET likes = likes + 1 WHERE {$type}id = '$id' LIMIT 1 ";
                $db->save($sql);









                //
            } else {


                $key = array_search($SOCIAL_userid, $user_ids);

                unset($likes[$key]);

                $likes_string = json_encode($likes);
                $sql = "UPDATE likes SET likes = '$likes_string' WHERE type = '$type' && contentid = '$id' LIMIT 1 ";
                $db->save($sql);

                //increment the right table
                $sql = "UPDATE {$type}s SET likes = likes - 1 WHERE {$type}id = '$id' LIMIT 1 ";
                $db->save($sql);
            }
        } else {
            //first time likes
            $arr["userid"] = $SOCIAL_userid;
            $arr["date"] = date("Y-m-d H:i:s");
            $arr2[] = $arr;

            $likes = json_encode($arr2); //json : js object notation way of converting array into string
            $sql = "INSERT INTO likes (type,contentid,likes) VALUES('$type','$id','$likes')";
            $db->save($sql);

            //increment the right table

            $sql = "UPDATE {$type}s SET likes = likes + 1 WHERE {$type}id = '$id' LIMIT 1 ";
            $db->save($sql);
        }
    }

    public function get_ff($id, $type)
    {
        $db = new database();
        if (is_numeric($id)) {
            //get likes details
            $sql = "SELECT likes FROM likes WHERE type = '$type' && contentid = '$id' LIMIT 1 ";
            $result = $db->read($sql);


            if (is_array($result)) {
                //already liked posts ar eny other
                $likes = json_decode($result[0]['likes'], true);
                return $likes;
            }
        }
        return false;
    }





    public function edit_post($data, $files)
    {
        $image_class = new Image();
        if (
            !empty($data['post']) || !empty($files['file']['name'])
        ) {

            $myimage = "";
            $has_image = 0;

            //from
            if (!empty($files['file']['name'])) {


                $folder = "uploads/" . $data . "/";
                //$filename = "uploads/" . $_FILES['file']['name'];

                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true); //making of folder true b/c if     uploads doesn't exists then creates
                }


                $myimage = $folder . $image_class->generate_filename(15) . ".jpg";
                move_uploaded_file($_FILES['file']['tmp_name'], $myimage);

                $image_class->resize_image($myimage, $myimage, 1500, 1500);



                $has_image = 1;
            }



            //till here

            $post = "";
            if (isset($data['post'])) {
                $post = addslashes($data['post']);
            }
            $postid = addslashes($data['postid']);

            if ($has_image) {
                $query = "UPDATE posts SET post = '$post', image = '$myimage' WHERE userid = '$postid' LIMIT 1";
            } else {
                $query = "UPDATE posts SET post = '$post' WHERE userid = '$postid' LIMIT 1";
            }
            //notify that those were tagged
            tag($postid, $post);
            $db = new database();
            $db->save($query);
        } else {

            $this->error .= "Please type else to post!!<br>";
        }
        return $this->error;
    }


    //real post likes

    public function get_likes($id, $type)
    {
        $db = new database();
        if (is_numeric($id)) {
            //get likes details
            $sql = "SELECT likes FROM likes WHERE type = '$type' && contentid = '$id' LIMIT 1 ";
            $result = $db->read($sql);


            if (is_array($result)) {
                //already liked posts ar eny other
                $likes = json_decode($result[0]['likes'], true);
                return $likes;
            }
        }
        return false;
    }

    public function likes_post($id, $type, $SOCIAL_userid)
    {
        if ($type == "post") {
            $db = new database();
            //save likes details
            $sql = "SELECT likes FROM likes WHERE type = 'post' && contentid = '$id' LIMIT 1 ";
            $result = $db->read($sql);


            if (is_array($result)) {
                //already liked posts ar eny other
                $likes = json_decode($result[0]['likes'], true);

                $user_ids = array_column($likes, "userid");

                if (!in_array($SOCIAL_userid, $user_ids)) {

                    $arr["userid"] = $SOCIAL_userid;
                    $arr["date"] = date("Y-m-d H:i:s");

                    $likes[] = $arr;

                    $likes_string = json_encode($likes); //json : js object notation way of converting array into string
                    $sql = "UPDATE likes SET likes = '$likes_string' WHERE type = 'post' && contentid = '$id' LIMIT 1 ";
                    $db->save($sql);

                    //increment the right table
                    $sql = "UPDATE posts SET likes = likes + 1 WHERE userid = '$id' LIMIT 1 ";
                    $db->save($sql);


                    if ($type != "user") {
                        $post = new Post();

                        $single_post = $post->get_single_posts($id);

                        add_notification($_SESSION['SOCIAL_userid'], "like", $single_post);
                    }


                    //
                } else {


                    $key = array_search($SOCIAL_userid, $user_ids);

                    unset($likes[$key]);

                    $likes_string = json_encode($likes);
                    $sql = "UPDATE likes SET likes = '$likes_string' WHERE type = 'post' && contentid = '$id' LIMIT 1 ";
                    $db->save($sql);

                    //increment the right table
                    $sql = "UPDATE posts SET likes = likes - 1 WHERE userid = '$id' LIMIT 1 ";
                    $db->save($sql);
                }
            } else {
                //first time likes
                $arr["userid"] = $SOCIAL_userid;
                $arr["date"] = date("Y-m-d H:i:s");
                $arr2[] = $arr;

                $likes = json_encode($arr2); //json : js object notation way of converting array into string
                $sql = "INSERT INTO likes (type,contentid,likes) VALUES('$type','$id','$likes')";
                $db->save($sql);

                //increment the right table

                $sql = "UPDATE posts SET likes = likes+ 1 WHERE userid = '$id' LIMIT 1 ";
                $db->save($sql);


                if ($type != "user") {
                    $post = new Post();
                    $single_post = $post->get_single_posts($id);
                    add_notification($_SESSION['SOCIAL_userid'], "like", $single_post);
                }
            }
        }
    }
}
