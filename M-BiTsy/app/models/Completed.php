<?php
class Completed
{

    public static function completedUser($id)
    {
        $row = DB::run("SELECT users.id, users.username, users.uploaded, users.downloaded, users.privacy, completed.date FROM users LEFT JOIN completed ON users.id = completed.userid WHERE users.enabled = 'yes' AND completed.torrentid = '$id'");
        return $row;
    }

}