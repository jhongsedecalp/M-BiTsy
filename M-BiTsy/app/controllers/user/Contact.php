<?php
class Contact
{
    public function __construct()
    {
        Auth::user(0, 0);
    }

    public function index()
    {
        $data = [
            'title' => 'Contact Staff',
        ];
        View::render('contact/index', $data, 'user');
    }

    public function submit()
    {
        (new Captcha)->response($_POST['g-recaptcha-response']);
        
        $msg = Input::get("msg");
        $subject = Input::get("subject");

        if (!$msg || !$subject) {
            Redirect::autolink(URLROOT, Lang::T("NO_EMPTY_FIELDS"));
        }
        if (strlen($msg) < 10 || strlen($subject) < 5) {
            Redirect::autolink(URLROOT, Lang::T("Message or subject too short"));
        }
        
        $userid = Users::get('id') ?? 0;
        $req = DB::insert('staffmessages', [ 'sender'=>$userid,'added'=>TimeDate::get_date_time(),'msg'=>$msg,'subject'=>$subject]);
        if ($req == 1) {
            Redirect::autolink(URLROOT, Lang::T("CONTACT_SENT"));
        } else {
            Redirect::autolink(URLROOT, Lang::T("TRYLATER"));
        }
    }

}