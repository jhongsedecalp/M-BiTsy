<?php
class Bookmark
{

    public function __construct()
    {
        Auth::user(0, 2, true);
    }

    public function index()
    {
        $count = DB::column('bookmarks', 'COUNT(*)', ['userid'=>Users::get("id"),'type'=>'torrent']);
        list($pagerbuttons, $limit) = Pagination::pager(25, $count, URLROOT."/bookmark?");
        $query = Bookmarks::join($limit, Users::get("id"));

        if ($count == 0) {
            Redirect::autolink(URLROOT, "Your Bookmarks list is empty !");
        } else {
            $data = [
                'title' => 'My Bookmarks',
                'count' => $count,
                'pagerbuttons' => $pagerbuttons,
                'res' => $query,
            ];
            View::render('bookmark/index', $data, 'user');
        }

    }

    public function add()
    {
        $target = (int) Input::get("target");
        $type = 'torrent';

        if (!isset($target)) {
            Redirect::autolink(URLROOT, "No target selected...");
        }

        $arr = DB::column('bookmarks', 'COUNT(*)', ['targetid'=>$target,'type'=>$type,'userid'=>Users::get('id')]);
        if ($arr > 0) {
            Redirect::autolink(URLROOT, "Already bookmarked...");
        }

        if ($type === 'torrent') {
            if ((get_row_count("torrents", "WHERE id=$target")) > 0) {
                DB::insert('bookmarks', ['userid'=>Users::get('id'), 'targetid'=>$target, 'type'=>'torrent']);
                Redirect::autolink(URLROOT."/torrent?id=$target", "Torrent was successfully bookmarked.");
            }
        } else {
            // if type forum ???
        }
        Redirect::autolink(URLROOT, "ID not found");
    }

    public function delete()
    {
        $target = (int) Input::get("target");
        $type = 'torrent';

        $arr = DB::column('bookmarks', 'COUNT(*)', ['targetid'=>$target,'type'=>$type,'userid'=>Users::get('id')]);
        if (!$arr) {
            Redirect::autolink(URLROOT, "ID not found in your bookmarks list...");
        }

        DB::delete('bookmarks', ['targetid' =>$target, 'type' => $type, 'userid'=>Users::get('id')]);
        
        if ($type === 'torrent') {
            Redirect::autolink(URLROOT."/profile?id=".Users::get('id')."", "Book Mark Deleted...");
        } else {
            // redirect forum
        }
    }

}