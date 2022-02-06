<?php
class Message
{

    public function __construct()
    {
        Auth::user(0, 2);
    }

    public function overview()
    {
        $arr = Messages::countmsg();

        $data = [
            'title' => Lang::T('MY_MESSAGES'),
            'inbox' => $arr['inbox'],
            'unread' => $arr['unread'],
            'outbox' => $arr['outbox'],
            'draft' => $arr['draft'],
            'template' => $arr['template'],
        ];
        View::render('message/overview', $data, 'user');
    }

    public function create()
    {
        $id = (int) Input::get('id');

        $username = DB::column('users', 'username', ['id'=>$id]);

        $data = [
            'title' => Lang::T('ACCOUNT_SEND_MSG'),
            'id' => $id,
            'username' => $username,
        ];
        View::render('message/create', $data, 'user');
    }

    public function submit()
    {
        $type = $_GET['type'];
        $receiver = $_POST['receiver'];
        $subject = Input::get('subject');
        $body = Input::get('body');

        if (strlen($body) < 5) {
            Redirect::autolink(URLROOT . "/message/overview", Lang::T('Body Too Short'));
        }
        if ($receiver == "") {
            Redirect::autolink(URLROOT . "/message/overview", Lang::T('EMPTY_RECEIVER'));
        }
        if (strlen($subject) < 3) {
            Redirect::autolink(URLROOT . "/message/overview", Lang::T('EMPTY_SUBJECT'));
        }
        
        if ($type == 'reply') {
            Messages::insertbytype($_REQUEST['Update'], $receiver, $subject, $body);
        } else {
            Messages::insertbytype($_REQUEST['Update'], $receiver, $subject, $body);
        }
    }

    public function read()
    {
        $id = (int) Input::get('id');
        $type = isset($_GET['type']) ? $_GET['type'] : null;

        // Set button condition
        if ($type == 'templates' || $type == 'draft') {
            $button = "<a href='" . URLROOT . "/message/update?type=$type&id=$id'><button  class='btn btn-sm ttbtn'>Edit</button></a>";
        } elseif ($type == 'inbox' || $type == 'outbox') {
            $button = " <a href='" . URLROOT . "/message/reply?type=$type&id=$id'><button  class='btn btn-sm ttbtn'>Reply</button></a>
                        <a href='" . URLROOT . "/message/update?type=$type&id=$id'><button  class='btn btn-sm ttbtn'>Edit</button></a>";
        }
        
        // get row
        $arr = DB::select('messages', '*', ['id'=>$id]);
        if ($arr["sender"] != Users::get('id') && $arr["receiver"] != Users::get('id')) {
            Redirect::autolink(URLROOT, Lang::T('NO_PERMISSION'));
        }

        // mark read
        if ($arr["unread"] == "yes" && $arr["receiver"] == Users::get('id')) {
            DB::update('messages', ['unread'=>'no'], ['id'=>$arr['id'], 'receiver'=>Users::get('id')]);
        }
        
        $data = [
            'title' => Lang::T('MESSAGE'),
            'id' => $id,
            'button' => $button,
            'sender' => $arr['sender'],
            'subject' => $arr['subject'],
            'added' => $arr['added'],
            'msg' => $arr['msg'],
        ];
        View::render('message/read', $data, 'user');
    }

    public function reply()
    {
        $url_id = isset($_GET['id']) ? $_GET['id'] : null;
        $type = isset($_GET['type']) ? $_GET['type'] : null;

        $row = Messages::getallmsg($url_id);
        if ($row["sender"] != Users::get('id') && $row["receiver"] != Users::get('id')) {
            Redirect::autolink(URLROOT, Lang::T('NO_PERMISSION'));
        }

        if ($type == 'inbox') {
            $arr2 =  DB::raw('users', 'username,id', ['id'=>$row['sender']])->fetch(PDO::FETCH_LAZY);
        } else {
            $arr2 = DB::raw('users', 'username,id', ['id'=>$row['receiver']])->fetch(PDO::FETCH_LAZY);
        }

        $username = $arr2["username"];
        $msg = $row['msg'];

        $data = [
            'username' => $username,
            'userid' => $arr2['id'],
            'msg' => $msg,
            'subject' => $row['subject'],
            'id' => $row['id'],
        ];
        View::render('message/reply', $data, 'user');
    }

    public function update()
    {
        $url_id = $_GET['id'];
        
        $row = Messages::getallmsg($url_id);
        if ($row["sender"] != Users::get('id') && $row["receiver"] != Users::get('id')) {
            Redirect::autolink(URLROOT, Lang::T('NO_PERMISSION'));
        }
        if (!$row) {
            Redirect::autolink(URLROOT . '/message?type=inbox', Lang::T("INVALID_ID"));
        }
        
        if (isset($_GET['id'])) {
            if (!empty($_POST)) {
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                $msg = isset($_POST['msg']) ? $_POST['msg'] : '';
                // Update the record
                DB::update('messages', ['msg'=>$msg], ['id'=>$id]);
                Redirect::autolink(URLROOT . '/message?type=inbox', "Edited Successfully !");
            }
        }

        $data = [
            'title' => 'Edit Message',
            'msg' => $row['msg'],
            'subject' => $row['subject'],
            'id' => $row['id'],
        ];
        View::render('message/edit', $data, 'user');
    }

    public function index()
    {
        $type = isset($_GET['type']) ? $_GET['type'] : null;

        $arr = Messages::msgPagination($type);

        $res = DB::run("SELECT * FROM messages WHERE $arr[where] $arr[limit]");
        
        $data = [
            'title' => $type,
            'pagename' => $arr['pagename'],
            'pagerbuttons' => $arr['pagerbuttons'],
            'mainsql' => $res,
        ];
        View::render('message/index', $data, 'user');
    }

    public function delete()
    {
        $type = isset($_GET['type']) ? $_GET['type'] : null;

        if ($_POST["read"]) {
            
            if (!isset($_POST["del"])) {
                Redirect::autolink(URLROOT . "/message?type=$type", Lang::T("NOTHING_SELECTED"));
            }

            $ids = array_map("intval", $_POST["del"]);
            $ids = implode(", ", $ids);
            DB::run("UPDATE messages SET `unread` = 'no' WHERE `id` IN ($ids)");
            Redirect::autolink(URLROOT . "/message?type=$type", Lang::T("COMPLETED"));

        } else {
            
            if (!isset($_POST["del"])) {
                Redirect::autolink(URLROOT . "/message?type=$type", Lang::T("NOTHING_SELECTED"));
            }

            $ids = array_map("intval", $_POST["del"]);
            $ids = implode(", ", $ids);

            if ($type == 'inbox') {
                DB::run("DELETE FROM messages WHERE `location` = 'in' AND `receiver` = ".Users::get('id')." AND `id` IN ($ids)");
                DB::run("UPDATE messages SET `location` = 'out' WHERE `location` = 'both' AND `receiver` = ".Users::get('id')." AND `id` IN ($ids)");
            } elseif ($type == 'outbox') {
                DB::run("UPDATE messages SET `location` = 'in' WHERE `location` = 'both' AND `sender` = ".Users::get('id')." AND `id` IN ($ids)");
                DB::run("DELETE FROM messages WHERE `location` IN ('out', 'draft', 'template') AND `sender` = ".Users::get('id')." AND `id` IN ($ids)");
            } elseif ($type == 'templates') {
                DB::deleteByIds('messages', ['sender'=>Users::get('id'),'location'=>'template'], $ids, 'id');
            } elseif ($type == 'draft') {
                DB::deleteByIds('messages', ['sender'=>Users::get('id'),'location'=>'draft'], $ids, 'id');
            }
            Redirect::autolink(URLROOT . "/message?type=$type", Lang::T("COMPLETED"));
            
        }
    }
}