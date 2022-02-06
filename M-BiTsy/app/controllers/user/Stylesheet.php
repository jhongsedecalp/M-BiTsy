<?php
class Stylesheet
{

    public function __construct()
    {
        Auth::user(0, 2);
    }

    public function index()
    {
        $stylesheet = Input::get('stylesheet');
        $language = Input::get('language');

        $updateset = array();
        $updateset["stylesheet"] = $stylesheet;
        $updateset["language"] = $language;

        if (count($updateset)) {
            DB::update("users", $updateset, ['id' =>Users::get('id')]);
        }
        if (empty($_SERVER["HTTP_REFERER"])) {
            Redirect::to(URLROOT);
            return;
        }
        Redirect::to($_SERVER["HTTP_REFERER"]);
    }

}