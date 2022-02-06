<?php
class Adminpeer
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        $count = number_format(get_row_count("peers"));
        list($pagerbuttons, $limit) = Pagination::pager(50, $count, "/adminpeer?");
        $result = DB::raw('peers', '*', '', 'ORDER BY started DESC', "$limit");

        $data = [
            'title' => Lang::T("Peers List"),
            'count1' => $count,
            'pagerbuttons' => $pagerbuttons,
            'result' => $result
        ];
        View::render('peer/index', $data, 'admin');
   }

}