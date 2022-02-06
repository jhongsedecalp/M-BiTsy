<?php
class Rule
{

    public function __construct()
    {
        Auth::user(0, 1);
    }

    public function index()
    {
        $res = DB::run("SELECT * FROM `rules` ORDER BY `id`");
        
        $data = [
            'title' => 'Rules',
            'res' => $res
        ];
        View::render('rule/index', $data, 'user');
    }

}