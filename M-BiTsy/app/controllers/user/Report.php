<?php
class Report
{

    public function __construct()
    {
        Auth::user(0, 2);
    }

    public function index()
    {
        Redirect::autolink(URLROOT, Lang::T("NO_ID"));
    }

    public function user()
    {
        $takeuser = (int) Input::get("user");
        $takereason = Input::get("reason");
        $user = (int) Input::get("id");

        if (Users::get("view_users") == "no") {
            Redirect::autolink(URLROOT, Lang::T("NO_USER_VIEW"));
        }

        if ($takeuser) {
            if (empty($takereason)) {
                Redirect::autolink(URLROOT . "/report/user?user=$user", Lang::T("YOU_MUST_ENTER_A_REASON"));
            }
            $res = DB::raw('reports', 'id', [ 'addedby'=>Users::get('id'),'votedfor'=>$takeuser,'type'=>'user']);
            if ($res->rowCount() == 0) {
                DB::insert('reports', ['addedby'=>Users::get('id'),'votedfor'=>$takeuser,'votedfor_xtra'=>0,'type'=>'user','reason'=>$takereason]);
                Redirect::autolink(URLROOT . "/profile?id=$user", "User: $takeuser, Reason: " . htmlspecialchars($takereason) . "<p>Successfully Reported</p>");
            } else {
                Redirect::autolink(URLROOT . "/profile?id=$user", Lang::T("YOU_HAVE_ALREADY_REPORTED") . " user $takeuser");
            }
        }

        if ($user != "") {
            $res = DB::raw('users', 'username, class', ['id' => $user]);
            if ($res->rowCount() == 0) {
                Redirect::autolink(URLROOT, Lang::T("INVALID_USERID"));
            }
            $arr = $res->fetch(PDO::FETCH_ASSOC);
            
            $data = [
                'title' => 'Report',
                'username' => $arr['username'],
                'user' => $user,
            ];
            View::render('report/user', $data, 'user');
        } else {
            Redirect::autolink(URLROOT . "/profile?id=$user", Lang::T("MISSING_INFO"));
        }
    }

    public function torrent()
    {
        $taketorrent = (int) Input::get("torrent");
        $takereason = Input::get("reason");
        $torrent = (int) Input::get("torrent");

        if (Users::get("view_torrents") == "no") {
            Redirect::autolink(URLROOT, Lang::T("NO_TORRENT_VIEW"));
        }

        if (($taketorrent != "") && ($takereason != "")) {
            if (!$takereason) {
                Redirect::autolink(URLROOT . "/report/torrent?torrent=$torrent", Lang::T("YOU_MUST_ENTER_A_REASON"));
            }
            
            $res = DB::raw('reports', 'id', [ 'addedby'=>Users::get('id'),'votedfor'=>$torrent,'type'=>'torrent']);
            if ($res->rowCount() == 0) {
                DB::insert('reports', ['addedby'=>Users::get('id'),'votedfor'=>$taketorrent,'votedfor_xtra'=>0,'type'=>'torrent','reason'=>$takereason]);
                Redirect::autolink(URLROOT . "/torrent?id=$torrent", "Torrent with id: $taketorrent, Reason for report: " . htmlspecialchars($takereason) . "<p>Successfully Reported</p>");
            } else {
                Redirect::autolink(URLROOT . "/torrent?id=$torrent", Lang::T("YOU_HAVE_ALREADY_REPORTED") . " torrent $taketorrent");
            }
        }

        if ($torrent != "") {
            $res = DB::raw('torrents', 'name', ['id' => $torrent]);
            if ($res->rowCount() == 0) {
                Redirect::autolink(URLROOT . "/torrent?id=$torrent", 'Invalid TorrentID');
            }
            $arr = $res->fetch(PDO::FETCH_LAZY);
            
            $data = [
                'title' => 'Report',
                'name' => $arr['name'],
                'torrent' => $torrent,
            ];
            View::render('report/torrent', $data, 'user');
        } else {
            Redirect::autolink(URLROOT . "/torrent?id=$torrent", Lang::T("MISSING_INFO"));
        }
    }

    public function comment()
    {
        $takecomment = (int) Input::get("comment");
        $takereason = Input::get("reason");
        $type = Input::get("type");
        
        if ($type == "req") {
            $whattype = 'req';
        } else {
            $whattype = 'comment';
        }

        // Validate Input/User Checks
        if (($takecomment != "") && ($takereason != "")) {
            if (!$takereason) {
                Redirect::autolink(URLROOT . "/report/comment?comment=$takecomment", Lang::T("YOU_MUST_ENTER_A_REASON"));
            }
            $res = DB::raw('reports', 'id', [ 'addedby'=>Users::get('id'),'votedfor'=>$takecomment,'type'=>$whattype]);
            if ($res->rowCount() == 0) {
                DB::insert('reports', ['addedby'=>Users::get('id'),'votedfor'=>$takecomment,'votedfor_xtra'=>0,'type'=>$whattype,'reason'=>$takereason]);
                Redirect::autolink(URLROOT, "Comment with id: $takecomment, Reason for report: " . htmlspecialchars($takereason) . "<p>Successfully Reported</p>");
            } else {
                Redirect::autolink(URLROOT, Lang::T("YOU_HAVE_ALREADY_REPORTED") . " torrent $takecomment");
            }
        }

        // No Input So Show Form
        if ($takecomment != "") {
            $res = DB::run("SELECT id, text FROM comments WHERE id=?", [$takecomment]);
            if ($res->rowCount() == 0) {
                Redirect::autolink(URLROOT, "Invalid Comment");
            }
            $arr = $res->fetch(PDO::FETCH_LAZY);

            $data = [
                'type' => $type,
                'title' => 'Report',
                'text' => $arr['text'],
                'comment' => $takecomment,
            ];
            View::render('report/comment', $data, 'user');
        } else {
            Redirect::autolink(URLROOT, Lang::T("MISSING_INFO"));
        }
    }

    public function forum()
    {
        $takeforumid = (int) Input::get("forumid");
        $takeforumpost = (int) Input::get("forumpost");
        $takereason = Input::get("reason");
        $forumid = (int) Input::get("forumid");
        $forumpost = (int) Input::get("forumpost");

        if (Users::get("forumbanned") == "yes" || Users::get("view_forum") == "no") {
            Redirect::autolink(URLROOT, Lang::T("FORUM_BANNED"));
        }
        
        if (($takeforumid != "") && ($takereason != "")) {
            if (!$takereason) {
                Redirect::autolink(URLROOT, Lang::T("YOU_MUST_ENTER_A_REASON"));
            }
            $res = DB::raw('reports', 'id', [ 'addedby'=>Users::get('id'),'votedfor'=>$takeforumid,'votedfor_xtra'=>$takeforumpost,'type'=>'forum']);
            if ($res->rowCount() == 0) {
                DB::insert('reports', ['addedby'=>Users::get('id'),'votedfor'=>$takeforumid,'votedfor_xtra'=>$takeforumpost,'type'=>'forum','reason'=>$takereason]);
                Redirect::autolink(URLROOT, "User: ".Users::get('username').", Reason: " . htmlspecialchars($takereason) . "<p>Successfully Reported</p>");
            } else {
                Redirect::autolink(URLROOT, Lang::T("YOU_HAVE_ALREADY_REPORTED") . " post $takeforumid");
            }
        }

        if (($forumid != "") && ($forumpost != "")) {
            $res = DB::raw('forum_topics', 'subject', ['id' => $forumid]);
            if ($res->rowCount() == 0) {
                Redirect::autolink(URLROOT, "Invalid Forum ID");
            }
            $arr = $res->fetch(PDO::FETCH_LAZY);
            
            $data = [
                'title' => 'Report',
                'subject' => $arr['subject'],
                'forumpost' => $forumpost,
                'forumid' => $forumid,
            ];
            View::render('report/forum', $data, 'user');
        }

        Redirect::autolink(URLROOT, Lang::T("MISSING_INFO"));
    }

}