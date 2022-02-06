<?php
class Signup
{
    public function __construct()
    {
        Auth::user(0, 0);
    }

    public function index()
    {
        //check if IP is already a peer
        if (Config::get('IPCHECK')) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $ipq = get_row_count("users", "WHERE ip = '$ip'");
            if ($ipq >= Config::get('ACCOUNTMAX')) {
                Redirect::autolink(URLROOT . '/login', "This IP is already in use !");
            }
        }

        // Check if we're signing up with an invite
        $invite = Input::get("invite");
        $secret = Input::get("secret");

        $invite_row = 0;
        if (!Validate::Id($invite) || strlen($secret) != 20) {
            if (Config::get('INVITEONLY')) {
                Redirect::autolink(URLROOT . '/home', "<center>" . Lang::T("INVITE_ONLY_MSG") . "<br></center>");
            }
        } else {
            $invite_row = DB::select('users', 'id', ['id'=>$invite, 'secret'=>$secret]);
            if (!$invite_row) {
                Redirect::autolink(URLROOT . '/home', Lang::T("INVITE_ONLY_NOT_FOUND") . "" . (Config::get('SIGNUPTIMEOUT') / 86400) . "days.");
            }
        }
        
        $data = [
            'title' => Lang::T("SIGNUP"),
            'invite' => $invite_row,
        ];
        View::render('signup/index', $data, 'user');
    }

    public function submit()
    {
        if (Input::exist()) {
            (new Captcha)->response($_POST['g-recaptcha-response']);
            $wantusername = Input::get("wantusername");
            $email = Input::get("email");
            $wantpassword = Input::get("wantpassword");
            $passagain = Input::get("passagain");
            $country = Input::get("country");
            $gender = Input::get("gender");
            $client = Input::get("client");
            $age = Input::get("age");
            // Is It A Invite
            $invite = Input::get("invite");
            $secret = Input::get("secret");

            if (strlen($secret) == 20 || !is_numeric($invite)) {
                $invite_row = DB::select('users', 'id', ['id'=>$invite, 'secret'=>$secret]);
            }

            $message = $this->validSign($wantusername, $email, $wantpassword, $passagain, $invite_row);
            
            if ($message == "") {
                // If NOT Invite Check
                if (!$invite_row) {
                    // get max members, and check how many users there is
                    $numsitemembers = get_row_count("users");
                    if ($numsitemembers >= Config::get('MAXUSERS')) {
                        $msg = Lang::T("SITE_FULL_LIMIT_MSG") . number_format(Config::get('MAXUSERS')) . " " . Lang::T("SITE_FULL_LIMIT_REACHED_MSG") . " " . number_format($numsitemembers) . " members";
                        Redirect::autolink(URLROOT . '/home', $msg);
                    }
                    // check email isnt banned
                    $maildomain = (substr($email, strpos($email, "@") + 1));
                    $a = DB::column('email_bans', 'COUNT(*)', ['mail_domain'=>$email]);
                    if ($a != 0) {
                        $message = sprintf(Lang::T("EMAIL_ADDRESS_BANNED_S"), $email);
                    }
                    $a = DB::run("SELECT count(*) FROM email_bans where mail_domain LIKE '%$maildomain%'")->fetchColumn();
                    if ($a != 0) {
                        $message = sprintf(Lang::T("EMAIL_ADDRESS_BANNED_S"), $email);
                    }
                    // check if email addy is already in use
                    $a = DB::column('users', 'COUNT(*)', ['email'=>$email]);
                    if ($a != 0) {
                        $message = sprintf(Lang::T("EMAIL_ADDRESS_INUSE_S"), $email);
                    }
                }

                //check username isnt in use
                $count = DB::column('users', 'COUNT(*)', ['username'=>$wantusername]);
                if ($count != 0) {
                    $message = sprintf(Lang::T("USERNAME_INUSE_S"), $wantusername);
                }

                $secret = Helper::mksecret(); //generate secret field
                $wantpassword = password_hash($wantpassword, PASSWORD_BCRYPT); // hash the password
            }

            // Checks Returns Message
            if ($message != "") {
                Redirect::autolink(URLROOT . '/login', $message);
            }

            if ($message == "") {
                // Invited User
                if ($invite_row) {
                    DB::update('users', ['username'=>$wantusername, 'password'=>$wantpassword, 'secret'=>$secret, 'status'=>'confirmed', 'added'=>TimeDate::get_date_time()], ['id'=>$invite_row['id']]);
                    
                    if (Config::get('WELCOMEPM_ON')) {
                        $dt = TimeDate::get_date_time();
                        $msg = Config::get('WELCOMEPM_MSG');
                        Messages::insert(['sender'=>0, 'receiver'=>$invite_row['id'], 'added'=>$dt, 'subject'=>'Welcome', 'msg'=>$msg, 'unread'=>'yes', 'location'=>'in']);
                    }
                    
                    $msg_shout = "New User: " . $wantusername . " has joined.";
                    DB::insert('shoutbox', ['userid'=>0, 'date'=>TimeDate::get_date_time(), 'user'=>'System', 'message'=>$msg_shout]);
                    Redirect::autolink(URLROOT . '/login', Lang::T("ACCOUNT_ACTIVATED"));
                }

                if (Config::get('CONFIRMEMAIL') || Config::get('ACONFIRM')) {
                    $status = "pending";
                } else {
                    $status = "confirmed";
                }
                
                if ($numsitemembers == '0') {
                    $signupclass = '7';
                } else {
                    $signupclass = '1';
                }

                DB::run("
                    INSERT INTO users
                    (username, password, secret, email, status, added, last_login,
                    last_access, age, country, gender, client, stylesheet, language, class, ip)
                    VALUES
                    (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                    [$wantusername, $wantpassword, $secret, $email, $status, TimeDate::get_date_time(),
                        TimeDate::get_date_time(), TimeDate::get_date_time(), $age, $country, $gender,
                        $client, Config::get('DEFAULTTHEME'), Config::get('DEFAULTLANG'), $signupclass, Ip::getIP()]);
                $id = DB::lastInsertId();
    
                $msg_shout = "New User: " . $wantusername . " has joined.";
                DB::insert('shoutbox', ['userid'=>0, 'date'=>TimeDate::get_date_time(), 'user'=>'System', 'message'=>$msg_shout]);

                if (Config::get('WELCOMEPM_ON')) {
                    $dt = TimeDate::get_date_time();
                    $mess = Config::get('WELCOMEPM_MSG');
                    Messages::insert(['sender'=>0, 'receiver'=>$id, 'added'=>$dt, 'subject'=>'Welcome', 'msg'=>$mess, 'unread'=>'yes', 'location'=>'in']);
                }

                if (Config::get('ACONFIRM')) {
                    $body = Lang::T("YOUR_ACCOUNT_AT") . " " . Config::get('SITENAME') . " " . Lang::T("HAS_BEEN_CREATED_YOU_WILL_HAVE_TO_WAIT") . "\n\n" . Config::get('SITENAME') . " " . Lang::T("ADMIN");
                } else {
                    $body = Lang::T("YOUR_ACCOUNT_AT") . " " . Config::get('SITENAME') . " " . Lang::T("HAS_BEEN_APPROVED_EMAIL") . "\n\n	" . URLROOT . "/confirmemail/signup?id=$id&secret=$secret\n\n" . Lang::T("HAS_BEEN_APPROVED_EMAIL_AFTER") . "\n\n	" . Lang::T("HAS_BEEN_APPROVED_EMAIL_DELETED") . "\n\n" . URLROOT . " " . Lang::T("ADMIN");
                }

                if (Config::get('CONFIRMEMAIL') || Config::get('ACONFIRM')) {
                    $TTMail = new TTMail();
                    $TTMail->Send($email, "Your " . Config::get('SITENAME') . " User Account", $body, "", "-f" . Config::get('SITEEMAIL') . "");
                    Redirect::autolink(URLROOT . '/login', Lang::T("A_CONFIRMATION_EMAIL_HAS_BEEN_SENT") . " (" . htmlspecialchars($email) . "). " . Lang::T("ACCOUNT_CONFIRM_SENT_TO_ADDY_REST") . " <br/ >");
                } else {
                    Redirect::autolink(URLROOT . '/login', Lang::T("ACCOUNT_ACTIVATED"));
                }
            }
        } else {
            Redirect::to(URLROOT . "/signup");
        }
    }

    public function validSign($wantusername, $email, $wantpassword, $passagain, $invite_row)
    {
        if (Validate::isEmpty($wantpassword) || (Validate::isEmpty($email) && !$invite_row) || Validate::isEmpty($wantusername)) {
            $message = Lang::T("DONT_LEAVE_ANY_FIELD_BLANK");
        } elseif (strlen($wantusername) > 50) {
            $message = sprintf(Lang::T("USERNAME_TOO_LONG"), 16);
        } elseif ($wantpassword != $passagain) {
            $message = Lang::T("PASSWORDS_NOT_MATCH");
        } elseif (strlen($wantpassword) < 6) {
            $message = sprintf(Lang::T("PASS_TOO_SHORT_2"), 6);
        } elseif (strlen($wantpassword) > 16) {
            $message = sprintf(Lang::T("PASS_TOO_LONG_2"), 16);
        } elseif ($wantpassword == $wantusername) {
            $message = Lang::T("PASS_CANT_MATCH_USERNAME");
        } elseif (!Validate::username($wantusername)) {
              $message = "Invalid username.";
        } elseif (!$invite_row && !Validate::Email($email)) {
            $message = "That doesn't look like a valid email address.";
        }
        return $message;
    }

}