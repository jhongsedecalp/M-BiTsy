<?php
class Admincategorie
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        $sql = DB::all('categories', '*', '', 'ORDER BY parent_cat ASC, sort_index ASC');
        
        $data = [
            'title' => Lang::T("TORRENT_CATEGORIES"),
            'sql' => $sql,
        ];
        View::render('cats/index', $data, 'admin');
    }

    public function edit()
    {
        $id = (int) $_GET["id"];

        if (!Validate::Id($id)) {
            Redirect::autolink(URLROOT . "/admincategorie", Lang::T("INVALID_ID"));
        }

        $res = DB::all('categories', '*', ['id'=>$id]);
        if (count($res) != 1) {
            Redirect::autolink(URLROOT . "/admincategorie", "No category with ID $id.");
        }

        if ($_GET["save"] == '1') {
            $parent_cat = $_POST['parent_cat'];
            if ($parent_cat == "") {
                Redirect::autolink(URLROOT . "/admincategorie/edit", "Parent Cat cannot be empty!");
            }

            $name = $_POST['name'];
            if ($name == "") {
                Redirect::autolink(URLROOT . "/admincategorie/edit", "Sub cat cannot be empty!");
            }

            $sort_index = $_POST['sort_index'];
            $image = $_POST['image'];

            DB::update('categories', ['parent_cat' => $parent_cat, 'name' => $name, 'sort_index' => $sort_index, 'image' => $image], ['id' => $id]);
            Redirect::autolink(URLROOT . "/admincategorie", Lang::T("SUCCESS"), "category was edited successfully!");
        } else {
            $data = [
                'title' => Lang::T("TORRENT_CATEGORIES"),
                'res' => $res,
                'id' => $id,
            ];
            View::render('cats/edit', $data, 'admin');
        }
    }

    public function delete()
    {
        $id = (int) $_GET["id"];
        if ($_GET["sure"] == '1') {
            if (!Validate::Id($id)) {
                Redirect::autolink(URLROOT . "/admincategorie", Lang::T("CP_NEWS_INVAILD_ITEM_ID = $id"));
            }

            $newcatid = (int) $_POST["newcat"];
            DB::run("UPDATE torrents SET category=$newcatid WHERE category=$id"); //move torrents to a new cat
            DB::delete('categories', ['id'=>$id]);
            Redirect::autolink(URLROOT . "/admincategorie", Lang::T("Category Deleted OK."));
        } else {
            $data = [
                'title' => Lang::T("TORRENT_CATEGORIES"),
                'id' => $id,
            ];
            View::render('cats/delete', $data, 'admin');
        }
    }

    public function takeadd()
    {
        $name = $_POST['name'];
        if ($name == "") {
            Redirect::autolink(URLROOT . "/admincategorie/add", "Sub Cat cannot be empty!");
        }

        $parent_cat = $_POST['parent_cat'];
        if ($parent_cat == "") {
            Redirect::autolink(URLROOT . "/admincategorie/add", "Parent Cat cannot be empty!");
        }
        $sort_index = $_POST['sort_index'];
        $image = $_POST['image'];

        $ins = DB::insert('categories', ['name'=>$name, 'parent_cat'=>$parent_cat, 'sort_index'=>$sort_index, 'image'=>$image]);
        if ($ins) {
            Redirect::autolink(URLROOT . "/admincategorie", Lang::T("Category was added successfully."));
        } else {
            Redirect::autolink(URLROOT . "/admincategorie/add", "Unable to add category");
        }

    }

    public function add()
    {
        $data = [
            'title' => Lang::T("TORRENT_CATEGORIES"),
        ];
        View::render('cats/add', $data, 'admin');
    }

}