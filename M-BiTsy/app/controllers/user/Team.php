<?php
class Team
{

    public function __construct()
    {
        Auth::user(0, 2);
    }
    
    public function index()
    {
        $res = Teams::getTeams();

        if ($res->rowCount() == 0) {
            Redirect::autolink(URLROOT, Lang::T("NO_TEAM"));
        }
        
        $data = [
            'title' => Lang::T("TEAM[1]"),
            'res' => $res
        ];
        View::render('team/index', $data, 'user');
    }

}