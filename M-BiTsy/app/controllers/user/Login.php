<?php
class Login
{

    public function __construct()
    {
        Auth::ipBanned();
        Auth::isClosed();
        $this->token = Cookie::csrf_token();
        Cookie::destroyAll();
    }

    public function index()
    {
        $data = [
            'token' => $this->token,
            'title' => Lang::T("LOGIN"),
        ];
        View::render('login/index', $data, 'user');
    }

    public function submit()
    {
        // check if using google captcha
        (new Captcha)->response(Input::get('g-recaptcha-response'));
        if (Input::exist() && Cookie::csrf_check()) {
            $username = Input::get("username");
            $password = Input::get("password");
            
            $sql = DB::raw('users', 'id, password, secret, status, enabled', ['username' =>$username])->fetch();
            if (!$sql || !password_verify($password, $sql['password'])) {
                Redirect::autolink(URLROOT . "/logout", Lang::T("LOGIN_INCORRECT"));
            } elseif ($sql['status'] == "pending") {
                Redirect::autolink(URLROOT . "/logout", Lang::T("ACCOUNT_PENDING"));
            } elseif ($sql['enabled'] == "no") {
                Redirect::autolink(URLROOT . "/logout", Lang::T("ACCOUNT_DISABLED"));
            }
            
            Cookie::setAll($sql['id'], $sql['password'], $this->loginString());
            DB::update('users', ['last_login'=>TimeDate::get_date_time(), 'token'=>$this->loginString()], ['id'=>$sql['id']]);
            Redirect::to(URLROOT);
        } else {
            Redirect::to(URLROOT . "/logout");
        }
    }

    private function loginString()
    {
        $ip = Ip::getIP();
        $browser = Ip::agent();
        return md5($browser . $browser);
    }

}