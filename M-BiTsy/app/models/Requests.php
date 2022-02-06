<?php
class Requests
{
    public static function join() {
        $res = DB::run("SELECT 
                       users.downloaded, 
                       users.uploaded, 
                       users.username, 
                       users.privacy, 
                       requests.filled, 
                       requests.comments,
                       requests.filledby, 
                       requests.id, 
                       requests.userid, 
                       requests.request, 
                       requests.added, 
                       requests.hits, 
                       categories.name as cat,
                       categories.parent_cat as parent_cat
                FROM requests 
                inner join categories 
                on requests.cat = categories.id 
                inner join users 
                on requests.userid = users.id");
        return $res;
    }

}