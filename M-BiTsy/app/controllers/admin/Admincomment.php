<?php
class Admincomment
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        $count = get_row_count("comments");
        list($pagerbuttons, $limit) = Pagination::pager(10, $count, URLROOT."/admincomment?");
        $res = Comments::graball($limit);
        
        $data = [
            'title' => Lang::T("TORRENT_CATEGORIES"),
            'res' => $res,
            'pagerbuttons' => $pagerbuttons,
            'count' => $count,
        ];
        View::render('comment/index', $data, 'admin');
    }

}