<?php
class Reports
{

    public static function getname($type, $votedfor)
    {
        switch ($type) {
            case "user":
                $q = DB::run("SELECT username FROM users WHERE id = ?", [$votedfor])->fetch();
                $test = array('name' => $q['username']);
                break;
            case "torrent":
                $q = DB::run("SELECT name FROM torrents WHERE id = ?", [$votedfor])->fetch();
                $test = array('name' => $q['name']);
                break;
            case "comment":
                $q = DB::run("SELECT text, news, torrent FROM comments WHERE id = ?", [$votedfor])->fetch();
                $test = array('name' => $q['text'], 'news' => $q['news'], 'torrent' => $q['torrent']);
                break;
            case "forum":
                $q = DB::run("SELECT subject FROM forum_topics WHERE id = ?", [$votedfor])->fetch();
                $test = array('name' => $q['subject']);
                break;
            case "req":
                $q = DB::run("SELECT request FROM requests WHERE id = ?", [$votedfor])->fetch();
                $test = array('name' => $q['request']);
                break;
        }
        return $test;
    }

}
