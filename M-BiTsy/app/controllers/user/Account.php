<?php
class Account
{
    public function __construct()
    {
        Auth::user(0, 2);
    }

    public function index()
    {
        Redirect::to(URLROOT);
    }

    public function password()
    {
        $id = (int) Input::get("id");

        if (Users::get('class') < _MODERATOR && $id != Users::get('id')) {
            Redirect::autolink(URLROOT . "/index", Lang::T("NO_PERMISSION"));
        }

        $data = [
            'title' => Lang::T('CHANGE_PASS'),
            'id' => $id,
        ];
        View::render('account/password', $data, 'user');
    }

    public function passwordchanged()
    {
        $id = (int) Input::get("id");
        $chpassword = Input::get('chpassword');
        $passagain = Input::get('passagain');

        if (Users::get('class') < _MODERATOR && $id != Users::get('id')) {
            Redirect::autolink(URLROOT . "/index", Lang::T("NO_PERMISSION"));
        }

        if ((!$chpassword) || (!$passagain)) {
            Redirect::autolink(URLROOT . "/account/password?id=$id", Lang::T("YOU_DID_NOT_ENTER_ANYTHING"));
        }
        if (strlen($chpassword) < 6) {
            Redirect::autolink(URLROOT . "/account/password?id=$id", Lang::T("PASS_TOO_SHORT"));
        }
        if ($chpassword != $passagain) {
            Redirect::autolink(URLROOT . "/account/password?id=$id", Lang::T("PASSWORDS_NOT_MATCH"));
        }
        
        $chpassword = password_hash($chpassword, PASSWORD_BCRYPT);
        $secret = Helper::mksecret();
        
        $in = DB::update('users', ['password'=>$chpassword, 'secret'=>$secret], ['id'=>$id]);
        if ($in > 0) {
            Redirect::autolink(URLROOT . "/logout", Lang::T("PASSWORD_CHANGED_OK"));
        } else {
            Redirect::autolink(URLROOT . "/account/password?id=$id", Lang::T("ERROR"));
        }
    }

    public function email()
    {
        $id = (int) Input::get("id");

        if ($id != Users::get('id')) {
            Redirect::autolink(URLROOT . "/index", Lang::T("NO_PERMISSION"));
        }

        if (Input::exist()) {
            $email = $_POST["email"];

            if (!Validate::Email($email)) {
                Redirect::autolink(URLROOT, Lang::T("INVALID_EMAIL_ADDRESS"));
            }

            $sec = Helper::mksecret();
            $obemail = rawurlencode($email);
            $sitename = URLROOT;

            $body = file_get_contents(APPROOT . "/views/user/email/changeemail.php");
            $body = str_replace("%usersname%", Users::get("username"), $body);
            $body = str_replace("%sitename%", $sitename, $body);
            $body = str_replace("%usersip%", $_SERVER["REMOTE_ADDR"], $body);
            $body = str_replace("%usersid%", Users::get("id"), $body);
            $body = str_replace("%userssecret%", $sec, $body);
            $body = str_replace("%obemail%", $obemail, $body);
            $body = str_replace("%newemail%", $email, $body);

            $TTMail = new TTMail();
            $TTMail->Send($email, "$sitename profile update confirmation", $body, "From: " . Config::get('SITEEMAIL') . "", "-f" . Config::get('SITEEMAIL') . "");
            DB::update('users', ['editsecret'=>$sec], ['id'=>Users::get('id')]);
            Redirect::autolink(URLROOT . "/profile?id=$id", Lang::T("EMAIL_CHANGE_SEND"));
        }

        $user = DB::select('users', 'email', ['id'=>$id]);
        $data = [
            'id' => $id,
            'email' => $user['email'],
        ];
        View::render('account/email', $data, 'user');
    }

    public function avatar()
    {
        $id = (int) Input::get("id");

        if ($id != Users::get('id')) {
            Redirect::autolink(URLROOT . "/index", Lang::T("NO_PERMISSION"));
        }

        if (isset($_FILES["upfile"])) {
            $upload = new Uploader($_FILES["upfile"]);
            $upload->must_be_image();
            $upload->max_size(100); // in MB
            $upload->max_image_dimensions(130, 130);
            $upload->encrypt_name();
            $upload->path("uploads/avatars");
            if (!$upload->upload()) {
                Redirect::autolink(URLROOT . "/profile/edit?id=$id", "Upload error: " . $upload->get_error() . " image should be 90px x 90px or lower");
            } else {
                $avatar = URLROOT . "/uploads/avatars/" . $upload->get_name();
                DB::update('users', ['avatar'=>$avatar], ['id'=>$id]);
                Redirect::autolink(URLROOT . "/profile/edit?id=$id", Lang::T("UP_AVATAR")." OK");
            }
        }
        
        $data = [
            'title' => Lang::T("AVATAR_UPLOAD"),
            'id' => $id,
        ];
        View::render('account/avatar', $data, 'user');
    }

}