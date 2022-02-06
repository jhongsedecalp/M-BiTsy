<?php
class Shoutboxs
{

    public static function checkFlood($message, $username)
    {
        $stmt = DB::run("SELECT COUNT(*) FROM shoutbox 
                        WHERE message=? AND user=? AND UNIX_TIMESTAMP(?)-UNIX_TIMESTAMP(date) < ?", 
                        [$message, $username, TimeDate::get_date_time(), 30])->fetch(PDO::FETCH_LAZY);
        return $stmt;
    }

}