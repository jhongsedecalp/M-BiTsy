<?php
class Adminbonus
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }


    public function index()
    {
        if ($_POST['do'] == "del") {
            if (!@count($_POST["ids"])) {
                Redirect::autolink(URLROOT . '/adminbonus', "select nothing.");
            }
            $ids = array_map("intval", $_POST["ids"]);
            $ids = implode(", ", $ids);
            DB::deleteByIds('bonus', 'id', $ids);
            Redirect::autolink(URLROOT."/adminbonus", "deleted entries");
        }

        $count = get_row_count("bonus");
        list($pagerbuttons, $limit) = Pagination::pager(10, $count, 'adminbonus&amp;');
        $res = DB::raw('bonus', 'id, title, cost, value, descr, type', '', "ORDER BY `type` $limit");

        $data = [
            'title' => Lang::T("Seedbonus Manager"),
            'count' => $count,
            'pagerbuttons' => $pagerbuttons,
            'limit' => $limit,
			'res' => $res,
        ];
        View::render('bonus/seedbonus', $data, 'admin');
    }

    public function change()
    {
        $row = null;

        if (Validate::Id($_REQUEST['id'])) {
            $res = DB::raw('bonus', 'id, title, cost, value, descr, type', ['id'=>$_REQUEST['id']]);
            $row = $res->fetch(PDO::FETCH_LAZY);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['title']) or empty($_POST['descr']) or empty($_POST['type']) or !is_numeric($_POST['cost'])) {
                Redirect::autolink($_SERVER['HTTP_REFERER'], "missing information.");
            }

            $_POST["value"] = ($_POST["type"] == "traffic") ? strtobytes($_POST["value"]) : (int) $_POST["value"];
            $var = array_map('sqlesc', $_POST);
            extract($var);

            if ($row == null) {
                DB::insert('bonus', ['title'=>$title, 'descr'=>$descr, 'cost'=>$cost, 'value'=>$value, 'type'=>$type]);
            } else {
                DB::update('bonus', ['title' => $title, 'descr' => $descr, 'cost' => $cost, 'value' => $value, 'type' => $type], ['id' => $id]);
            }
            Redirect::autolink(URLROOT . "/adminbonus", "Updating the bonus seed.");
        }

        $data = [
            'title' => Lang::T("Seedbonus Manager"),
            'row' => $row,
        ];
        View::render('bonus/change', $data, 'admin');
    }

}