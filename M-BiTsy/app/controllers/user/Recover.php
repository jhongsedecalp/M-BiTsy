<?php
class Recover
{

    public function __construct()
    {
        Auth::user(0, 0);
    }

    public function index()
    {
        $data = [
            'title' => 'Recover Account',
        ];
        View::render('recover/index', $data, 'user');
    }

    public function submit()
    {
        (new Captcha)->response($_POST['g-recaptcha-response']);

        $email = Input::get("email");

        if (!Validate::Email($email)) {
            Redirect::autolink(URLROOT . "/home", Lang::T("EMAIL_ADDRESS_NOT_VAILD"));
        } else {
            $arr = DB::raw('users', 'id, username, email', ['email'=>$email])->fetch();
            if (!$arr) {
                Redirect::autolink(URLROOT . "/home", Lang::T("EMAIL_ADDRESS_NOT_FOUND"));
            }
            if ($arr) {
                $sec = Helper::mksecret();
                $id = $arr['id'];
                $username = $arr['username']; // 06/01
                $emailmain = Config::get('SITEEMAIL');
                $url = URLROOT;
                $body = Lang::T("SOMEONE_FROM") . " " . $_SERVER["REMOTE_ADDR"] . " " . Lang::T("MAILED_BACK") . " ($email) " . Lang::T("BE_MAILED_BACK") . " \r\n\r\n " . Lang::T("ACCOUNT_INFO") . " \r\n\r\n " . Lang::T("USERNAME") . ": " . $username . " \r\n " . Lang::T("CHANGE_PSW") . "\n\n$url/recover/confirm?id=$id&secret=$sec\n\n\n" . $url . "\r\n";
                $TTMail = new TTMail();
                $TTMail->Send($email, Lang::T("ACCOUNT_DETAILS"), $body, "", "-f$emailmain");
                DB::update('users', ['secret'=>$sec], ['email'=>$email], 1);
                Redirect::autolink(URLROOT . "/home", sprintf(Lang::T('MAIL_RECOVER'), htmlspecialchars($email)));
            }
        }
    }

    public function confirm()
    {
        var_dump($_GET);
        $data = [
            'title' => 'Recover Account'];
        View::render('recover/confirm', $data, 'user');
    }

    public function ok()
    {
        $id = Input::get("id");
        $secret = Input::get("secret");

        if (Validate::Id(Input::get("id")) && strlen(Input::get("secret")) == 20) {
            $password = Input::get("password");
            $password1 = Input::get("password1");

            if (empty($password) || empty($password1)) {
                Redirect::autolink(URLROOT, Lang::T("NO_EMPTY_FIELDS"));
            } elseif ($password != $password1) {
                Redirect::autolink(URLROOT, Lang::T("PASSWORD_NO_MATCH"));
            } else {
                $count = DB::column('users', 'COUNT(*)', ['id'=>$id,'secret'=>$secret]);
                if ($count != 1) {
                    Redirect::autolink(URLROOT, Lang::T("NO_SUCH_USER"));
                }
                $newsec = Helper::mksecret();
                $wantpassword = password_hash($password, PASSWORD_BCRYPT);
                Users::recoverUpdate($wantpassword, $newsec, $id, $secret);
                Redirect::autolink(URLROOT, Lang::T("PASSWORD_CHANGED_OK"));
            }
        } else {
            Redirect::autolink(URLROOT, Lang::T("Wrong Imput"));
        }
    }
    
}