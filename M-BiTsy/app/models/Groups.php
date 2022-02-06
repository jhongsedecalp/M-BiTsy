<?php
class Groups
{

    public static function getStaff()
    {
        $stmt = DB::run("SELECT `users`.`id`, `users`.`username`, `users`.`class`, `users`.`last_access`
                         FROM `users`
                         INNER JOIN `groups`
                         ON `users`.`class` = `groups`.`group_id`
                         WHERE `users`.`enabled` =? AND `users`.`status` =? AND `groups`.`staff_page` =?
                         ORDER BY `username`",
                        ['yes', 'confirmed', 'yes']);
        return $stmt;
    }

    public static function getStaffLevel($where)
    {
        $row = DB::run("SELECT `group_id`, `level`, `staff_public`
                        FROM `groups`
                        WHERE `staff_page` = 'yes' $where
                        ORDER BY `staff_sort`");
        return $row;
    }

    public static function getGroupsearch($query, $limit)
    {
        $row = DB::run("SELECT users.*, groups.level FROM users INNER JOIN `groups` ON groups.group_id=users.class WHERE $query ORDER BY username $limit");
        return $row;
    }

    
    // Function That Returns The Group Name
    public static function get_user_class_name($i)
    {
        if ($i == Users::get("class")) {
           return Users::get("level");
        }
        $res = DB::run("SELECT level FROM `groups` WHERE group_id=" . $i . "");
        $row = $res->fetch(PDO::FETCH_LAZY);
        return $row[0];
    }

    // Function To List Groups Of Members Of The Database
    public static function classlist()
    {
        $ret = array();
        $res = DB::run("SELECT * FROM `groups` ORDER BY group_id ASC");
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
           $ret[] = $row;
        }
        return $ret;
    }

    public static function search($search, $class, $letter) {
        $q = $query = null;
        if ($search) {
            $query = "username LIKE " . sqlesc("%$search%") . " AND status='confirmed'";
            if ($search) {
                $q = "search=" . htmlspecialchars($search);
            }
        } elseif ($letter) {
            if (strlen($letter) > 1) {
                unset($letter);
            }
            if ($letter == "" || strpos("abcdefghijklmnopqrstuvwxyz", $letter) === false) {
                unset($letter);
            } else {
                $query = "username LIKE '$letter%' AND status='confirmed'";
            }
            $q = "letter=$letter";
        }
        if (!$query) {
            $query = "status='confirmed'";
        }
        if (!$class) {
            unset($class);
        } else {
            $query .= " AND class=$class";
            $q .= ($q ? "&amp;" : "") . "class=$class";
        }

        $var = ['query'=>$query, 'q'=>$q];

        return $var;
    }
}