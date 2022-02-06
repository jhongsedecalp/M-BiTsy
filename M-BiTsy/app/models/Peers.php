<?php
class Peers
{

    public static function seedingTorrent($id, $seeder)
    {
        $sql = DB::run("SELECT `torrent`, `uploaded`, `downloaded` 
                        FROM `peers` 
                        LEFT JOIN torrents 
                        ON torrent = torrents.id 
                        WHERE userid = ? AND seeder = ?", [$id, $seeder]);
        return $sql;
    }

}