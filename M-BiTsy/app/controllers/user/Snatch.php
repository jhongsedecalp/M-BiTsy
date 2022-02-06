<?php
class Snatch
{

    public function __construct()
    {
        Auth::user(0, 2);
    }

    public function index()
    {
        $tid = (int) $_GET['tid'];

        if ($tid > 0) {
            $count_tid = get_row_count('snatched', 'WHERE `tid` = \'' . $tid . '\'');
        }

        list($pagerbuttons, $limit) = Pagination::pager(25, $count_tid, '/snatch?tid=' . $tid . ' &amp;');
        $res = Snatched::join($tid, $limit);

        $torrents = DB::column('torrents', 'name', ['id'=>$tid]);
        $title = "" . Lang::T("REGISTERED_MEMBERS_TO_TORRENT") . " " . htmlspecialchars($torrents) . "";

        if ($count_tid > 0) {

            $data = [
                'title' => $title,
                'res' => $res,
                'tid' => $tid,
                'pagerbuttons' => $pagerbuttons,
            ];
            View::render('snatch/torrent', $data, 'user');

        } else {
            Redirect::autolink(URLROOT, Lang::T("Torrent Has No Snatched Users :)"));
        }
    }

    public function user()
    {
        $uid = (int) $_GET['id'];

        if ((Users::get("control_panel") == "no") && Users::get("id") != $uid) {
            Redirect::autolink(URLROOT, Lang::T("NO_PERMISSION"));
        }

        $count_uid = get_row_count('snatched', 'WHERE `uid` = \'' . $uid . '\'');
        list($pagerbuttons, $limit) = Pagination::pager(50, $count_uid, '/snatch?id=' . $uid . ' &amp;');
        $res = Snatched::join($uid, $limit);

        if ($count_uid > 0) {
            $users = DB::column('users', 'username', ['id'=>$uid]);
            $title = "" . Lang::T("SNATCHLIST_FOR") . " " . htmlspecialchars($users) . "";

            $data = [
                'title' => $title,
                'res' => $res,
                'count_uid' => $count_uid,
                'uid' => $uid,
                'pagerbuttons' => $pagerbuttons,
            ];
            View::render('snatch/user', $data, 'user');
        } else {
            Redirect::autolink(URLROOT, Lang::T("User Has No Snatched Torrents :)"));
        }

    }

    public function trade()
    {
        $uid = (int) Users::get('id');

        $res = Snatched::join1($uid);
        
        if ($_POST["requestpoints"]) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $torid = $_POST['torid'];
                $modcom = $row['modcomment'];
                }
                $modcomment = gmdate("d-M-Y") . " - " . Lang::T("DELETED_RECORDING") . ": " . $torid . " " . Lang::T("POINTS_OF_SEED_BONUS") . "\n" . $modcom;
                DB::run("UPDATE users SET seedbonus = seedbonus - '100', modcomment = ? WHERE id = ?", [$modcomment, $uid]);
                DB::update('snatched', ['ltime' =>86400, 'hnr' =>'no','done' =>'yes'], ['tid' => $torid, 'uid' => $uid]);
                Logs::write("<a href=" . URLROOT . "/profile?id=".Users::get('id')."><b>".Users::get('username')."</b></a> " . Lang::T("DELETED_RECORDING") . ": <a href=" . URLROOT . "/torrent?id=$torid><b>$torid</b></a> " . Lang::T("POINTS_OF_SEED_BONUS") . "");
                Redirect::autolink(URLROOT . "/snatch/trade", Lang::T("ONE_RECORDING_HIT_AND_RUN_DELETED"));
        }

        if ($_POST["requestupload"]) {
                while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $torid = $_POST['torid'];
                    $torsize = $row['size'];
                    $viewsize = mksize($row['size']);
                    $modcom = $row['modcomment'];
                }
                $modcomment = gmdate("d-M-Y") . " - " . Lang::T("DELETED_RECORDING") . ": " . $torid . " with " . $viewsize . " " . Lang::T("OF_UPLOAD") . "\n" . $modcom;
                DB::run("UPDATE users SET uploaded = uploaded - '$torsize', modcomment = ? WHERE id = ?", [$modcomment, $uid]);
                DB::update('snatched', ['ltime' =>86400, 'hnr' =>'no','done' =>'yes'], ['tid' => $torid, 'uid' => $uid]);
                Logs::write("<a href=" . URLROOT . "/profile?id=Users::get(id]><b>".Users::get('username')."</b></a> " . Lang::T("DELETED_RECORDING") . ": <a href=" . URLROOT . "/torrent?id=$torid><b>$torid</b></a> " . Lang::T("HIT_AND_RUN_WITH") . " <b>$viewsize</b> " . Lang::T("OF_UPLOAD") . "");
                Redirect::autolink(URLROOT . "/snatch/trade", Lang::T("ONE_RECORDING_HIT_AND_RUN_DELETED"));
        }
            
        if ($res->rowCount() > 0) {
            $data = [
                'title' => 'Trade',
                'res' => $res,
                'uid' => $uid,
            ];
            View::render('snatch/trade', $data, 'user');
        } else {
            Redirect::autolink(URLROOT, Lang::T("THERE_ARE_NO_RECORDINGS"));
        }
    }
    
}