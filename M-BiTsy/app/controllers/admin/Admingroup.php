<?php
class Admingroup
{

    public function __construct()
    {
        Auth::user(_SUPERMODERATOR, 2);
    }

    public function index()
    {
        $getlevel = DB::run("SELECT * from `groups` ORDER BY group_id");
		
        $data = [
            'title' => Lang::T("Groups Management"),
            'getlevel' => $getlevel,
            ];
        View::render('group/index', $data, 'admin');
    }

    public function edit()
    {
        $group_id = intval($_GET["group_id"]);

        $rlevel = DB::run("SELECT * FROM `groups` WHERE group_id=?", [$group_id]);
        if (!$rlevel) {
            Redirect::autolink(URLROOT . '/admingroup', Lang::T("CP_NO_GROUP_ID_FOUND"));
        }

        $data = [
            'title' => Lang::T("Groups Management"),
            'rlevel' => $rlevel,
        ];
        View::render('group/edit', $data, 'admin');
    }

    public function update()
    {
        $group_id = intval($_GET["group_id"]);

        DB::run("UPDATE `groups` SET 
                level = ?, Color = ?, view_torrents = ?, edit_torrents  = ?, delete_torrents = ?,
                view_users = ?, edit_users = ?, delete_users = ?, view_news = ?, edit_news = ?,
                delete_news = ?, view_forum = ?, edit_forum = ?, delete_forum = ?, can_upload = ?,
                can_download = ?, maxslots= ?, control_panel = ?, staff_page = ?, staff_public = ?, staff_sort = ?
                WHERE group_id=?", [
                $_POST["gname"], $_POST["gcolor"], $_POST["vtorrent"], $_POST["etorrent"], $_POST["dtorrent"], 
                $_POST["vuser"], $_POST["euser"], $_POST["duser"], $_POST["vnews"], $_POST["enews"], 
                $_POST["dnews"], $_POST["vforum"], $_POST["eforum"], $_POST["dforum"], $_POST["upload"], 
                $_POST["down"], $_POST["downslots"], $_POST["admincp"],  $_POST["staffpage"], 
                $_POST["staffpublic"], intval($_POST['sort']),  $group_id]);
        Redirect::autolink(URLROOT . "/admingroup/groups", Lang::T("SUCCESS"), "Groups Updated!");
    }

    public function delete()
    {
        $group_id = intval($_GET["group_id"]);
        if (($group_id == "1") || ($group_id == _ADMINISTRATOR)) {
            Redirect::autolink(URLROOT . '/admingroup', Lang::T("CP_YOU_CANT_DEL_THIS_GRP"));
        }

        DB::delete('`groups`', ['group_id'=>$group_id]);
        Redirect::autolink(URLROOT . "/admingroup/groups", Lang::T("CP_DEL_OK"));
    }

    public function add()
    {
        $rlevel = DB::run("SELECT DISTINCT group_id, level FROM `groups` ORDER BY group_id");

        $data = [
            'title' => Lang::T("Groups Management"),
            'rlevel' => $rlevel,
        ];
        View::render('group/add', $data, 'admin');
    }

    public function addnew()
    {
        $gname = $_POST["gname"];
        $gcolor = $_POST["gcolor"];
        $group_id = $_POST["getlevel"];

        $rlevel = DB::run("SELECT * FROM `groups` WHERE group_id=?", [$group_id]);
        $level = $rlevel->fetch(PDO::FETCH_ASSOC);
        if (!$level) {
            Redirect::autolink(URLROOT . '/admingroup/add', Lang::T("CP_INVALID_ID"));
        }

        DB::run("INSERT INTO `groups`
        (level, color, view_torrents, edit_torrents, delete_torrents, view_users, edit_users, delete_users,
	    view_news, edit_news, delete_news, view_forum, edit_forum, delete_forum, can_upload, can_download,
	    control_panel, staff_page, staff_public, staff_sort, maxslots)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
        [$gname, $gcolor, $level['view_torrents'], $level["edit_torrents"], $level["delete_torrents"], $level["view_users"],
        $level["edit_users"], $level["delete_users"], $level["view_news"], $level["edit_news"], $level["delete_news"],
        $level["edit_forum"], $level["edit_forum"], $level["delete_forum"], $level["can_upload"], $level["can_download"], $level["control_panel"],
        $level["staff_page"], $level["staff_public"], $level["staff_sort"], $level["maxslots"]]);
        Redirect::autolink(URLROOT . "/admingroup/groups", Lang::T("SUCCESS"), "Groups Updated!");
    }
}