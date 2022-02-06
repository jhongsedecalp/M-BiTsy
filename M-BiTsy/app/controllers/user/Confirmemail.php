<?php
class Confirmemail
{

    public function __construct()
    {
        Auth::user(0, 0);
    }

    
    public function index()
    {
        Redirect::to(URLROOT);
    }

    public function signup()
    {
        $id = (int) Input::get("id");
        $md5 = Input::get("secret");

        if (!$id || !$md5) {
            Redirect::autolink(URLROOT, Lang::T("INVALID_ID"));
        }

        $row = DB::raw('users', 'password,secret,status', ['id'=>$id])->fetch();
        if (!$row) {
            $mgs = sprintf(Lang::T("CONFIRM_EXPIRE"), Config::get('SIGNUPTIMEOUT') / 86400);
            Redirect::autolink(URLROOT, $mgs);
        }
        if ($row['status'] != "pending") {
            Redirect::autolink(URLROOT, Lang::T("ACCOUNT_ACTIVATED"));
            die;
        }
        if ($md5 != $row['secret']) {
            Redirect::autolink(URLROOT, Lang::T("SIGNUP_ACTIVATE_LINK"));
        }

        $secret = Helper::mksecret();
        $upd = Users::updatesecret($secret, $id, $row['secret']);
        if ($upd == 0) {
            Redirect::autolink(URLROOT, Lang::T("SIGNUP_UNABLE"));
        }
        Redirect::autolink(URLROOT . '/login', Lang::T("ACCOUNT_ACTIVATED"));
    }

    public function account()
    {
        $id = (int) Input::get("id");
        $md5 = Input::get("secret");
        $email = Input::get("email");

        if (!$id || !$md5 || !$email) {
            Redirect::autolink(URLROOT . "/home",  Lang::T("MISSING_FORM_DATA"));
        }

        $row = Users::getEditsecret($id);
        if (!$row) {
            Redirect::autolink(URLROOT . "/home",  Lang::T("NOTHING_FOUND"));
        }
        
        if ($md5 != $row['editsecret']) {
            Redirect::autolink(URLROOT . "/home",  Lang::T("NOTHING_FOUND"));
        }
        
        Users::updateUserEmailResetEditsecret($email, $id, $row['editsecret']);
        Redirect::autolink(URLROOT . "/home", Lang::T("SUCCESS"));
    }

}