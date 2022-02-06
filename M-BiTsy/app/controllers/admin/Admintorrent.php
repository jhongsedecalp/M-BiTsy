<?php
class Admintorrent
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        if ($_POST["do"] == "delete") {
            if (!@count($_POST["torrentids"])) {
                Redirect::autolink(URLROOT . "/admintorrent", "Nothing selected click <a href='admintorrent'>here</a> to go back.");
            }
            foreach ($_POST["torrentids"] as $id) {
                Torrents::deletetorrent(intval($id));
                Logs::write("Torrent ID $id was deleted by ".Users::get('username')."");
            }
            Redirect::autolink(URLROOT . "/admintorrent", "Deleted Item Go <a href='admintorrent'>back</a>?");
        }

        $search = (!empty($_GET["search"])) ? htmlspecialchars(trim($_GET["search"])) : "";
        $where = ($search == "") ? "" : "WHERE name LIKE " . sqlesc("%$search%") . "";
        $count = get_row_count("torrents", $where);
        list($pagerbuttons, $limit) = Pagination::pager(25, $count, "admintorrent&amp;");
        $res = DB::run("SELECT id, name, seeders, leechers, visible, banned, external FROM torrents $where ORDER BY name $limit");

        $data = [
            'title' => Lang::T("Torrent Management"),
            'count' => $count,
            'pagerbuttons' => $pagerbuttons,
            'res' => $res,
            'search' => $search,
        ];
        View::render('torrent/index', $data, 'admin');
    }

    public function free()
    {
        $search = trim($_GET['search'] ?? '');

        if ($search != '') {
            $whereand = "AND name LIKE " . sqlesc("%$search%") . "";
        }

        $count = DB::run("SELECT COUNT(*) FROM torrents WHERE freeleech=? $whereand", [1])->fetchColumn();
        list($pagerbuttons, $limit) = Pagination::pager(40, $count, "/admintorrent/free?");
        $resqq = DB::run("SELECT id, name, seeders, leechers, visible, banned FROM torrents WHERE freeleech='1' $whereand ORDER BY name $limit");
        
        $data = [
            'title' => Lang::T("Free Leech"),
            'resqq' => $resqq,
            'pagerbuttons' => $pagerbuttons,
        ];
        View::render('torrent/freeleech', $data, 'admin');
    }
    
    
    // dead torrents
    public function dead()
    {
        if (Users::get("control_panel") != "yes") {
            Redirect::autolink(URLROOT, Lang::T("SORRY_NO_RIGHTS_TO_ACCESS"));
        }

        $count = DB::run("SELECT COUNT(*) FROM torrents WHERE banned = 'no' AND seeders < 1")->fetchColumn();
        list($pagerbuttons, $limit) = Pagination::pager(25, $count, URLROOT . "/admintorrent/dead&amp;");
        $res = DB::run("SELECT torrents.id, torrents.name, torrents.owner, torrents.external, torrents.size, torrents.seeders, torrents.leechers, torrents.times_completed, torrents.added, torrents.last_action, users.username FROM torrents LEFT JOIN users ON torrents.owner = users.id WHERE torrents.banned = 'no' AND torrents.seeders < 1 ORDER BY torrents.added DESC $limit");

        if ($_POST["do"] == "delete") {
            if (!@count($_POST["torrentids"])) {
                Redirect::autolink(URLROOT . "/admintorrent/dead", "You must select at least one torrent.");
            }
            foreach ($_POST["torrentids"] as $id) {
                Torrents::deletetorrent(intval($id));
                Logs::write("<a href=" . URLROOT . "/profile?id=".Users::get('id')."><b>".Users::get('username')."</b></a>deleted the torrent ID : [<b>$id</b>]of the page: <i><b>Dead Torrents </b></i>");
            }
            Redirect::autolink(URLROOT . "/admintorrent/dead", "The selected torrent has been successfully deleted.");
        }

        if ($count < 1) {
            Redirect::autolink(URLROOT, "No Dead Torrents !");
        }
        
        $data = [
            'res' => $res,
            'title' => "The Dead Torrents",
            'count' => $count,
            'perpage' => 25,
            'pagerbuttons' => $pagerbuttons,
        ];
        View::render('torrent/dead', $data, 'admin');
    }

}