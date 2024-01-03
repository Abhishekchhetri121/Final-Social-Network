<?php

class Profile
{
    public function get_profile($id)
    {
        $id = addslashes($id);
        $db = new database();
        $query = "SELECT * FROM USERS WHERE userid = $id LIMIT 1";

        return $db->read($query);
    }
}
