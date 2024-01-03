<?php

class User
{
    public function get_data($id)
    {
        $db = new database();
        $query = "SELECT * FROM USERS WHERE userid = '$id' LIMIT 1";
        $result = $db->read($query);


        if ($result) {

            $row = $result[0];
            return $row;
        } else {
            return false;
        }
    }

    public function get_user($id)
    {
        $db = new database();
        $query = "SELECT * FROM USERS WHERE userid = '$id' LIMIT 1";
        $result = $db->read($query);

        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function get_friends($fid)
    {
        $db = new database();
        $query = "SELECT * FROM USERS WHERE userid != '$fid'";
        $result = $db->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function get_following($id, $type)
    {
        $db = new database();
        if (is_numeric($id)) {
            //get likes details
            $sql = "SELECT following FROM follow WHERE type = '$type' && userid = '$id' LIMIT 1 ";
            $result = $db->read($sql);


            if (is_array($result)) {
                //already liked posts ar eny other
                $likes = json_decode($result[0]['following'], true);
                return $likes;
            }
        }
        return false;
    }


    public function follow_user($id, $type, $SOCIAL_userid)
    {

        if ($type == "user") {


            $db = new database();

            //save likes details
            $sql = "SELECT following FROM follow WHERE type = '$type' && userid = '$SOCIAL_userid' LIMIT 1 ";
            $result = $db->read($sql);



            if (is_array($result)) {
                //already liked posts ar eny other
                $likes = json_decode($result[0]['following'], true);




                $user_ids = array_column($likes, "userid");


                if (!in_array($id, $user_ids)) {

                    $arr["userid"] = $id;
                    $arr["date"] = date("Y-m-d H:i:s");

                    $likes[] = $arr;

                    $likes_string = json_encode($likes); //json : js object notation way of converting array into string
                    $sql = "UPDATE follow SET following = '$likes_string' WHERE type = '$type' && userid = '$SOCIAL_userid' LIMIT 1 ";
                    $db->save($sql);


                    $user = new User();
                    $single_post = $user->get_user($id);


                    add_notification($_SESSION['SOCIAL_userid'], "following", $single_post);
                } else {
                    $key = array_search($id, $user_ids);

                    unset($likes[$key]);


                    $likes_string = json_encode($likes);
                    $sql = "UPDATE follow SET following = '$likes_string' WHERE type = '$type' && userid = '$SOCIAL_userid' LIMIT 1 ";
                    $db->save($sql);
                }
            } else {
                //first time likes
                $arr["userid"] = $id;
                $arr["date"] = date("Y-m-d H:i:s");
                $arr2[] = $arr;

                $following = json_encode($arr2); //json : js object notation way of converting array into string
                $sql = "INSERT INTO follow (type,userid,following) VALUES('$type','$SOCIAL_userid','$following')";
                $db->save($sql);


                $user = new User();
                $single_post = $user->get_user($id);
                add_notification($_SESSION['SOCIAL_userid'], "following", $single_post);
            }
        }
    }

    //followers
    /*
    public function get_followers($id, $type)
    {

        $db = new database();
        if (is_numeric($id)) {
            //get likes details
            $sql = "SELECT followers FROM followers WHERE type = '$type' && userid = '$id' LIMIT 1 ";
            $result = $db->read($sql);


            if (is_array($result)) {
                //already liked posts ar eny other
                $likes = json_decode($result[0]['followers'], true);
                return $likes;
            }
        }
        return false;
    }


    public function followers($id, $type, $SOCIAL_userid)
    {

        $db = new database();

        //save likes details
        $sql = "SELECT {$type}s FROM followers WHERE type = '$type' && userid = '$id' LIMIT 1 ";
        $result = $db->read($sql);



        if (is_array($result)) {
            //already liked posts ar eny other
            $likes = json_decode($result[0]['followers'], true);




            $user_ids = array_column($likes, "userid");


            if (!in_array($SOCIAL_userid, $user_ids)) {

                $arr["userid"] = $SOCIAL_userid;
                $arr["date"] = date("Y-m-d H:i:s");

                $likes[] = $arr;

                $likes_string = json_encode($likes); //json : js object notation way of converting array into string
                $sql = "UPDATE followers SET followers = '$likes_string' WHERE type = '$type' && userid = '$id' LIMIT 1 ";
                $db->save($sql);

                $sql = "UPDATE {$type}s SET followers = followers + 1 WHERE {$type}id = '$id' LIMIT 1 ";
                $db->save($sql);
            } else {
                $key = array_search($SOCIAL_userid, $user_ids);

                unset($likes[$key]);


                $likes_string = json_encode($likes);
                $sql = "UPDATE followers SET followers = '$likes_string' WHERE type = '$type' && userid = '$id' LIMIT 1 ";
                $db->save($sql);

                //increment the right table
                $sql = "UPDATE {$type}s SET followers = followers - 1 WHERE {$type}id = '$id' LIMIT 1 ";
                $db->save($sql);
            }
        } else {
            //first time likes
            $arr["userid"] = $SOCIAL_userid;
            $arr["date"] = date("Y-m-d H:i:s");
            $arr2[] = $arr;

            $following = json_encode($arr2); //json : js object notation way of converting array into string
            $sql = "INSERT INTO {$type}s (type,userid,followers) VALUES('$type','$SOCIAL_userid','$following')";
            $db->save($sql);

            //increment the right table

            $sql = "UPDATE {$type}s SET followers = followers+ 1 WHERE {$type}id = '$id' LIMIT 1 ";
            $db->save($sql);
        }
    }*/
}
