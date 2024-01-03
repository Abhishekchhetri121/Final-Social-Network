<?php
class Login
{
    private $error = "";

    public function evaluate($data)
    {

        $email = addslashes($data["email"]); //addslashes escapes certain strings eg: 'jon\'s data' 
        $password = addslashes($data["password"]);



        $query = "SELECT * FROM USERS WHERE email = '$email' limit 1";

        //echo $query;
        $log = new Login();
        $db = new database();
        $result =  $db->read($query);



        if ($result) {


            $row =  $result[0];

            if ($this->encrypt_text($password) == $row['password']) {

                //session creation
                $_SESSION['SOCIAL_userid'] = $row['userid'];
            } else {
                $this->error .= "wrong email or password<br>";
            }
        } else {
            $this->error .= "Wrong email or password<br>";
        }

        return $this->error;
    }

    public function check_status($ip)
    {
        $email = addslashes($ip["email"]); //addslashes escapes certain strings eg: 'jon\'s data' 
        $password = addslashes($ip["password"]);

        $query = "SELECT status FROM USERS WHERE email = '$email' and status ='Admin' limit 1";

        $db = new database();
        $res =  $db->read($query);
        if ($res == "") {
            return false;
        } else {
            return $res[0];
        }
    }
    public function check_login($id, $redirect = true)
    {
        if (is_numeric($id)) {
            $query = "SELECT * FROM USERS WHERE userid = '$id' limit 1";

            //echo $query;

            $db = new database();
            $result =  $db->read($query);

            if ($result) {
                $user_data = $result[0];
                return $user_data;
            } else {
                if ($redirect) {
                    header("Location:login.php");
                    die();
                } else {
                    $_SESSION['SOCIAL_userid'] = 0;
                }
            }
        } else {
            if ($redirect) {
                header("Location:login.php");
                die();
            } else {
                $_SESSION['SOCIAL_userid'] = 0;
            }
        }
    }

    function encrypt_text($text)
    {
        $text = hash("sha1", $text);
        return $text;
    }
}
