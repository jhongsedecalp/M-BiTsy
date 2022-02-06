<?php
class Adminreport
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }


    public function index()
    {
        $switch = $this->switch();

        $num = get_row_count("reports", "WHERE $switch[where]");
        list($pagerbuttons, $limit) = Pagination::pager(25, $num, "$switch[pager]&amp;");
        $res = DB::run("SELECT reports.id, reports.dealtwith, reports.dealtby, reports.addedby, reports.votedfor, reports.votedfor_xtra, reports.reason, reports.type, users.username, reports.complete 
        FROM `reports` INNER JOIN users ON reports.addedby = users.id 
        WHERE $switch[where] ORDER BY reports.id DESC $limit");
        $data = [
            'title' => Lang::T("Reported Items"),
            'res' => $res,
            'page' => $switch['page'],
            'pagerbuttons' => $pagerbuttons,
        ];
        View::render('report/index', $data, 'admin');
    }

    private function switch()
    {
        $page = 'adminreport?';
        $pager[] = substr($page, 0, -4);
        $where = array();

        switch ($_GET["type"]) {
            case "user":
                $where[] = "type = 'user'";
                $pager[] = "type=user";
                break;
            case "torrent":
                $where[] = "type = 'torrent'";
                $pager[] = "type=torrent";
                break;
            case "comment":
                $where[] = "type = 'comment'";
                $pager[] = "type=comment";
                break;
            case "forum":
                $where[] = "type = 'forum'";
                $pager[] = "type=forum";
                break;
            case "req":
                $where[] = "type = 'req'";
                $pager[] = "type=req";
                break;
            default:
                $where = null;
                break;
        }

        switch ($_GET["completed"]) {
            case 1:
                $where[] = "complete = '1'";
                $pager[] = "complete=1";
                break;
            default:
                $where[] = "complete = '0'";
                $pager[] = "complete=0";
                break;
        }

        $where = implode(" AND ", $where);
        $pager = implode("&amp;", $pager);
        
        $array = ['where'=>$where,'pager'=>$pager,'page'=>$page];

        return $array;
    }

    public function completed()
    {
        if ($_POST["mark"]) {
            if (!@count($_POST["reports"])) {
                Redirect::autolink(URLROOT."/adminreport", "Nothing selected to mark.");
            }
            $ids = array_map("intval", $_POST["reports"]);
            $ids = implode(",", $ids);
            DB::run("UPDATE reports SET complete = '1', dealtwith = '1', dealtby = ".Users::get('id')." WHERE id IN ($ids)");
            header("Refresh: 2; url=" . URLROOT . "/adminreport");
            Redirect::autolink(URLROOT."/adminreport", Lang::T("CP_ENTRIES_MARK_COMP"));
        }
        
        if ($_POST["del"]) {
            if (!@count($_POST["reports"])) {
                Redirect::autolink(URLROOT."/adminreport", "Nothing selected to delete.");
            }
            $ids = array_map("intval", $_POST["reports"]);
            $ids = implode(",", $ids);
            DB::deleteByIds('reports', 'id', $ids);
            header("Refresh: 2; url=" . URLROOT . "/adminreport");
            Redirect::autolink(URLROOT."/adminreport", "Entries marked deleted.");
        }
    }

}