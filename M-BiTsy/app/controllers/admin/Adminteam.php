<?php
class Adminteam
{

    public function __construct()
    {
        Auth::user(_MODERATOR, 2);
    }

    public function index()
    {
        $sql = DB::raw('teams', '*', '');

        $data = [
            'title' => Lang::T("TEAMS_MANAGEMENT"),
            'sql' => $sql
        ];
        View::render('team/index', $data, 'admin');
    }

    public function add()
    {
        $team_name = $_POST['team_name'];
        $team_image = $_POST['team_image'];
        $team_description = $_POST['team_description'];
        $teamownername = $_POST['team_owner'];
        $add = $_POST['add'];

        if ($add == 'true') {
            if (!$team_name || !$teamownername || !$team_description) {
                Redirect::autolink(URLROOT . '/adminteam', Lang::T("One or more fields left empty."));
            }

            $ar = DB::select('users', 'id', ['username' =>$teamownername]);
            $team_owner = $ar["id"];
            if (!$team_owner) {
                Redirect::autolink(URLROOT . '/adminteam', Lang::T("This user does not exist"));
            }

            DB::insert('teams', ['name'=>$team_name, 'owner'=>$team_owner, 'info'=>$team_description, 'image'=>$team_image, 'added'=>TimeDate::get_date_time()]);
            $tid = DB::lastInsertId();
            DB::update(' users', ['team' =>$tid], ['id' => $team_owner]);
        }
        Redirect::autolink(URLROOT . '/adminteam', Lang::T("Team Added"));
    }

    public function delete()
    {
        $sure = $_GET['sure'];
        $del = $_GET['del'];
        $team = htmlspecialchars($_GET['team']);

        if ($sure == "yes") {
            DB::run("UPDATE users SET team=? WHERE team=?", ['0', $del]);
            DB::delete('teams', ['id'=>$del], 1);
            Logs::write(Users::get('username') . " has deleted team id:$del");
            Redirect::autolink(URLROOT . '/adminteam', Lang::T("Team Successfully Deleted!"));
        }

        if ($del > 0) {
            Redirect::autolink(URLROOT . '/adminteas', Lang::T("You and in the truth wish to delete team? ($team) ( <b><a href='".URLROOT."/adminteam/delete?del=$del&amp;team=$team&amp;sure=yes'>Yes!</a></b> / <b><a href='".URLROOT."/adminteam'>No!</a></b> )"));
        }
    }

    public function edit()
    {
        $edited = (int) Input::get('edited');
        $id = (int) $_POST['id'];
        $team_name = $_POST['team_name'];
        $team_info = $_POST['team_info'];
        $team_image = $_POST['team_image'];
        $teamownername = $_POST['team_owner'];
        $editid = $_GET['editid'];
        $name = $_GET['name'];
        $image = $_GET['image'];
        $owner = $_GET['owner'];
        $info = $_GET['info'];

        // Post/Get
        if ($edited == 1) {
            if (!$team_name || !$teamownername || !$team_info) {
                Redirect::autolink(URLROOT . '/adminteam', 'One or more fields left empty.');
            }
 
            $aa = DB::raw('users', 'class, id', ['username'=>$teamownername]);
            $ar = $aa->fetch(PDO::FETCH_ASSOC);
            $team_owner = $ar["id"];

            $sql = DB::update(' teams', ['name' =>$team_name, 'info' =>$team_info,'owner' =>$team_owner, 'image' =>$team_image], ['id' => $id]);
            DB::update(' users', ['team' =>$id], ['id' => $team_owner]);
            if ($sql) {
                $mss = "<b>Successfully Edited</b>[<a href='".URLROOT."/adminteam'>Back</a>]";
                Logs::write(Users::get('username') . " has edited team ($team_name)");
                Redirect::autolink(URLROOT . '/adminteam', $mss);
            }
        }

        if ($editid > 0) {
            $data = [
                'title' => Lang::T("Team Edit"),
                'editid' => $editid,
                'name' => $name,
                'image' => $image,
                'owner' => $owner,
                'info' => $info,
            ];
            View::render('team/edit', $data, 'admin');
        }
	}

    public function members()
    {
        $teamid = $_GET['teamid'];

        $sql = DB::raw('users', 'id,username,uploaded,downloaded', ['team'=>$teamid]);
        
        $data = [
            'title' => Lang::T("TEAMS_MANAGEMENT"),
            'sql' => $sql
        ];
        View::render('team/members', $data, 'admin');
	}

}