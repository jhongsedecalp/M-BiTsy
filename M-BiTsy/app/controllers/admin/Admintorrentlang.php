<?php
class Admintorrentlang
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        $sql = DB::raw('torrentlang', '*', '', 'ORDER BY sort_index ASC');
        
        $data = [
            'title' => Lang::T("TORRENT_LANGUAGES"),
            'sql' => $sql,
        ];
        View::render('torrentlang/index', $data, 'admin');
    }

    public function edit()
    {
        $id = (int) Input::get("id");

        if (!Validate::Id($id)) {
            Redirect::autolink(URLROOT . "/admintorrentlang", Lang::T("INVALID_ID"));
        }

        $res = DB::raw('torrentlang', '*', ['id'=>$id]);
        if ($res->rowCount() != 1) {
            Redirect::autolink(URLROOT . "/admintorrentlang", "No Language with ID $id.");
        }

        if ($_GET["save"] == '1') {
            $name = $_POST['name'];
            if ($name == "") {
                Redirect::autolink(URLROOT."/admintorrentlang/edit", "Language cat cannot be empty!");
            }
            $sort_index = $_POST['sort_index'];
            $image = $_POST['image'];
            DB::update('torrentlang', ['name' =>$name, 'sort_index' =>$sort_index,'image' =>$image], ['id' => $id]);
            Redirect::autolink(URLROOT . "/admintorrentlang/torrentlang", Lang::T("Language was edited successfully."));
        } else {
            $data = [
                'title' => Lang::T("TORRENT_LANGUAGES"),
                'id' => $id,
                'res' => $res,
            ];
            View::render('torrentlang/edit', $data, 'admin');
        }
    }

    public function delete()
    {
        $id = (int) $_GET["id"];

        if ($_GET["sure"] == '1') {
            if (!Validate::Id($id)) {
                Redirect::autolink(URLROOT."/admintorrentlang/delete", "Invalid Language item ID");
            }
            $newlangid = (int) $_POST["newlangid"];
            DB::run("UPDATE torrents SET torrentlang=$newlangid WHERE torrentlang=$id");
            DB::delete('torrentlang', ['id'=>$id]);
            Redirect::autolink(URLROOT . "/admintorrentlang", Lang::T("Language Deleted OK."));
        } else {
            $data = [
                'title' => Lang::T("TORRENT_LANGUAGES"),
                'id' => $id,
            ];
            View::render('torrentlang/delete', $data, 'admin');
        }
    }

    public function takeadd()
    {
        $name = $_POST['name'];

        if ($name == "") {
            Redirect::autolink(URLROOT . "/admintorrentlang/add", "Name cannot be empty!");
        }

        $sort_index = $_POST['sort_index'];
        $image = $_POST['image'];

        $ins = DB::insert('torrentlang', ['name'=>$name, 'sort_index'=>$sort_index, 'image'=>$image]);
        if ($ins) {
            Redirect::autolink(URLROOT . "/admintorrentlang", Lang::T("Language was added successfully."));
        } else {
            Redirect::autolink(URLROOT . "/admintorrentlang/add", "Unable to add Language");
        }
    }

    public function add()
    {
        $data = [
            'title' => Lang::T("TORRENT_LANGUAGES"),
        ];
        View::render('torrentlang/add', $data, 'admin');
    }

}