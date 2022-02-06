<?php
class Catalog
{
    public function __construct()
    {
        Auth::user(0, 2);
    }

    public function index()
    {
        if (Users::get("view_torrents") != "yes" && Config::get('MEMBERSONLY')) {
            Redirect::autolink(URLROOT, Lang::T("NO_TORRENT_VIEW"));
        }

		$search = $_GET['search'] ?? '';
        $letter = $_GET["letter"] ?? '';
        $url = "?";

        if ($search != '') {
            $keys = explode(" ", $search);
            foreach ($keys as $k) {
                $ssa[] = " torrents.name LIKE '%$k%' ";
            }
            $query = '(' . implode(' OR ', $ssa) . ')';
            $url .= "search=" . urlencode($search);
        } else {
            if (strlen($letter) > 1) {
                die;
            }
            if ($letter == "" || strpos("abcdefghijklmnopqrstuvwxyz", $letter) === false) {
                $letter = "t";
            }
            $query = "torrents.name LIKE '$letter%'";
            $url = "?letter=$letter";
        }

        $count = DB::run("SELECT count(id) FROM torrents WHERE $query AND visible != ?", ['no'])->fetchColumn();
        list($pagerbuttons, $limit) = Pagination::pager(28, $count, URLROOT . "/catalog$url&");
        $res = Torrents::catalog($query, $limit);

        $data = [
            'title' => Lang::T("CATALOGUE"),
            'res' => $res,
            'count' => $count,
            'pagerbuttons' => $pagerbuttons,
        ];
        View::render('catalog/index', $data, 'user');
        
    }
}