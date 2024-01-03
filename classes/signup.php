<?php

class SignUp
{
    private $error = ""; //used for returning error
    public function evaluate($data)
    {
        foreach ($data as $key => $value) {
            # code...
            if (empty($value)) {
                $this->error = $this->error .  $key . " is empty!<br>";
            }
            //email validation check
            if ($key == "email") {
                if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $value)) {
                    $this->error = $this->error .  $key . "Invalid email address!<br>";
                }
            }

            //name numeric or not
            if ($key == "first_name") {
                if (is_numeric($value)) {
                    $this->error = $this->error .  $key . "first_name cannot be number!<br>";
                }

                if (strstr($value, " ")) {
                    $this->error = $this->error .  $key . "first_name cannot have space!<br>";
                }
            }

            if ($key == "password") {

                $pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';


                if ((strlen($value) < 8) and (!preg_match('/^(?=[A-Z][a-z][0-9][!@#$%^&*()-_+=])[A-Za-z0-9!@#$%^&*()-_+=]*$/', $value))) {



                    $this->error = $this->error . $key . "<p>8-32 characters</p>
                    <br>
                    <p>At least one upper case</p>
                    <br>
                    <p>At least one lower case</p>
                    <br>
                    <p>At least one number</p>
                    <br>
                    <p>At least one special character</p>
                    <br>
                    <p>No space characters</p>";
                }
            }


            if ($key == "last_name") {
                if (is_numeric($value)) {
                    $this->error = $this->error .  $key . "last_name cannot be number!<br>";
                }

                if (strstr($value, " ")) {
                    $this->error = $this->error .  $key . "last_name cannot have space!<br>";
                }
            }
        }

        //check tag_name
        $db = new database();
        $data['tag_name'] = $tag_name = strtolower($data['first_name'] . $data['last_name']);


        $sql = "SELECT ID FROM USERS WHERE tag_name = '$data[tag_name]' LIMIT 1";
        $check = $db->read($sql);
        while (is_array($check)) {

            $data['tag_name'] = strtolower($data['first_name'] . $data['last_name']) . rand(0, 9999);

            $sql = "SELECT ID FROM USERS WHERE tag_name = '$data[tag_name]' LIMIT 1";

            $check = $db->read($sql);
        }


        //check userid

        $data['userid'] = $this->create_userid();

        $sql = "SELECT ID FROM USERS WHERE userid = '$data[userid]' LIMIT 1";
        $check = $db->read($sql);
        while (is_array($check)) {

            $data['userid'] = $this->create_userid();

            $sql = "SELECT ID FROM USERS WHERE userid = '$data[userid]' LIMIT 1";
            $check = $db->read($sql);
        }


        //check email

        $data['userid'] = $this->create_userid();

        $sql = "SELECT ID FROM USERS WHERE email = '$data[email]' LIMIT 1";
        $check = $db->read($sql);
        if (is_array($check)) {

            $this->error = $this->error . "Another user is already using that email<br>";
        }



        if ($this->error == "") {
            //no error
            $this->create_user($data);
        } else {
            return $this->error;
        }
    }
    public function create_user($data)
    {

        //ucfirst makes firt letter capital 
        $first_name = ucfirst($data["first_name"]);
        $last_name = ucfirst($data["last_name"]);
        $gender = $data["gender"];
        $address = $data['address'];
        $semester = $data['semester'];
        $email = $data["email"];
        $password = $data["password"];

        $userid = $data["userid"];
        $tag_name = $data["tag_name"];
        //$tag_name = strtolower($first_name . $last_name);

        // passwod hashing
        $password = hash("sha1", $password);


        //create these
        $url_address = strtoLower($first_name) . "." . strtoLower($last_name);


        $query = "INSERT INTO USERS (userid,first_name,last_name,gender,email,password,url_address,address,semester,tag_name) VALUES('$userid','$first_name','$last_name','$gender','$email','$password','$url_address','$address','$semester','$tag_name')";

        //echo $query;
        $db = new database();
        $db->save($query);
    }



    private function create_userid()
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
}
