<?php
class Adminpoll
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        $query = DB::raw('polls', 'id,question,added', '', 'ORDER BY added DESC');

        $data = [
            'title' => Lang::T("POLLS_MANAGEMENT"),
            'query' => $query,
        ];
        View::render('poll/index', $data, 'admin');
    }

    public function results()
    {
        $poll = DB::raw('pollanswers', '*', '', 'ORDER BY pollid DESC');

        $data = [
            'title' => Lang::T("POLLS_MANAGEMENT"),
            'poll' => $poll,
        ];
        View::render('poll/results', $data, 'admin');
    }

    public function delete()
    {
        $id = (int) $_GET["id"];

        if (!Validate::Id($id)) {
            Redirect::autolink(URLROOT."/adminpoll", sprintf(Lang::T("CP_NEWS_INVAILD_ITEM_ID").$id));
        }

        DB::delete('polls', ['id'=>$id]);
        DB::delete('pollanswers', ['pollid'=>$id]);
        Redirect::autolink(URLROOT . "/adminpoll", Lang::T("Poll and answers deleted"));
    }

    public function add()
    {
        $pollid = (int) $_GET["pollid"];

        $res = DB::raw('polls', '*', ['id'=>$pollid]);

        $data = [
            'title' => Lang::T("POLLS_MANAGEMENT"),
            'res' => $res,
            'id' => $pollid
        ];
        View::render('poll/add', $data, 'admin');
    }

    public function save()
    {
        $subact = $_POST["subact"];
        $pollid = (int) $_POST["pollid"];

        $data = [
            'question' => $_POST["question"],
            'option0' => $_POST["option0"],
            'option1' => $_POST["option1"],
            'option2' => $_POST["option2"],
            'option3' => $_POST["option3"],
            'option4' => $_POST["option4"],
            'option5' => $_POST["option5"],
            'option6' => $_POST["option6"],
            'option7' => $_POST["option7"],
            'option8' => $_POST["option8"],
            'option9' => $_POST["option9"],
            'option10' => $_POST["option10"],
            'sort' => (int) $_POST["sort"],
            'added' => TimeDate::get_date_time()
        ];

        if (!$data['question'] || !$data['option0'] || !$data['option1']) {
            Redirect::autolink(URLROOT."/adminpoll", Lang::T("MISSING_FORM_DATA") . "!");
        }

        if ($subact == "edit") {
            if (!Validate::Id($pollid)) {
                Redirect::autolink(URLROOT."/adminpoll", Lang::T("INVALID_ID"));
            }

            DB::update("polls", $data, ['id'=>$pollid]);
        } else {
            DB::insert("polls", $data);
        }
        Redirect::autolink(URLROOT . "/adminpoll", Lang::T("COMPLETE"));
    }

}