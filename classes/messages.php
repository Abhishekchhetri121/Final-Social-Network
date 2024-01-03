<?php
class Messages
{
    private $error = "";
    public function send($data, $files, $receiver)
    {
        $image_class = new Image();
        if (!empty($data['message']) || !empty($files['file']['name'])) {

            $myimage = "";
            $has_image = 0;


            //from
            if (!empty($files['file']['name'])) {


                $userid = esc($_SESSION['SOCIAL_userid']);
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

            //till here

            $message = "";
            if (isset($data['message'])) {
                $message = esc($data['message']);
            }
            $tags = array();
            $tags = get_tags($message);
            $tags = json_encode($tags);

            if (trim($message) == "" and $has_image == 0) {

                $this->error .= "Please type something  to send<br>";
            }
            if ($this->error == "") {


                $msgid = $this->create_msgid(60);

                $sender = esc($_SESSION['SOCIAL_userid']);
                $receiver = esc($receiver);

                //$msgid = $this->create_msgid();




                $db = new database();

                // checking if thread exists
                $query = "SELECT * FROM messages WHERE (sender = '$sender' AND receiver = '$receiver') OR (sender = '$receiver' AND receiver = '$sender')  ORDER BY id DESC LIMIT 1";

                $data = $db->read($query);

                if (is_array($data)) {
                    $msgid = $data[0]['msgid'];
                }

                //thread checking stops



                $file = esc($myimage);


                $query = "INSERT INTO messages (sender,msgid,receiver,message,file,tags) VALUES('$sender','$msgid','$receiver','$message','$file','$tags')";

                $db->save($query);




                //tag($msgid);
            }
        } else {

            $this->error .= "Please type something  to send<br>";
        }
        return $this->error;
    }


    public function read($userid)
    {
        //echo $userid . "<br>";

        //$msgid = $this->create_msgid();
        $db = new database();
        $me = esc($_SESSION['SOCIAL_userid']);
        //echo $me;
        $userid = esc($userid);


        $query = "SELECT * FROM messages WHERE ((sender = '$me'  AND receiver = '$userid' )AND deleted_sender = 0) OR ((sender = '$userid' AND receiver = '$me')AND deleted_receiver = 0)  ORDER BY id DESC LIMIT 10";

        $data = $db->read($query);
        if (is_array($data)) {

            //set seen to 1
            $msgid = $data[0]['msgid'];
            $query = "UPDATE MESSAGES SET SEEN = 1 WHERE receiver = '$me' AND  msgid = '$msgid'";

            $db->save($query);

            sort($data);
        }
        return $data;
    }
    public function read_threads()
    {

        $db = new database();
        $me = esc($_SESSION['SOCIAL_userid']);


        $query = "SELECT t1.* FROM `messages` AS t1 JOIN (SELECT id , msgid , max(date) mydate FROM MESSAGES WHERE ((sender = '$me'  AND deleted_sender = 0) OR  (receiver = '$me' AND deleted_receiver = 0)) GROUP BY msgid) AS m ON t1.msgid = m.msgid AND m.mydate = t1.date   GROUP BY msgid;";



        $data = $db->read($query);
        if (is_array($data)) {
            sort($data);
        }
        return $data;
    }


    public function read_one_thread($msgid)
    {
        //echo $userid . "<br>";

        $msgid = esc($msgid);
        $db = new database();
        $me = esc($_SESSION['SOCIAL_userid']);


        $query = "SELECT t1.* FROM `messages` AS t1 JOIN (SELECT id , msgid , max(date) mydate FROM MESSAGES WHERE (sender = '$me'  or receiver = '$me' ) AND msgid = '$msgid' GROUP BY msgid) AS m ON t1.msgid = m.msgid AND m.mydate = t1.date   GROUP BY msgid;";



        $data = $db->read($query);
        if (is_array($data)) {
            return ($data[0]);
        }
        return false;
    }








    public function read_1($id)
    {
        //$msgid = $this->create_msgid();
        $id = (int)$id;
        $db = new database();
        $me = esc($_SESSION['SOCIAL_userid']);
        //$userid = esc($userid);



        $query = "SELECT * FROM messages WHERE (sender = '$me' OR receiver = '$me') AND id='$id' LIMIT 1";

        $data = $db->read($query);

        if (is_array($data)) {
            return $data[0];
        }

        return false;
    }



    public function delete_one($id)
    {
        $id = (int)$id;
        //$msgid = $this->create_msgid();
        $db = new database();
        $me = esc($_SESSION['SOCIAL_userid']);
        //$userid = esc($userid);


        $query = "SELECT * FROM messages WHERE (sender = '$me' OR receiver = '$me') AND id='$id' LIMIT 1";

        $data = $db->read($query);

        if (is_array($data)) {

            $data = $data[0];

            if ($data['sender'] == $me) {
                $query = "UPDATE messages SET deleted_sender = 1  WHERE id='$id' LIMIT 1";
            } else {
                $query = "UPDATE messages SET deleted_receiver = 1  WHERE id='$id' LIMIT 1";
            }
            $db->save($query);
        }

        return false;
    }






    public function delete_one_thread($msgid)
    {
        $msgid = esc($msgid);
        //$msgid = $this->create_msgid();
        $db = new database();
        $me = esc($_SESSION['SOCIAL_userid']);
        //$userid = esc($userid);


        $query = "SELECT * FROM messages WHERE (sender = '$me' OR receiver = '$me') AND msgid='$msgid' ";

        $data = $db->read($query);

        if (is_array($data)) {

            foreach ($data as $row) {


                $myid = $row['id'];

                if ($row['sender'] == $me) {
                    $query = "UPDATE messages SET deleted_sender = 1  WHERE id='$myid' LIMIT 1";
                } else {
                    $query = "UPDATE messages SET deleted_receiver = 1  WHERE id='$myid' LIMIT 1";
                }
                $db->save($query);
            }
        }

        return false;
    }


    private function create_msgid($length)
    {
        //fpr creation of random files

        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "-", "_");
        $text = "";

        $length =  rand(4, $length);
        for ($i = 0; $i < $length; $i++) {

            $random = rand(0, 63);
            $text .= $array[$random];
        }
        return $text;
    }
}
