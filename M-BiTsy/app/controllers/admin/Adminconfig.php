<?php
class Adminconfig
{

    public function __construct()
    {
        Auth::user(_ADMINISTRATOR, 2);
    }

    // Check admin name !!!
    public function check()
    {
        if (!in_array(Users::get("username"), _OWNERS)) {
            Redirect::to(URLROOT . "/logout");
        }
        if (Users::get("control_panel") != "yes") {
            Redirect::to(URLROOT . "/logout");
        }
    }

    // Load The Form
    public function index()
    {
        $this->check();
        $data = [
            'title' => 'Config',
        ];
        View::render('admincp/config', $data, 'admin');
    }

    // I'M LAZY SO LETS UPDATE ALL AT ONCE
    public function submit()
    {
        //var_dump($_POST);die();
        $this->check();
        if (!$_POST) {
            Redirect::to(URLROOT . "/logout");
        }

        $data = array (
            'CAPTCHA_ON' => (int) $_POST['CAPTCHA_ON'],
            'CAPTCHA_KEY' => $_POST['CAPTCHA_KEY'],
            'CAPTCHA_SECRET' => $_POST['CAPTCHA_SECRET'],
            'SITENAME' => $_POST['SITENAME'],
            '_SITEDESC' => $_POST['_SITEDESC'],
            'SITEEMAIL' => $_POST['SITEEMAIL'],
            'CACHE_TYPE' => $_POST['CACHE_TYPE'],
            'MEMCACHE_HOST' => $_POST['MEMCACHE_HOST'],
            'MEMCACHE_PORT' => (int) $_POST['MEMCACHE_PORT'],
            'SITENOTICEON' => (int) $_POST['SITENOTICEON'],
            'SITENOTICE' => $_POST['SITENOTICE'],
            'DEFAULTLANG' => $_POST['DEFAULTLANG'],
            'DEFAULTTHEME' => $_POST['DEFAULTTHEME'],
            'MEMBERSONLY' => (int) $_POST['MEMBERSONLY'],
            'MEMBERSONLY_WAIT' => (int) $_POST['MEMBERSONLY_WAIT'],
            'ALLOWEXTERNAL' => (int) $_POST['ALLOWEXTERNAL'],
            'UPLOADERSONLY' => (int) $_POST['UPLOADERSONLY'],
            'INVITEONLY' => (int) $_POST['INVITEONLY'],
            'ENABLEINVITES' => (int) $_POST['ENABLEINVITES'],
            'CONFIRMEMAIL' => (int) $_POST['CONFIRMEMAIL'],
            'ACONFIRM' => (int) $_POST['ACONFIRM'],
            'ANONYMOUSUPLOAD' => (int) $_POST['ANONYMOUSUPLOAD'],
            'UPLOADSCRAPE' => (int) $_POST['UPLOADSCRAPE'],
            'FORUMS' => (int) $_POST['FORUMS'],
            'FORUMS_GUESTREAD' => (int) $_POST['FORUMS_GUESTREAD'],
            'OLD_CENSOR' => (int) $_POST['OLD_CENSOR'],
            'MAXUSERS' => (int) $_POST['MAXUSERS'],
            'MAXUSERSINVITE' => (int) $_POST['MAXUSERSINVITE'],
            'CURRENCYSYMBOL' => $_POST['CURRENCYSYMBOL'],
            'BONUSPERTIME' => (float) $_POST['BONUSPERTIME'],
            'ADDBONUS' => (int) $_POST['ADDBONUS'],
            'FORCETHANKS' => (int) $_POST['FORCETHANKS'],
            'ALLOWLIKES' => (int) $_POST['ALLOWLIKES'],
            'SITE_ONLINE' => (int) $_POST['SITE_ONLINE'],
            'OFFLINEMSG' => $_POST['OFFLINEMSG'],
            'WELCOMEPM_ON' => (int) $_POST['WELCOMEPM_ON'],
            'WELCOMEPM_MSG' => $_POST['WELCOMEPM_MSG'],
            'UPLOADRULES' => $_POST['UPLOADRULES'],
            'LEFTNAV' => (int) $_POST['LEFTNAV'],
            'RIGHTNAV' => (int) $_POST['RIGHTNAV'],
            'MIDDLENAV' => (int) $_POST['MIDDLENAV'],
            'SHOUTBOX' => (int) $_POST['SHOUTBOX'],
            'NEWSON' => (int) $_POST['NEWSON'],
            'DONATEON' => (int) $_POST['DONATEON'],
            'DISCLAIMERON' => (int) $_POST['DISCLAIMERON'],
            'PEERLIMIT' => (int) $_POST['PEERLIMIT'],
            'AUTOCLEANINTERVAL' => (int) $_POST['AUTOCLEANINTERVAL'],
            'ANNOUNCEINTERVAL' => (int) $_POST['ANNOUNCEINTERVAL'],
            'SIGNUPTIMEOUT' => (int) $_POST['SIGNUPTIMEOUT'],
            'MAXDEADTORRENTTIMEOUT' => (int) $_POST['MAXDEADTORRENTTIMEOUT'],
            'RATIOWARNENABLE' => (int) $_POST['RATIOWARNENABLE'],
            'RATIOWARNMINRATIO' => (float) $_POST['RATIOWARNMINRATIO'],
            'RATIOWARN_MINGIGS' => (int) $_POST['RATIOWARN_MINGIGS'],
            'RATIOWARN_DAYSTOWARN' => (int) $_POST['RATIOWARN_DAYSTOWARN'],
            'TORRENTTABLE_COLUMNS' => $_POST['TORRENTTABLE_COLUMNS'],
            'mail_type' => $_POST['mail_type'],
            'mail_smtp_host' => $_POST['mail_smtp_port'],
            'mail_smtp_port' => (int) $_POST['mail_smtp_port'],
            'mail_smtp_ssl' => (int) $_POST['mail_smtp_ssl'],
            'mail_smtp_auth' => (int) $_POST['mail_smtp_auth'],
            'mail_smtp_user' => $_POST['mail_smtp_user'],
            'mail_smtp_pass' => $_POST['mail_smtp_pass'],
            'FORUMONINDEX' => (int) $_POST['FORUMONINDEX'],
            'LATESTFORUMPOSTONINDEX' => (int) $_POST['LATESTFORUMPOSTONINDEX'],
            'IPCHECK' => (int) $_POST['IPCHECK'],
            'ACCOUNTMAX' => (int) $_POST['ACCOUNTMAX'],
            'YOU_TUBE' => (int) $_POST['YOU_TUBE'],
            'FREELEECHGBON' => (int) $_POST['FREELEECHGBON'],
            'FREELEECHGB' => (int) $_POST['FREELEECHGB'],
            'REQUESTSON' => (int) $_POST['REQUESTSON'],
            'HIDEBBCODE' => (int) $_POST['HIDEBBCODE'],

            'CLASS_WAIT' => (int) $_POST['CLASS_WAIT'],
            'GIGSA' => (int) $_POST['GIGSA'],
            'RATIOA' => (float) $_POST['RATIOA'],
            'A_WAIT' => (int) $_POST['A_WAIT'],
            'GIGSB' => (int) $_POST['GIGSB'],
            'RATIOB' => (float) $_POST['RATIOB'],
            'B_WAIT' => (int) $_POST['B_WAIT'],
            'GIGSC' => (int) $_POST['GIGSC'],
            'RATIOC' => (float) $_POST['RATIOC'],
            'C_WAIT' => (int) $_POST['C_WAIT'],
            'GIGSD' => (int) $_POST['GIGSD'],
            'RATIOD' => (float) $_POST['RATIOD'],
            'D_WAIT' => (int) $_POST['D_WAIT'],
            'LOGCLEAN' => (int) $_POST['LOGCLEAN'],
            'HNR_ON' => (int) $_POST['HNR_ON'],
            'HNR_DEADLINE' => (int) $_POST['HNR_DEADLINE'],
            'HNR_SEEDTIME' => (int) $_POST['HNR_SEEDTIME'],
            'HNR_WARN' => (int) $_POST['HNR_WARN'],
            'HNR_STOP_DL' => (int) $_POST['HNR_STOP_DL'],
            'HNR_BAN' => (int) $_POST['HNR_BAN'],
            );
        file_put_contents(APPROOT.'/config/settings.php', "<?php\nreturn " . var_export($data, true) . "\n?>");
        Redirect::autolink(URLROOT . "/adminconfig", 'Settings Saved');
    }

    public function backup()
    {
        $this->check();
        $file = APPROOT.'/config/settings.php';
        $newfile = APPROOT.'/config/settings.php.bak';
        
        if (!copy($file, $newfile)) {
            Redirect::autolink(URLROOT . "/adminconfig", 'Backup Failed');
        }
        Redirect::autolink(URLROOT . "/adminconfig", 'Success');
    }

}