<?php
class Adminuser
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        Redirect::to(URLROOT . '/admincp');
    }

    public function add()
    {
        $data = [
            'title' => 'Add User',
        ];
        View::render('user/adduser', $data, 'admin');
    }

    public function addeduserok()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($_POST["username"] == "" || $_POST["password"] == "" || $_POST["email"] == "") {
                Redirect::autolink(URLROOT . "/adminuser", "Missing form data.");
            }
            if ($_POST["password"] != $_POST["password2"]) {
                Redirect::autolink(URLROOT . "/adminuser", "Passwords mismatch.");
            }

            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];
            $secret = Helper::mksecret();
            $passhash = password_hash($password, PASSWORD_BCRYPT);
            $secret = $secret;
            
            $count = DB::column('users', 'count(*)', ['username'=>$username]);
            if ($count !=0) {
                Redirect::autolink(URLROOT . "/adminuser/add", "Unable to create the account. The user name is possibly already taken.");
            }
            DB::insert('users', ['added'=>TimeDate::get_date_time(), 'last_access'=>TimeDate::get_date_time(), 'secret'=>$secret, 'username'=>$username, 'password'=>$passhash, 'status'=>'confirmed', 'email'=>$email]);
            Redirect::autolink(URLROOT . "/admincp", Lang::T("COMPLETE"));
        }
    }

    public function whoswhere()
    {
        $res = DB::run("SELECT `id`, `username`, `page`, `last_access`
                        FROM `users`
                        WHERE `enabled` = 'yes' AND `status` = 'confirmed' AND `page` != ''
                        ORDER BY `last_access`
                        DESC LIMIT 100");
        $data = [
            'title' => 'Where are members',
            'res' => $res,
        ];
        View::render('user/whoswhere', $data, 'admin');
    }

    public function privacy()
    {
        $where = array();
        switch ($_GET['type']) {
            case 'low':
                $where[] = "privacy = 'low'";
                break;
            case 'normal':
                $where[] = "privacy = 'normal'";
                break;
            case 'strong':
                $where[] = "privacy = 'strong'";
                break;
            default:
                break;
        }
        $where[] = "enabled = 'yes'";
        $where[] = "status = 'confirmed'";
        $where = implode(' AND ', $where);
        $count = get_row_count("users", "WHERE $where");
        list($pagerbuttons, $limit) = Pagination::pager(25, $count, htmlspecialchars($_SERVER['REQUEST_URI'] . '&'));
        $res = DB::run("SELECT id, username, class, email, ip, added, last_access FROM users WHERE $where ORDER BY username DESC $limit");

        $data = [
            'title' => Lang::T("PRIVACY_LEVEL"),
            'count' => $count,
            'res' => $res,
            'pagerbuttons' => $pagerbuttons,
        ];
        View::render('user/privacylevel', $data, 'admin');
    }

    public function duplicateip()
    {
        $res = DB::run("SELECT ip FROM users GROUP BY ip HAVING count(*) > 1");
        $num = $res->rowCount();
		if ($num == 0) {
            Redirect::autolink(URLROOT."/admincp", Lang::T("No Duplicate IPs."));
        }
        list($pagerbuttons, $limit) = Pagination::pager(25, $num, 'Adminuser/duplicateip?');
        $res = DB::run("SELECT id, username, class, email, ip, added, last_access, COUNT(*) as count FROM users GROUP BY ip HAVING count(*) > 1 ORDER BY id ASC $limit");
        $data = [
            'title' => Lang::T("DUPLICATEIP"),
            'num' => $num,
            'res' => $res,
        ];
        View::render('user/duplicuteip', $data, 'admin');
    }

    public function confirm() // new user
    {
        $do = $_GET['do'];
        if ($do == "confirm") {
            if ($_POST["confirmall"]) {
                DB::run("UPDATE `users` SET `status` = 'confirmed' WHERE `status` = 'pending' AND `invited_by` = '0'");
            } else {
                if (!@count($_POST["users"])) {
                    Redirect::autolink(URLROOT."/adminuser/duplicateip", Lang::T("NOTHING_SELECTED"));
                }
                $ids = array_map("intval", $_POST["users"]);
                $ids = implode(", ", $ids);
                DB::run("UPDATE `users` SET `status` = 'confirmed' WHERE `status` = 'pending' AND `invited_by` = '0' AND `id` IN ($ids)");
            }
            Redirect::autolink(URLROOT . "/Adminuser/confirm", "Entries Confirmed");
        }
        $count = get_row_count("users", "WHERE status = 'pending' AND invited_by = '0'");
        list($pagerbuttons, $limit) = Pagination::pager(25, $count, '' . URLROOT . '/Adminuser/confirm?');
        $res = DB::raw('users', 'id,username,email,added,ip', ['status'=>'status','invited_by'=>0], "ORDER BY `added` DESC $limit");

        $data = [
            'title' => Lang::T("Manual Registration Confirm"),
            'count' => $count,
            'pagerbuttons' => $pagerbuttons,
            'res' => $res,
        ];
        View::render('user/confirmreg', $data, 'admin');
    }

    public function cheats()
    {
        $data = [
            'title' => Lang::T("Possible Cheater Detection"),
        ];
        View::render('user/cheatform', $data, 'admin');
    }

    public function result()
    {
        $megabts = (int) $_POST['megabts'];
        $daysago = (int) $_POST['daysago'];
        if ($daysago && $megabts) {
            $timeago = 84600 * $daysago; //last 7 days
            $bytesover = 1048576 * $megabts; //over 500MB Upped
            $result = DB::run("SELECT * FROM users WHERE UNIX_TIMESTAMP('?') - UNIX_TIMESTAMP(added) < ? AND status=? AND uploaded > ? ORDER BY uploaded DESC ", [TimeDate::get_date_time(), $timeago, 'confirmed', $bytesover]);
            $num = $result->rowCount(); // how many uploaders
            $message = "<p>" . $num . " Users with found over last " . $daysago . " days with more than " . $megabts . " MB (" . $bytesover . ") Bytes Uploaded.</p>";
            $zerofix = $num - 1; // remove one row because mysql starts at zero
            if ($num > 0) {
                $data = [
                    'title' => Lang::T("Possible Cheater Detection"),
                    'result' => $result,
                    'zerofix' => $zerofix,
                    'message' => $message
                    ];
                    View::render('user/cheatresult', $data, 'admin');
            } else {
                Redirect::autolink(URLROOT . '/adminuser/cheats', $message);
                die;
            } 

        } else {
            $data = [
            'title' => Lang::T("Possible Cheater Detection"),
            ];
            View::render('user/cheatform', $data, 'admin');
        }
    }
}