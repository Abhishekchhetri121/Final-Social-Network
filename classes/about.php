<?php

class About
{

    public function get_bio($id)
    {
        $db = new database();
        $sql = "SELECT first_name,last_name,email,semester,address, status FROM USERS WHERE userid = '$id' LIMIT 1";
        $row = $db->read($sql);

        if (is_array($row)) {
            return $row[0];
        } else {
            return false;
        }
    }
}
