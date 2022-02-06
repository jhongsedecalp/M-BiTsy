<?php

class Completed {

    public function __construct()
    {
        Auth::user(0, 2);
    }
    
    public function index()
    {
        if (Users::get("view_torrents") == "no") {
            Redirect::autolink(URLROOT, Lang::T("NO_TORRENT_VIEW"));
        }

        $id = (int) Input::get("id");

        $row = DB::select('torrents', 'name, external, banned', ['id'=>$id]);
        if ((!$row) || ($row["banned"] == "yes" && Users::get("edit_torrents") == "no")) {
            Redirect::autolink(URLROOT, Lang::T("TORRENT_NOT_FOUND"));
        }
        if ($row["external"] == "yes") {
            Redirect::autolink(URLROOT, Lang::T("THIS_TORRENT_IS_EXTERNALLY_TRACKED"));
        }

        $res = Completed::completedUser($id);
        if ($res->rowCount() == 0) {
            Redirect::autolink(URLROOT, Lang::T("NO_DOWNLOADS_YET"));
        }

        $title = sprintf(Lang::T("COMPLETED_DOWNLOADS"), CutName($row["name"], 40));

        $data = [
            'title' => $title,
            'res' => $res,
            'id' => $id,
        ];
        View::render('complete/index', $data, 'user');
    }

}