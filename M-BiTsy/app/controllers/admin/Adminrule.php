<?php
class Adminrule
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        $res = DB::raw('rules', '*', '', 'ORDER BY id');

        $data = [
            'title' => Lang::T("SITE_RULES_EDITOR"),
            'res' => $res,
        ];
        View::render('rule/index', $data, 'admin');
    }

    public function edit()
    {
        if ($_GET["save"] == "1") {
            $id = (int) $_POST["id"];
            $title = $_POST["title"];
            $text = $_POST["text"];
            $public = $_POST["public"];
            $class = $_POST["class"];
            DB::update('rules', ['title' =>$title, 'text' =>$text,'public' =>$public, 'class' =>$class], ['id' => $id]);
            Logs::write("Rules have been changed by ".Users::get('username')."");
            Redirect::autolink(URLROOT."/adminrule", "Rules edited ok<br /><br /><a href=" . URLROOT . "/adminrule>Back To Rules</a>");
        }

        $id = (int) $_POST["id"];
        $res = DB::raw('rules', '*', ['id'=>$id]);
        
        $data = [
            'title' => Lang::T("SITE_RULES_EDITOR"),
            'id' => $id,
            'res' => $res,
        ];
        View::render('ruls/edit', $data, 'admin');
    }

    public function addsect()
    {
        if ($_GET["save"] == "1") {
            $title = $_POST["title"];
            $text = $_POST["text"];
            $public = $_POST["public"];
            $class = $_POST["class"];
            DB::insert('rules', ['title'=>$title, 'text'=>$text, 'public'=>$public, 'class'=>$class]);
            Redirect::autolink(URLROOT."/adminrule", "New Section Added<br /><br /><a href=" . URLROOT . "/adminrule>Back To Rules</a>");
        }

        $data = [
            'title' => Lang::T("SITE_RULES_EDITOR"),
        ];
        View::render('rule/addsect', $data, 'admin');
    }

}