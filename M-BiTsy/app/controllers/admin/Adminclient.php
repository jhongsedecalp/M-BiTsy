<?php
class Adminclient
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        if (isset($_POST['ban'])) {
            DB::insert('clients', ['agent_name'=>$_POST['ban'], 'hits'=>1, 'ins_date'=>TimeDate::get_date_time()]);
            Redirect::autolink(URLROOT . "/adminclient/banned", Lang::T("SUCCESS"));
        }

        $res11 = DB::select('peers', 'client, peer_id', '', 'ORDER BY client');

        $data = [
            'title' => Lang::T("Clients"),
            'res11' => $res11,
        ];
        View::render('client/index', $data, 'admin');
    }

    public function banned()
    {
        if (isset($_POST['unban'])) {
            foreach ($_POST['unban'] as $deleteid) {
                DB::delete('clients', ['agent_id'=>$deleteid]);
            }
            Redirect::autolink(URLROOT . "/adminclient/banned", Lang::T("SUCCESS"));
        }

        $sql = DB::all('clients', '*', '');

        $data = [
            'title' => Lang::T("Clients"),
            'sql' => $sql,
        ];
        View::render('client/banned', $data, 'admin');
    }

}