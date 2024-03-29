<?php

class Auth
{

    public static function user($class = 0, $force = 0, $autoclean = false)
    {
        // Check Ip
        self::ipBanned();
        // Check Token
        Cookie::csrf_token();
        // Check Cookies
        if (strlen(Cookie::get("password")) != 60 || !is_numeric($_COOKIE["id"]) || $_COOKIE["key_token"] != self::loginString()) {
            self::isLoggedIn($force);
            return;
        } else {
            // Is User A Member
            try {
                $row = DB::run("SELECT * FROM `users` 
                                LEFT OUTER JOIN `groups` 
                                ON users.class=groups.group_id 
                                WHERE id = $_COOKIE[id] 
                                AND users.enabled='yes'
                                AND users.status ='confirmed'")->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                Cookie::destroyAll();
                Redirect::autolink(URLROOT . "/logout", 'Issue With User Auth');
            }
			// Site closeed
			self::isClosed($row['class']);
            // Now Compare Checks
            if ($row['password'] != $_COOKIE['password']) {
                Redirect::to(URLROOT . "/logout");
            }
            if ($row['id'] != $_COOKIE['id']) {
                Redirect::to(URLROOT . "/logout");
            }
            if ($class != 0 && $class > $row['class']) {
                Redirect::autolink(URLROOT . "/index", Lang::T("SORRY_NO_RIGHTS_TO_ACCESS"));
            }
            // User So Update & Set Seesion
            if ($row) {
                $where = Users::where($_SERVER['REQUEST_URI'], $row["id"], 0);
                DB::update('users', ['last_access' =>TimeDate::get_date_time(), 'ip' =>Ip::getIP(),'page' =>$where], ['id' => $row["id"]]);
                $GLOBALS['CURRENTUSER'] = $row;
                //$_SESSION = $row;
                $_SESSION["loggedin"] = true;
                unset($row);
            }
            // Run Cleanup
            if ($autoclean) {
                Cleanup::autoclean();
            }
        }

    }

    private static function loginString()
    {
        $ip = Ip::getIP();
        $browser = Ip::agent();
        return md5($browser . $browser);
    }

    public static function ipBanned()
    {
        $ip = Ip::getIP();
        if ($ip == '') {
            return;
        }
        Ip::checkipban($ip);
    }

    public static function isLoggedIn($force = 0)
    {
        // If force 0 guest view, force 1 use config Config::get('MEMBERSONLY'], force 2 always hidden from guest
        if ($force == 1 && Config::get('MEMBERSONLY')) {
            if (!$_SESSION['loggedin']) {
                Redirect::to(URLROOT . "/logout");
            }
        } elseif ($force == 2) {
            if (!$_SESSION['loggedin']) {
                Redirect::to(URLROOT . "/login");
            }
        }
    }

    public static function isClosed($class = false)
    {
        if (!Config::get('SITE_ONLINE')) {
            if ($class < _MODERATOR) {
                ob_start();
                ob_clean();
                require_once "assets/themes/" . (Users::get('stylesheet') ?: Config::get('DEFAULTTHEME')) . "/header.php";
                echo '<div class="alert ttalert"><center>' . stripslashes(Config::get('OFFLINEMSG')) . '</center></div>';
                require_once "assets/themes/" . (Users::get('stylesheet') ?: Config::get('DEFAULTTHEME')) . "/footer.php";
                die();
            } else {
                echo '<center>' . stripslashes(Config::get('OFFLINEMSG')) . '</center>';
            }
        }
    }

}