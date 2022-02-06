<?php
class Request
{
    public function __construct()
    {
        Auth::user(0, 2);
    }

    public function checks()
    {
        if (Users::get("view_torrents") == "no") {
            Redirect::autolink(URLROOT, Lang::T("NO_PERMISSION_TO_VIEW_AREA"));
        }
        if (!Config::get('REQUESTSON')) {
            Redirect::autolink(URLROOT, Lang::T("REQ_OFF"));
        }
    }

    public function index()
    {
        $this->checks();

        $count = DB::run("SELECT count(requests.id) FROM requests inner join categories on requests.cat = categories.id inner join users on requests.userid = users.id")->fetchColumn();;
        list($pagerbuttons, $limit) = Pagination::pager(30, $count, URLROOT . "/request?" . "category=" . $_GET["category"] . "&sort=" . $_GET["sort"] . "&");
        $res = Requests::join();
        
        $data = [
            'title' => Lang::T('REQUESTS'),
            'pagerbuttons' => $pagerbuttons,
            'num' => $count,
            'res' => $res,
        ];
        View::render('request/index', $data, 'user');
    }

    public function edit()
    {
        $this->checks();
        
        $id = (int) Input::get("id");
        if (!Validate::Id($id)) {
            Redirect::autolink(URLROOT . "/request", Lang::T("CP_INVALID_ID"));
        }

        $descr = Input::get("desc");
        $cat = Input::get("cat");
        $filled = Input::get("filled");
        $request = Input::get("request");
        $filledby = Input::get("filledby");

        if (Input::exist()) {
            if (!$filled) {
                DB::update('requests', ['cat' =>$cat, 'request' =>$request,'descr' =>$descr, 'filledby' =>$filledby, 'filled' =>$filled], ['id' => $id]);
            } else {
                DB::update('requests', ['cat' =>$cat, 'request' =>$request,'descr' =>$descr, 'filledby' =>$filledby, 'filled' =>$filled], ['id' => $id]);
            }
            Redirect::to(URLROOT . "/request/reqdetails?id=$id");
        }
        
        $res = DB::raw('requests', '*', ['id' => $id]);

        $data = [
            'title' => Lang::T('REQUESTS'),
            'res' => $res,
        ];
        View::render('request/edit', $data, 'user');
    }

    public function delete()
    {
        $this->checks();
        $delreq = $_POST['delreq'];

        if ((Users::get('class')) > _UPLOADER) {
            if (!$delreq) {
                Redirect::autolink(URLROOT . "/request", Lang::T("NOTHING_SELECTED"));
            }
            $ids = implode(", ", $_POST['delreq']);
            DB::deleteByIds('requests', 'id', $ids);
            DB::deleteByIds('addedrequests', 'requestid', $ids);
            Redirect::autolink(URLROOT . "/request", Lang::T("_SUCCESS_DEL_"));
        } else {
            foreach ($_POST['delreq'] as $del_req) {
                $query = DB::raw('requests', '*', ['userid'=>Users::get('id'),'id' => $del_req]);
                $num = $query->rowCount();
                if ($num > 0) {
                    DB::deleteByIds('requests', 'id', $del_req);
                    DB::deleteByIds('addedrequests', 'requestid', $del_req);
                    Redirect::autolink(URLROOT . "/request", "Request ID $del_req Deleted", URLROOT . "/request");
                } else {
                    Redirect::autolink(URLROOT . "/request", "No Permission to delete Request ID $del_req");
                }
            }
        }
    }

    public function makereq()
    {
        $data = [
            'title' => Lang::T('REQUESTS'),
        ];
        View::render('request/makereq', $data, 'user');
    }

    public function confirmreq()
    {
        if (Users::get('class') < _MODERATOR) {
            Redirect::autolink(URLROOT . "/request/makereq", "Only Moderators can request - For show only");
        }

        $requesttitle = Input::get("requesttitle");
        if (!$requesttitle) {
            Redirect::autolink(URLROOT . "/request/makereq", "You must enter a request!");
        }

        $cat = Input::get("cat");
        if ($cat == 0) {
            Redirect::autolink(URLROOT . "/request/makereq", "Category cannot be empty!");
        }

        $descr = Input::get("descr");
        DB::insert('requests', ['hits'=>1, 'userid'=>Users::get("id") , 'cat'=>$cat, 'request'=>$requesttitle, 'descr'=>$descr, 'added'=>TimeDate::get_date_time()]);
        $id = DB::lastInsertId();

        DB::insert('addedrequests', ['requestid'=>$id, 'userid'=>Users::get('id')]);
        
        $msg = "".Users::get('username')." has made a request for [url=" . URLROOT . "/request/reqdetails?id=" . $id . "]" . $requesttitle . "[/url]";
        DB::insert('shoutbox', ['user'=>'System', 'message'=>$msg, 'date'=>TimeDate::get_date_time(), 'userid'=>0]);
        Logs::write("$requesttitle was added to the Request section");
        Redirect::to(URLROOT . "/request");
    }

    public function reqdetails()
    {
        $id = (int) Input::get("id");

        $res = DB::raw('requests', '*', ['id'=>$id]);
        if ($res->rowCount() != 1) {
            Redirect::autolink(URLROOT . "/request", "That request id doesn't exist.");
        }

        $commcount = DB::column('comments', 'COUNT(*)', ['req'=>$id]);
        if ($commcount) {
            $commquery = "SELECT comments.id, text, user, comments.added, editedby, editedat, avatar, warned, username, title, class, donated FROM comments LEFT JOIN users ON comments.user = users.id WHERE req = $id ORDER BY comments.id";
            $commres = DB::run($commquery);
        } else {
            unset($commres);
        }

        $data = [
            'title' => Lang::T('REQUESTS'),
            'id' => $id,
            'res' => $res,
            'commcount' => $commcount,
            'commres' => $commres,
        ];
        View::render('request/details', $data, 'user');
    }

    public function reqfilled()
    {
        $filledurl = Input::get("filledurl");
        $requestid = (int) Input::get("requestid");
        
        $res = DB::run("SELECT users.username, requests.userid, requests.request FROM requests inner join users on requests.userid = users.id where requests.id = $requestid");
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        $arr2 = DB::select('users', 'username', ['id'=>Users::get('id')]);
        
        $msg = "Your request $requestid ";
        $msg2 = "Your Request Filled";
        DB::update('requests', ['filledby' =>Users::get('id'), 'filled' =>$filledurl], ['id' => $requestid]);
        DB::insert('messages', ['poster'=>0, 'sender'=>0, 'sender'=>$arr['userid'], 'added'=>TimeDate::get_date_time(), 'subject'=>$msg2, 'msg'=>$msg]);
        Redirect::autolink(URLROOT . "/request", "Request $requestid was successfully filled with <a href=$filledurl>$filledurl</a>.  User <a href=" . URLROOT . "/profile?id=$arr[userid]><b>$arr[username]</b></a> automatically PMd.  <br>Filled that accidently? No worries, <a href=" . URLROOT . "/request/reqreset?requestid=$requestid>CLICK HERE</a> to mark the request as unfilled.  Do <b>NOT</b> follow this link unless you are sure there is a problem.");
    }

    public function votesview()
    {
        $requestid = (int) Input::get('requestid');
        $res = DB::run("select users.id as userid,users.username, users.downloaded,users.uploaded, requests.id as requestid, requests.request from addedrequests inner join users on addedrequests.userid = users.id inner join requests on addedrequests.requestid = requests.id WHERE addedrequests.requestid =$requestid");
        
        if (!$res->rowCount() == 0) {
            $data = [
                'title' => Lang::T('REQUESTS'),
                'requestid' => $requestid,
                'res' => $res,
            ];
            View::render('request/voteview', $data, 'user');
        } else {
            Redirect::autolink(URLROOT . "/request", Lang::T('No Votes Yet'));
        }
    }

    public function addvote()
    {
        $requestid = (int) Input::get("id");
        $userid = (int) Users::get("id");

        $voted = DB::select('addedrequests', '*', ['requestid'=>$requestid,'userid'=>$userid]);
        if ($voted) {
            Redirect::autolink(URLROOT . "/request", 'Youve already voted for this request only 1 vote for each request is allowed');
        } else {
            DB::update('requests', ['hits'=>'hits + 1'], ['id'=>$requestid]);
            //DB::run("INSERT INTO addedrequests VALUES(0, $requestid, $userid)");
            Redirect::autolink(URLROOT . "/request", "<p>Successfully voted for request $requestid</p><p>Back to <a href=" . URLROOT . "/request><b>requests</b></a></p>");
        }
    }

    public function reqreset()
    {
        $requestid = (int) Input::get("requestid");

        $arr = DB::select('requests', 'userid, filledby', ['id'=>$requestid]);
        
        if ((Users::get('id') == $arr['userid']) || (Users::get("class") >= 4) || (Users::get('id') == $arr['filledby'])) {
            DB::update('requests', ['filledby' =>0, 'filled' =>''], ['id' => $requestid]);
            Redirect::autolink(URLROOT . "/request", "Request $requestid successfully reset.");
        } else {
            Redirect::autolink(URLROOT . "/request", "Sorry, cannot reset a request when you are not the owner");
        }
    }

}