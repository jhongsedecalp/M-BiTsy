<?php
class Guests
{

    public static function guestadd()
    {
        $ip = Ip::getIP();
        $time = TimeDate::gmtime();
        DB::run("INSERT INTO `guests` (`ip`, `time`) VALUES ('$ip', '$time') ON DUPLICATE KEY UPDATE `time` = '$time'");
    }

    public static function getguests()
    {
        $past = (TimeDate::gmtime() - 2400);
        DB::run("DELETE FROM `guests` WHERE `time` < $past");
        return get_row_count("guests");
    }

}