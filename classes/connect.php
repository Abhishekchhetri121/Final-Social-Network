<?php
//used of oop b/c of no need of typing multiple times 
class database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "mybook_db";
    function connect()
    {
        $connection = mysqli_connect($this->host, $this->username, $this->password, $this->db);
        return $connection;
    }

    function read($query)
    {
        $conn = $this->connect();
        $result = mysqli_query($conn, $query);

        if (!$result) {
            return false;
        } else {
            $data = false;
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        }
    }



    function save($query)
    {
        $conn = $this->connect();
        $result = mysqli_query($conn, $query);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    function delete($query)
    {
        $conn = $this->connect();
        $result = mysqli_query($conn, $query);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    function send_email($email)
    {

        $expire = time() + (60 * 1); //1 minutes
        $code = rand(10000, 99999); //5 digits of code
        $email = addslashes($email);
        $query = "INSERT into codes(email,code,expire) VALUES('$email','$code','$expire')";

        $this->save($query);



        //real email function
        send_mail($email, "Password reset", "Yours code is " . $code);
    }

    function save_password($password)
    {


        //$password = password_hash($password,PASSWORD_DEFAULT);
        $email = addslashes($_SESSION['forgot']['email']);
        $query = "UPDATE USERS SET password = '$password' WHERE email = '$email' LIMIT 1";

        $this->save($query);
    }

    function valid_email($email)
    {

        $email = addslashes($email);
        $query = "SELECT * FROM  USERS WHERE email = '$email' LIMIT 1";

        $result = $this->read($query);

        if ($result) {
            if (($result) > 0) {

                return true;
            }
        }


        return false;
        //$this->save($query);
    }



    function is_code_correct($code)
    {
        $code = addslashes($code);
        $expire = time(); // current time
        $email = addslashes($_SESSION['forgot']['email']);

        $query = "SELECT * FROM codes WHERE code = '$code' AND email = '$email'  ORDER BY id DESC LIMIT 1";

        $result = $this->read($query);

        if ($result) {
            if (($result) > 0) {

                //print_r($result);

                $row = $result[0]['expire'];



                if ($row > $expire) { // mean like $row 5 min and $expire 3 min

                    return "the code is correct";
                } else {
                    return "the code is expired";
                }
                //return true;
            } else {
                return "the code is incorrect";
            }
        }

        return "the code is incorrect";
    }
}
