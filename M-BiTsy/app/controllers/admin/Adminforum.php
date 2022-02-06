<?php
class Adminforum
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        $groupsres = DB::run("SELECT group_id, level FROM `groups` ORDER BY group_id ASC");

        $data = [
            'title' => Lang::T("FORUM_MANAGEMENT"),
            'groupsres' => $groupsres,
        ];
        View::render('forum/index', $data, 'admin');
    }

    public function addcat()
    {
        $error_ac = "";
        $new_forumcat_name = $_POST["new_forumcat_name"];
        $new_forumcat_sort = $_POST["new_forumcat_sort"];

        if ($new_forumcat_name == "") {
            $error_ac .= "<li>" . Lang::T("CP_FORUM_CAT_NAME_WAS_EMPTY") . "</li>\n";
        }
        if ($new_forumcat_sort == "") {
            $error_ac .= "<li>" . Lang::T("CP_FORUM_CAT_SORT_WAS_EMPTY") . "</li>\n";
        }

        if ($error_ac == "") {
            $res = DB::insert('forumcats', ['name'=>$new_forumcat_name, 'sort'=>intval($new_forumcat_sort)]);
            if ($res) {
                Redirect::autolink(URLROOT . "/adminforum", "Thank you, new forum cat added to db ...");
            } else {
                Redirect::autolink(URLROOT . "/adminforum", Lang::T("CP_COULD_NOT_SAVE_TO_DB"));
            }
        } else {
            Redirect::autolink(URLROOT . "/adminforum", $error_ac);
        }
    }

    public function delcat()
    {
        $id = (int) $_GET["id"];

        $v = DB::select('forumcats', '*', ['id'=>$id]);
        if (!$v) {
            Redirect::autolink(URLROOT . "/adminforum", Lang::T("FORUM_INVALID_CAT"));
        }

        $data = [
            'title' => Lang::T("FORUM_MANAGEMENT"),
            'id' => $id,
            'catid' => $v['id'],
            'name' => $v['name'],
        ];
        View::render('forum/deletecat', $data, 'admin');
    }

    public function deleteforumcat()
    {
        DB::delete('forumcats', ['id'=>$_POST['id']]);

        $res = DB::raw('forum_forums', 'id', ['category' => $_POST['id']]);
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $res2 = DB::raw('forum_topics', 'id', ['forumid'=>$row['id']]);
            while ($arr = $res2->fetch(PDO::FETCH_ASSOC)) {
                DB::delete('forum_posts', ['topicid'=>$arr['id']]);
                DB::delete('forum_readposts', ['topicid'=>$arr['id']]);
            }
            DB::delete('forum_topics', ['forumid'=>$row['id']]);
            DB::delete('forum_forums', ['id'=>$row['id']]);
        }
        Redirect::autolink(URLROOT . "/adminforum", Lang::T("CP_FORUM_CAT_DELETED"));
    }

    public function editcat()
    {
        $id = (int) $_GET["id"];

        $r = DB::raw('forumcats', '*', ['id'=>$id])->fetch();
        if (!$r) {
            Redirect::autolink(URLROOT . "/adminforum", Lang::T("FORUM_INVALID_CAT"));
        }

        $data = [
            'title' => Lang::T("FORUM_MANAGEMENT"),
            'id' => $id,
            'sort' => $r['sort'],
            'name' => $r['name'],
        ];
        View::render('forum/editcat', $data, 'admin');
    }

    public function saveeditcat()
    {
        $id = (int) $_POST["id"];
        $changed_sortcat = (int) $_POST["changed_sortcat"];

        DB::update('forumcats', ['sort'=>$changed_sortcat, 'name'=>$_POST["changed_forumcat"]], ['id' => $id]);
        Redirect::autolink(URLROOT . "/adminforum", "<center><b>" . Lang::T("CP_UPDATE_COMPLETED") . "</b></center>");
    }

    public function addforum()
    {
        $error_ac = "";
        $new_forum_name = $_POST["new_forum_name"];
        $new_desc = $_POST["new_desc"];
        $new_forum_sort = (int) $_POST["new_forum_sort"];
        $new_forum_cat = (int) $_POST["new_forum_cat"];
        $minclassread = (int) $_POST["minclassread"];
        $minclasswrite = (int) $_POST["minclasswrite"];
        $guest_read = $_POST["guest_read"];

        $new_forum_forum = (int) $_POST["new_forum_forum"] ?? 0; // sub forum mod

        if ($new_forum_name == "") {
            $error_ac .= "<li>" . Lang::T("CP_FORUM_NAME_WAS_EMPTY") . "</li>\n";
        }
        if ($new_desc == "") {
            $error_ac .= "<li>" . Lang::T("CP_FORUM_DESC_WAS_EMPTY") . "</li>\n";
        }
        if ($new_forum_sort == "") {
            $error_ac .= "<li>" . Lang::T("CP_FORUM_SORT_ORDER_WAS_EMPTY") . "</li>\n";
        }
        if ($new_forum_cat == "") {
            $error_ac .= "<li>" . Lang::T("CP_FORUM_CATAGORY_WAS_EMPTY") . "</li>\n";
        }
        if ($error_ac == "") {
            $res = DB::insert('forum_forums', ['name'=>$new_forum_name, 'description'=>$new_desc, 'sort'=>$new_forum_sort, 'category'=>$new_forum_cat, 'minclassread'=> $minclassread, 'minclasswrite'=>$minclasswrite, 'guest_read'=> $guest_read, 'sub'=>$new_forum_forum]);
            if ($res) {
                Redirect::autolink(URLROOT . "/adminforum", Lang::T("CP_FORUM_NEW_ADDED_TO_DB"));
            } else {
                Redirect::autolink(URLROOT . "/adminforum", Lang::T("CP_COULD_NOT_SAVE_TO_DB"));
            }
        } else {
            Redirect::autolink(URLROOT . "/adminforum", $error_ac);
        }
    }

    public function deleteforum()
    {
        $id = (int) $_GET["id"];

        $v = DB::raw('forum_forums', '*', ['id'=>$id])->fetch();
        if (!$v) {
            Redirect::autolink(URLROOT . "/adminforum", Lang::T("FORUM_INVALID"));
        }

        $data = [
            'title' => Lang::T("FORUM_MANAGEMENT"),
            'id' => $id,
            'catid' => $v['sort'],
            'name' => $v['name'],
        ];
        View::render('forum/deleteforum', $data, 'admin');
    }

    public function deleteforumok()
    {
        DB::delete('forum_forums', ['id'=>$_POST['id']]);
        DB::delete('forum_topics', ['forumid'=>$_POST['id']]);
        DB::delete('forum_posts', ['topicid'=>$_POST['id']]);
        DB::delete('forum_readposts', ['topicid'=>$_POST['id']]);
        Redirect::autolink(URLROOT . "/adminforum", Lang::T("CP_FORUM_DELETED"));
    }

    public function editforum()
    {
        $id = (int) $_GET["id"];

        $r = DB::raw('forum_forums', '*', ['id'=>$id])->fetch();
        if (!$r) {
            Redirect::autolink(URLROOT . "/adminforum", Lang::T("FORUM_INVALID"));
        }

        $query = DB::raw('forumcats', '*', '', 'ORDER BY sort, name');

        $data = [
            'title' => Lang::T("FORUM_MANAGEMENT"),
            'id' => $id,
            'sort' => $r['sort'],
            'name' => $r['name'],
            'sub' => $r['sub'],
            'description' => $r['description'],
            'guest_read' => $r['guest_read'],
            'query' => $query,
        ];
        View::render('forum/editforum', $data, 'admin');
    }

    public function saveeditforum()
    {
        $id = (int) $_POST["id"];
        $changed_sort = (int) $_POST["changed_sort"];
        $changed_forum = $_POST["changed_forum"];
        $changed_forum_desc = $_POST["changed_forum_desc"];
        $changed_forum_cat = (int) $_POST["changed_forum_cat"];
        $minclasswrite = (int) $_POST["minclasswrite"];
        $minclassread = (int) $_POST["minclassread"];
        $guest_read = $_POST["guest_read"];
        $changed_sub = (int) $_POST["changed_sub"];

        DB::update('forum_forums', ['sort' =>$changed_sort, 'name' =>$changed_forum, 'description' =>$changed_forum_desc, 'category' =>$changed_forum_cat, 'minclassread'=>$minclassread, 'minclasswrite'=>$minclasswrite, 'guest_read'=>$guest_read, 'sub'=>$changed_sub], ['id' => $id]);
        Redirect::autolink(URLROOT . "/adminforum", "<center><b>" . Lang::T("CP_UPDATE_COMPLETED") . "</b></center>");
    }

}