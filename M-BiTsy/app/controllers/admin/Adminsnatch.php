<?php
class Adminsnatch
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        if ($_POST['do'] == 'delete') {
            if (!@count($_POST['ids'])) {
                Redirect::autolink(URLROOT . "/adminsnatch", "Nothing Selected.");
            }
            $ids = array_map('intval', $_POST['ids']);
            $ids = implode(',', $ids);
            DB::run("UPDATE snatched SET ltime = '86400', hnr = 'no', done = 'yes' WHERE `sid` IN ($ids)");
            Redirect::autolink(URLROOT . "/Adminsnatch", "Entries deleted.");
        }

        if (Config::get('HNR_ON')) {
            $count = DB::column('snatched', 'count(*)', ['hnr'=>'yes']);
            $perpage = 50;
            list($pagerbuttons, $limit) = Pagination::pager($perpage, 30, "Adminsnatch?");
            $res = DB::run("SELECT *,s.tid FROM users u left join snatched s on s.uid=u.id  where hnr='yes' ORDER BY s.uid DESC $limit");

            $data = [
                'title' => "List of Hit and Run",
                'count' => $count,
                'pagerbuttons' => $pagerbuttons,
                'res' => $res,
            ];
            View::render('snatch/hitnrun', $data, 'admin');
        } else {
            Redirect::autolink(URLROOT, "Hit & Run Disabled in Config.php (mod in progress)");
        }
    }

}