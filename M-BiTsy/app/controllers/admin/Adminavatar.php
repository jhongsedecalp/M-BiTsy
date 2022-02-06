<?php
class Adminavatar
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        $count = DB::run("SELECT count(*) FROM users WHERE enabled=? AND avatar !=?", ['yes', ''])->fetchColumn();
        list($pagerbuttons, $limit) = Pagination::pager(50, $count, URLROOT.'/adminavatar?');
        $res = DB::run("SELECT username, id, avatar FROM users WHERE enabled='yes' AND avatar !='' $limit");

        $data = [
            'title' => "Avatar Log",
            'res' => $res,
            'pagerbuttons' => $pagerbuttons,
        ];
        View::render('avatar/index', $data, 'admin');
    }

}