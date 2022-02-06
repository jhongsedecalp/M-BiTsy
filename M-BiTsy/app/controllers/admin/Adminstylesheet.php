<?php
class Adminstylesheet
{

    public function __construct()
    {
        Auth::user(_ADMINISTRATOR, 2);
    }

    public function index()
    {
        $res = DB::raw('stylesheets', '*', '');

        $data = [
            'title' => Lang::T("THEME_MANAGEMENT"),
            'sql' => $res,
        ];
        View::render('stylesheet/index', $data, 'admin');
    }

    public function add()
    {
        if ($_POST) {
            if (empty($_POST['name'])) {
                Redirect::autolink(URLROOT . "/adminstylesheet/add", Lang::T("THEME_NAME_WAS_EMPTY"));
            }
            if (empty($_POST['uri'])) {
                Redirect::autolink(URLROOT . "/adminstylesheet/add", Lang::T("THEME_FOLDER_NAME_WAS_EMPTY"));
            }

            $qry = DB::insert('stylesheets', ['name'=>$_POST["name"], 'uri'=>$_POST["uri"]]);
            if ($qry) {
                Redirect::autolink(URLROOT . "/adminstylesheet/add", "Theme '" . htmlspecialchars($_POST["name"]) . "' added.");
            } elseif ($qry->errorCode() == 1062) {
                Redirect::autolink(URLROOT . "/adminstylesheet/add", Lang::T("THEME_ALREADY_EXISTS"));
            } else {
                 Redirect::autolink(URLROOT . "/adminstylesheet/add", Lang::T("THEME_NOT_ADDED_DB_ERROR") . " " . $qry->errorInfo());
            }
        }

        $data = [
            'title' => Lang::T("Theme"),
        ];
        View::render('stylesheet/add', $data, 'admin');
    }

    public function delete()
    {
        if (!@count($_POST["ids"])) {
            Redirect::autolink(URLROOT . "/adminstylesheet", Lang::T("NOTHING_SELECTED"));
        }

        $ids = array_map("intval", $_POST["ids"]);
        $ids = implode(', ', $ids);
        DB::deleteByIds('stylesheets', 'id', $ids);
        DB::run("UPDATE `users` SET `stylesheet` = " . Config::get('DEFAULTTHEME') . " WHERE stylesheet NOT IN (SELECT id FROM stylesheets)");
        Redirect::autolink(URLROOT . "/adminstylesheet", Lang::T("THEME_SUCCESS_THEME_DELETED"));
    }

}