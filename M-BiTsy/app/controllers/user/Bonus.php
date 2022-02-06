<?php
class Bonus
{

    public function __construct()
    {
        Auth::user(0, 2);
    }

    public function index()
    {
        $bonus = DB::raw('bonus', '*', '', 'ORDER BY type');
        $data = [
            'title' => Lang::T('Seed Bonus'),
            'bonus' => $bonus,
            'usersbonus' => Users::get('seedbonus'),
            'configbonuspertime' => Config::get('BONUSPERTIME'),
            'configautoclean_interval' => floor(Config::get('ADDBONUS') / 60),
			'usersid' => Users::get('id'),
        ];
        View::render('bonus/index', $data, 'user');
    }

    public function submit()
    {
        $id = (int) Input::get("id");

        if (Validate::Id($id)) {

            $row = DB::select('bonus', 'type, value, cost', ['id' =>$id]);
            if (!$row || Users::get('seedbonus') < $row['cost']) {
                Redirect::autolink(URLROOT."/bonus", "Demand not valid.");
            }

            DB::run("UPDATE `users` SET `seedbonus` = `seedbonus` - '$row[cost]' WHERE `id` = ".Users::get('id')."");
            Bonuses::switch($row);
            Redirect::autolink(URLROOT."/bonus", "Your account has been credited.");
        }

    }

}