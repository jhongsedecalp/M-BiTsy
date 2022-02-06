<?php
class Adminmessage
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }


    public function index()
    {
        $count = DB::run("SELECT COUNT(*) FROM messages WHERE location in ('in', 'both')")->fetchColumn();
        list($pagerbuttons, $limit) = Pagination::pager(40, $count, "/adminmessage?;");
        $res = DB::run("SELECT * FROM messages WHERE location in ('in', 'both') ORDER BY id DESC $limit");

        $data = [
            'title' => Lang::T("Message Spy"),
            'res' => $res,
            'pagerbuttons' => $pagerbuttons,
        ];
        View::render('message/spypm', $data, 'admin');
    }

    public function delete()
    {
        if ($_POST["delall"]) {
            DB::delete('messages', ['active'=>'yes']);
        } else {
            if (!@count($_POST["del"])) {
                Redirect::autolink(URLROOT . '/adminmessage', Lang::T("NOTHING_SELECTED"));
            }

            $ids = array_map("intval", $_POST["del"]);
            $ids = implode(", ", $ids);
            DB::deleteByIds('messages', 'id', $ids);
        }
        Redirect::autolink(URLROOT . '/adminmessage', Lang::T("CP_DELETED_ENTRIES"));
    }

    public function masspm()
    {
        $res = DB::run("SELECT group_id, level FROM `groups`");

        $data = [
            'title' => Lang::T("Mass Private Message"),
            'res' => $res,
        ];
        View::render('message/masspm', $data, 'admin');
    }
    
    public function send()
    {
        $sender_id = ($_POST['sender'] == 'system' ? 0 : Users::get('id'));
        $msg = $_POST['msg'];
        $subject = $_POST["subject"];

        if (!$msg) {
            Redirect::autolink(URLROOT . '/adminmessage/masspm', "Please Enter Something!");
        }

        $updateset = array_map("intval", $_POST['clases']);
        $query = DB::run("SELECT id FROM users WHERE class IN (" . implode(",", $updateset) . ") AND enabled = 'yes' AND status = 'confirmed'");
        while ($dat = $query->fetch(PDO::FETCH_ASSOC)) {
            DB::insert('messages', ['sender'=>$sender_id, 'receiver'=>$dat['id'], 'added'=>TimeDate::get_date_time(), 'msg'=>$msg, 'subject'=> $subject]);
        }

        Logs::write("A Mass PM was sent by ".Users::get('id')."");
        Redirect::autolink(URLROOT . "/adminmessage/masspm", Lang::T("SUCCESS"), "Mass PM Sent!");
    }

    
    public function massemail()
    {
        $res = DB::raw('groups', 'group_id, level', '', 'ORDER BY `group_id` ASC');
        $data = [
            'title' => Lang::T("Mass Email"),
            'res' => $res,
        ];
        View::render('message/massemail', $data, 'admin');
    }

    public function sendemail()
    {
        $msg_log = "Sent to classes: ";
        @set_time_limit(0);
        $subject = $_POST["subject"];
        $body = format_comment($_POST["body"]);

        if (!$subject || !$body) {
            Redirect::autolink(URLROOT . "/adminmessage/massemail", "No subject or body specified.");
        }

        if (!@count($_POST["groups"])) {
            Redirect::autolink(URLROOT . "/adminmessage/massemail", "No groups Selected.");
        }

        $ids = array_map("intval", $_POST["groups"]);
        $ids = implode(", ", $ids);
        $res_log = DB::run("SELECT DISTINCT level FROM `groups` WHERE group_id IN ($ids)");
        while ($row_log = $res_log->fetch(PDO::FETCH_ASSOC)) {
            $msg_log .= $row_log["level"] . ", ";
        }
        
        $res = DB::run("SELECT u.email FROM users u LEFT JOIN `groups` g ON u.class = g.group_id WHERE u.enabled = 'yes' AND u.status = 'confirmed' AND u.class IN ($ids)");
        $siteemail = Config::get('SITEEMAIL');
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $TTMail = new TTMail();
            $TTMail->Send($row["email"], $subject, $body, "Content-type: text/html; charset=utf-8", "-f$siteemail");
        }

        Logs::write("<b><font color='Magenta'>A Mass E-Mail</font> was sent by (<font color='Navy'>".Users::get('username')."</font>) $msg_log<b>");
        Redirect::autolink(URLROOT . "/adminmessage/massemail", "<b><font color='#ff0000'>Mass mail sent....</font></b>");
    }

}