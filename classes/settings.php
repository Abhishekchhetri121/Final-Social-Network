<?php

class Settings
{

    public function get_settings($id)
    {
        $db = new database();
        $sql = "SELECT * FROM USERS WHERE userid = '$id' LIMIT 1";
        $row = $db->read($sql);

        if (is_array($row)) {
            return $row[0];
        } else {
            return false;
        }
    }

    public function save_settings($data, $id)
    {
        $password = $data['password'];

        $db = new database();
        if (strlen($password) < 30) {
            if ($data['password'] == $data['password2']) {
                $data['password'] = hash("sha1", $password);
            } else {
                unset($data['password']);
            }
        }
        unset($data['password2']);

        $sql = "UPDATE USERS SET ";
        foreach ($data as $key => $value) {
            # code...
            $sql .= $key . "='" . $value . "',";
        }

        $sql = trim($sql, ",");

        //$sql = substr($sql, 0, strlen($sql) - 1);
        $sql .= " WHERE userid = '$id' LIMIT 1";
        //echo $sql;
        //die;
        $db->save($sql);
    }
}
