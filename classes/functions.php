
<?php

function paination_link()
{

    $page_number = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $page_number = ($page_number < 1) ?  1 : $page_number;

    $arr['next_page'] = "";
    $arr['prev_page'] = "";
    $arr = array();
    //get current url
    $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
    $url .= "?";

    $next_page_link = $url;
    $prev_page_link = $url;
    $page_found = false;
    $num = 0;
    foreach ($_GET as $key => $value) {

        $num++;

        if ($num == 1) {
            if ($key == "page") {

                $next_page_link .= $key . "=" . ($page_number + 1);
                $prev_page_link .= $key . "=" . ($page_number -  1);
                $page_found = true;
            } else {
                $next_page_link .= $key . "=" . $value;
                $prev_page_link .= $key . "=" . $value;
            }
        } else {

            if ($key == "page") {
                $next_page_link .= "&" . $key . "=" . ($page_number + 1);
                $prev_page_link .= "&" . $key . "=" . ($page_number - 1);
                $page_found = true;
            } else {

                $next_page_link .= "&" . $key . "=" . $value;
                $prev_page_link .= "&" . $key . "=" . $value;
            }
        }
    }

    $arr['next_page'] = $next_page_link;
    $arr['prev_page'] = $prev_page_link;


    if (!$page_found) {
        $arr['next_page'] = $next_page_link . "&page=2";
        $arr['prev_page'] = $prev_page_link . "&page=1";
    }
    return $arr;
}


function profile_visit($row)
{
    $Post = new POST();
    $myid = $_SESSION['SOCIAL_userid'];
    //profiles
    if (isset($row['gender']) and $myid == $row['userid']) {
        return true;
    }

    return false;
}

function i_own_content($row)
{
    //user table
    $Post = new POST();
    $myid = $_SESSION['SOCIAL_userid'];
    //profiles
    if (isset($row['gender']) and $myid == $row['userid']) {
        return true;
    }
    //messages
    if (isset($row['sender']) and $myid == $row['sender']) {
        return true;
    }

    //comments and posts from posts table

    if (isset($row['userid'])) {

        if ($myid == $row['postid']) {
            return true;
        } else {
            $one_post = $Post->get_single_posts($row['parent']);
            if ($myid == $one_post['postid']) {
                return true;
            }
        }
    }
    return false;
}


function add_notification($userid, $activity, $row, $tagged_user = '')
{


    $row = (object)$row; //
    $userid = esc($userid);
    $activity  = esc($activity);
    $content_owner = $row->postid;


    if ($tagged_user != "") {
        $content_owner = $tagged_user;
    }
    $date = date("Y-m-d H:i:s");
    $contentid = 0;
    $content_type = "";

    if (isset($row->userid)) {
        $contentid = $row->userid;
        $content_type = "post";

        if ($row->parent > 0) {
            $content_type = "comment";
        }
    }

    if (isset($row->gender)) {
        $content_type = "profile";
        $contentid = $row->userid;
        $content_owner = $row->userid;
    }
    $db = new database();
    $query = "INSERT INTO notification (userid,activity,content_owner,date,contentid,content_type) VALUES('$userid','$activity','$content_owner','$date','$contentid','$content_type')";
    $db->save($query);
}

function content_i_follow($userid, $row)
{
    $row = (object)$row;
    $userid = esc($userid);
    $date = date("Y-m-d H:i:s");
    $contentid = 0;
    $content_type = "";

    if (isset($row->userid)) {
        $contentid = $row->userid;
        $content_type = "post";

        if ($row->parent > 0) {
            $content_type = "comment";
        }
    }

    if (isset($row->gender)) {
        $content_type = "profile";
    }

    $query = "INSERT INTO content_i_follow(userid,date,contentid,content_type)
                            VALUES('$userid','$date','$contentid','$content_type')";

    $db = new database();
    $db->save($query);
}

function esc($value)
{
    return addslashes($value);
}


function notification_seen($id)
{

    $notification_id = esc($id);
    $userid = $_SESSION['SOCIAL_userid'];

    $db = new database();

    $query = "SELECT * FROM notification_seen WHERE userid = '$userid' and notification_id = '$notification_id' LIMIT 1";
    $check = $db->read($query);

    if (!is_array($check)) {
        $query = "INSERT INTO notification_seen (userid,notification_id) VALUES('$userid','$notification_id')";


        $db->save($query);
    }
}

function check_notification()
{
    $number = 0;

    //$notification_id = esc($id);
    $userid = $_SESSION['SOCIAL_userid'];


    $db = new database();

    //echo $notice;
    /*
    if ($notice == "notice") {

        $query = "SELECT * FROM NOTIFICATION WHERE (userid != '$userid' and content_owner != '$userid') AND activity = 'notice'  ORDER BY ID DESC LIMIT 30";
    } else {
        $query = "SELECT * FROM NOTIFICATION WHERE (userid != '$userid' and content_owner != '$userid')   ORDER BY ID DESC LIMIT 30";
    }
*/

    $follow = array();
    $sql = "SELECT * FROM content_i_follow WHERE disabled =0 and userid = '$userid' LIMIT 100";

    $i_follow = $db->read($sql);

    if (is_array($i_follow)) {
        $follow = array_column($i_follow, "contentid");
    }

    if (count($follow) > 0) {
        $str = "'" . implode("','", $follow) . "'";

        $query = "SELECT * FROM NOTIFICATION WHERE (userid != '$userid' and content_owner = '$userid') OR (contentid in ($str))  ORDER BY ID DESC LIMIT 30";
    } else {

        $query = "SELECT * FROM NOTIFICATION WHERE userid != '$userid'  AND content_owner = '$userid'   ORDER BY ID DESC LIMIT 30";
    }

    //} else {
    //  $query = "SELECT * FROM NOTIFICATION activity = 'notice' ORDER BY ID DESC LIMIT 30";
    //}
    //echo $userid;


    //$query = "SELECT * FROM NOTIFICATION WHERE (userid != '$userid' and content_owner = '$userid')   ORDER BY ID DESC LIMIT 30";
    $data = $db->read($query);

    //print_r($data);
    //die;

    //$data = $db->read($query);




    //$query = "SELECT * FROM NOTIFICATION WHERE (userid != '$userid' and content_owner != '$userid')  AND activity = 'notice' ORDER BY ID DESC LIMIT 30";




    if (is_array($data)) {

        foreach ($data as $row) {


            $query = "SELECT * FROM notification_seen WHERE userid = '$userid' and notification_id = '$row[id]' LIMIT 1";
            $check = $db->read($query);


            if (!is_array($check)) {

                $number++;
            }
        }
    }
    return $number;
}
//tagging starts
function tag($postid, $new_post_text = "")
{

    $db = new database();

    $sql = "SELECT * FROM POSTS WHERE userid = '$postid' LIMIT 1";
    $mypost = $db->read($sql);

    if (is_array($mypost)) {
        $mypost = $mypost[0];


        if ($new_post_text != "") {
            $old_post = $mypost;
            $mypost['post'] = $new_post_text;
        }

        $tags = get_tags($mypost['post']);
        foreach ($tags as $tag) {
            $sql = "SELECT * FROM USERS WHERE tag_name = '$tag' LIMIT 1";
            $tagged_user = $db->read($sql);
            if (is_array($tagged_user)) {

                $tagged_user = $tagged_user[0];
                if ($new_post_text != "") {


                    $old_tags = get_tags($old_post['post']);

                    if (!in_array($tagged_user['tag_name'], $old_tags)) {
                        add_notification($_SESSION['SOCIAL_userid'], "tag", $mypost, $tagged_user['userid']);
                    }
                } else {


                    //tag
                    add_notification($_SESSION['SOCIAL_userid'], "tag", $mypost, $tagged_user['userid']);
                }
            }
        }
    }
}

function check_tags($text)

{

    $str = "";
    $words = explode(" ", $text);

    //show($words);
    // die;
    if (is_array($words) and count($words) > 0) {

        $db = new database();
        foreach ($words as $word) {

            if (preg_match("/@[a-zA-Z_0-9\Q,.\E]+/", $word)) {


                $word = trim($word, '@');
                $word = trim($word, ',');
                $tag_name = esc(trim($word, '.'));


                $query = "SELECT * FROM USERS WHERE tag_name = '$tag_name'  LIMIT 1";
                $user_row = $db->read($query);
                if (is_array($user_row)) {



                    $user_row = $user_row[0];
                    $str .=  "<a href='profile.php?id=$user_row[userid]'>@" . $word . "</a> ";
                } else {
                    $str .= htmlspecialchars($word) . " ";
                }
            } else {
                $str .= htmlspecialchars($word) . " ";
            }
        }
    }

    if ($str != "") {
        return $str;
    }
    return htmlspecialchars($text);
}
function get_tags($text)
{

    $tags = array();
    $words = explode(" ", $text);
    if (is_array($words) and count($words) > 0) {

        $db = new database();
        foreach ($words as $word) {

            if (preg_match("/@[a-zA-Z_0-9\Q,.\E]+/", $word)) {


                $word = trim($word, '@');
                $word = trim($word, ',');
                $tag_name = esc(trim($word, '.'));

                $query = "SELECT * FROM USERS WHERE tag_name = '$tag_name'  LIMIT 1";
                $user_row = $db->read($query);
                if (is_array($user_row)) {



                    $tags[] = $word;
                }
            }
        }
    }


    return $tags;
}

function show($data)
{

    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

$URL = split_url2();
function split_url2()
{
    $url = isset($_GET['url']) ? $_GET['url'] : "index";
    $url = explode("/", filter_var(trim($url, "/"), FILTER_SANITIZE_URL));

    return $url;
}
function split_url_from_string($str)
{
    $url = isset($str) ? $str : "index";
    $url = explode("/", filter_var(trim($url, "/"), FILTER_SANITIZE_URL));

    return $url;
}

function admin_post($postid)
{
    show($postid);

    $db = new database();

    $sql = "SELECT * FROM POSTS WHERE userid = '$postid' LIMIT 2";
    $mypost = $db->read($sql);
    show($mypost);

    //die;


    //tag
    //add_notification($_SESSION['SOCIAL_userid'], "notice", $mypost);
}

function check_messages()
{
    //echo $userid . "<br>";

    //$msgid = $this->create_msgid();
    $db = new database();
    $me = esc($_SESSION['SOCIAL_userid']);
    //echo $me;



    $query = "SELECT * FROM messages WHERE (receiver = '$me'AND deleted_receiver = 0  AND seen = 0)  LIMIT 100";

    $data = $db->read($query);
    if (is_array($data)) {
        return count($data);
    }
    return 0;
}


function check_seen_threads($msgid)
{
    //echo $userid . "<br>";

    //$msgid = $this->create_msgid();
    $db = new database();
    $me = esc($_SESSION['SOCIAL_userid']);
    //echo $me;



    $query = "SELECT * FROM messages WHERE (receiver = '$me'AND deleted_receiver = 0  AND seen = 0 AND msgid = '$msgid')  LIMIT 100";

    $data = $db->read($query);
    if (is_array($data)) {
        return count($data);
    }
    return 0;
}

// online logic
if (isset($_SESSION['SOCIAL_userid'])) {
    set_online(esc($_SESSION['SOCIAL_userid']));
}
function set_online($id)
{

    if (!is_numeric($id)) {
        return;
    }

    $online = time();
    $query = "UPDATE  users SET online = '$online' WHERE userid = '$id' LIMIT 1";

    $db = new database();

    $db->save($query);
}



//admin post tagging starts





/*

function admin_tag($postid, $new_post_text = "")
{

    $db = new database();

    $sql = "SELECT * FROM POSTS WHERE userid = '$postid' LIMIT 1";
    $mypost = $db->read($sql);

    if (is_array($mypost)) {
        $mypost = $mypost[0];


        if ($new_post_text != "") {
            $old_post = $mypost;
            $mypost['post'] = $new_post_text;
        }

        $tags = get_admin_tag($mypost['post']);
        foreach ($tags as $tag) {
            $sql = "SELECT * FROM USERS WHERE admin_tag_name = '$tag' LIMIT 1";
            $tagged_user = $db->read($sql);
            if (is_array($tagged_user)) {

                $tagged_user = $tagged_user[0];
                if ($new_post_text != "") {


                    $old_tags = get_admin_tag($old_post['post']);

                    if (!in_array($tagged_user['tag_name'], $old_tags)) {
                        add_notification($_SESSION['SOCIAL_userid'], "tag", $mypost, $tagged_user['userid']);
                    }
                } else {


                    //tag
                    add_notification($_SESSION['SOCIAL_userid'], "tag", $mypost, $tagged_user['userid']);
                }
            }
        }
    }
}

function check_admin_tags($text)

{

    $str = "";
    $words = explode(" ", $text);
    if (is_array($words) and count($words) > 0) {

        $db = new database();
        foreach ($words as $word) {

            if (preg_match("/@[a-zA-Z_0-9\Q,.\E]+/", $word)) {


                $word = trim($word, '@');
                $word = trim($word, ',');
                $tag_name = esc(trim($word, '.'));


                $query = "SELECT * FROM USERS WHERE admin_tag_name = '$tag_name'  LIMIT 1";
                $user_row = $db->read($query);
                if (is_array($user_row)) {



                    $user_row = $user_row[0];
                    $str .=  "<a href='profile.php?id=$user_row[userid]'>@" . $word . "</a> ";
                } else {
                    $str .= htmlspecialchars($word) . " ";
                }
            } else {
                $str .= htmlspecialchars($word) . " ";
            }
        }
    }

    if ($str != "") {
        return $str;
    }
    return htmlspecialchars($text);
}
function get_admin_tag($text)
{

    $tags = array();
    $words = explode(" ", $text);
    if (is_array($words) and count($words) > 0) {

        $db = new database();
        foreach ($words as $word) {

            if (preg_match("/@[a-zA-Z_0-9\Q,.\E]+/", $word)) {


                $word = trim($word, '@');
                $word = trim($word, ',');
                $tag_name = esc(trim($word, '.'));

                $query = "SELECT * FROM USERS WHERE tag_name = '$tag_name'  LIMIT 1";
                $user_row = $db->read($query);
                if (is_array($user_row)) {



                    $tags[] = $word;
                }
            }
        }
    }


    return $tags;
}


function check_admin_notification()
{
    $number = 0;

    //$notification_id = esc($id);
    $userid = $_SESSION['SOCIAL_userid'];


    $db = new database();

    //echo $notice;
    /*
    if ($notice == "notice") {

        $query = "SELECT * FROM NOTIFICATION WHERE (userid != '$userid' and content_owner != '$userid') AND activity = 'notice'  ORDER BY ID DESC LIMIT 30";
    } else {
        $query = "SELECT * FROM NOTIFICATION WHERE (userid != '$userid' and content_owner != '$userid')   ORDER BY ID DESC LIMIT 30";
    }
*/
/*
    $follow = array();
    $sql = "SELECT * FROM content_i_follow WHERE disabled =0 and userid = '$userid' LIMIT 100";

    $i_follow = $db->read($sql);

    if (is_array($i_follow)) {
        $follow = array_column($i_follow, "contentid");
    }

    if (count($follow) > 0) {
        $str = "'" . implode("','", $follow) . "'";

        $query = "SELECT * FROM NOTIFICATION WHERE (userid != '$userid' and content_owner = '$userid') OR (contentid in ($str))  ORDER BY ID DESC LIMIT 30";
    } else {

        $query = "SELECT * FROM NOTIFICATION WHERE userid != '$userid'  AND content_owner = '$userid'   ORDER BY ID DESC LIMIT 30";
    }

    //} else {
    //  $query = "SELECT * FROM NOTIFICATION activity = 'notice' ORDER BY ID DESC LIMIT 30";
    //}
    //echo $userid;


    //$query = "SELECT * FROM NOTIFICATION WHERE (userid != '$userid' and content_owner = '$userid')   ORDER BY ID DESC LIMIT 30";
    $data = $db->read($query);

    //print_r($data);
    //die;

    //$data = $db->read($query);




    //$query = "SELECT * FROM NOTIFICATION WHERE (userid != '$userid' and content_owner != '$userid')  AND activity = 'notice' ORDER BY ID DESC LIMIT 30";




    if (is_array($data)) {

        foreach ($data as $row) {


            $query = "SELECT * FROM notification_seen WHERE userid = '$userid' and notification_id = '$row[id]' LIMIT 1";
            $check = $db->read($query);


            if (!is_array($check)) {

                $number++;
            }
        }
    }
    return $number;
}
*/