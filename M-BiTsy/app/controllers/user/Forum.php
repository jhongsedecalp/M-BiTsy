<?php
class Forum
{

    public function __construct()
    {
        Auth::user(0, 1);
    }

    /**
     * Lets show the validation
     */
    private function validForumUser()
    {
        if (!Config::get('FORUMS')) {
            Redirect::autolink(URLROOT, Lang::T("FORUM_AVAILABLE"));
        }
        if (!Config::get('FORUMS_GUESTREAD') && !$_SESSION['loggedin']) {
            Redirect::autolink(URLROOT, Lang::T("NO_PERMISSION"));
        }
        if (Users::get("forumbanned") == "yes" || Users::get("view_forum") == "no") {
            Redirect::autolink(URLROOT, Lang::T("FORUM_BANNED"));
        }
    }

    /**
     * View Forum Index.
     */
    public function index()
    {
        $this->validForumUser();

        if ($_GET["do"] == 'catchup') {
            catch_up();
        }

        // Get Data
        $forums_res = Forums::getIndex();
        if ($forums_res->rowCount() == 0) {
            Redirect::autolink(URLROOT, Lang::T("FORUM_AVAILABLE"));
        }

        // Get Subs
        $subforums_res = Forums::getsub();

        // topic count and post counts
        $postcount = number_format(get_row_count("forum_posts"));
        $topiccount = number_format(get_row_count("forum_topics"));

        $data = [
            'title' => Lang::T("Forums"),
            'mainquery' => $forums_res,
            'mainsub' => $subforums_res,
            'postcount' => $postcount,
            'topiccount' => $topiccount,
        ];
        View::render('forum/index', $data, 'user');
    }

    /**
     * Search Forum.
     */
    public function search()
    {
        $this->validForumUser();

        $data = [
            'title' => Lang::T("Search Forums"),
        ];
        View::render('forum/search', $data, 'user');
    }

    /**
     * Search Results.
     */
    public function result()
    {
        $this->validForumUser();

        $keywords = Input::get("keywords");
        $type = $_GET['type'];

        if (!$keywords == '') {
            $res = Forums::search($keywords, $_GET['type']);

            if ($res['count'] > 0) {
                $data = [
                    'res' => $res['res'],
                    'keywords' => $keywords,
                    'title' => 'Forums',
                    'count' => $res['count'],
                    'pagerbuttons' => $res['pager'],
                ];
                View::render('forum/result', $data, 'user');
            } else {
                Redirect::autolink(URLROOT . '/forum/search', Lang::T("NOTHING_FOUND"));
            }

        } else {
            Redirect::autolink(URLROOT . '/forum/search', Lang::T("YOU_DID_NOT_ENTER_ANYTHING"));
        }
    }

    /**
     * View Unread Topics.
     */
    public function viewunread()
    {
        $this->validForumUser();

        $res = DB::run("SELECT id, forumid, subject, lastpost FROM forum_topics ORDER BY lastpost DESC");
        
		$data = [
            'res' => $res,
            'n' => 0,
            'title' => Lang::T("Forums"),
        ];
        View::render('forum/viewunread', $data, 'user');
    }

    /**
     * View Forum.
     */
    public function view()
    {
        $this->validForumUser();

        $forumid = Input::get("forumid");

        if (!Validate::Id($forumid)) {
            Redirect::autolink(URLROOT . "/forum", Lang::T("FORUMS_DENIED"));
        }

        // Get forum name 
        $arr = DB::select('forum_forums', 'name, minclassread, guest_read', ['id'=>$forumid]);
        $forumname = $arr["name"];
        if (!$forumname || Users::get('class') < $arr["minclassread"] && $arr["guest_read"] == "no") {
            Redirect::autolink(URLROOT . "/forum", Lang::T("FORUMS_NOT_PERMIT"));
        }

        // Pagination
        $count = get_row_count("forum_topics", "WHERE forumid=$forumid");
        list($pagerbuttons, $limit) = Pagination::pager(25, $count, URLROOT . "/forum/view&forumid=$forumid&");
        $topicsres = DB::all('forum_topics', '*', ['forumid'=>$forumid], 'ORDER BY sticky, lastpost', "DESC $limit");
        
        $data = [
            'title' => Lang::T("Forums"),
            'topicsres' => $topicsres,
            'forumname' => $forumname,
            'forumid' => $forumid,
            'pagerbuttons' => $pagerbuttons,
        ];
        View::render('forum/viewforum', $data, 'user');
    }

}