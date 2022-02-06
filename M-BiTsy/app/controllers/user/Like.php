<?php
class Like
{

    public function __construct()
    {
        Auth::user(0, 2);
    }

    // Likes
    public function index()
    {
        $id = (int) Input::get('id');
        $type = Input::get('type');

        if (!Validate::Id($id)) {
            Redirect::autolink(URLROOT, Lang::T("INVALID_ID"));
        }

        if (!$type) {
            Redirect::autolink(URLROOT, "No Type");
        }

        $this->likeswitch($id, $type);
    }

    public function likeswitch($id, $type)
    {
        switch ($type) {
            
            case 'liketorrent':
                DB::insert('likes', ['user'=>Users::get('id'), 'liked'=>$id, 'added'=>TimeDate::get_date_time(), 'type'=>'torrent', 'reaction'=>'like']);
                Redirect::autolink(URLROOT."/torrent?id=$id", "Thanks you for you appreciation.");
                break;

            case 'unliketorrent':
                DB::delete('likes', ['user' =>Users::get('id'), 'liked' => $id, 'type'=>'torrent']);
                Redirect::autolink(URLROOT."/torrent?id=$id", "Unliked.");
                break;

            default:
                Redirect::autolink(URLROOT, "Thanks you for you appreciation.");
                break;
        }
    }

    public function thanks()
    {
        $id = (int) Input::get('id');
        $type = Input::get('type');

        if (!Validate::Id($id)) {
            Redirect::autolink(URLROOT, Lang::T("INVALID_ID"));
        }

        if (!$type) {
            Redirect::autolink(URLROOT, "No ID");
        }

        $this->thankswitch($id, $type);
    }

    public function thankswitch($id, $type)
    {
        switch ($type) {

            case 'torrent':
                DB::insert('thanks', ['user'=>Users::get('id'), 'thanked'=>$id, 'added'=>TimeDate::get_date_time(), 'type'=>'torrent']);
                Redirect::autolink(URLROOT."/torrent?id=$id", "Thanks you for you appreciation.");
                break;

            case 'thanksforum':
                DB::insert('thanks', ['user'=>Users::get('id'), 'thanked'=>$id, 'added'=>TimeDate::get_date_time(), 'type'=>'forum']);
                Redirect::autolink(URLROOT."/topic?topicid=$id", "Thanks you for you appreciation.");
                break;

            default:
                Redirect::autolink(URLROOT, "Thanks you for you appreciation.");
                break;
        }
    }

}