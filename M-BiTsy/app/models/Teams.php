<?php
class Teams
{

    public static function getTeams()
    {
        $sql = DB::run("SELECT teams.id, teams.name, teams.image, teams.info, teams.owner, teams.added, users.username, (SELECT GROUP_CONCAT(id, ' ', username) FROM users WHERE FIND_IN_SET(users.team, teams.id) AND users.enabled = 'yes' AND users.status = 'confirmed') AS members FROM teams LEFT JOIN users ON teams.owner = users.id WHERE users.enabled = 'yes' AND users.status = 'confirmed'");
        return $sql;
    }

    
    public static function dropDownTeams($team)
    {
        $teams = "<option value='0'>--- " . Lang::T("NONE_SELECTED") . " ----</option>\n";
        $sashok = DB::raw('teams', 'id, name', '', 'ORDER BY name');
        while ($sasha = $sashok->fetch(PDO::FETCH_LAZY)) {
            $teams .= "<option value='$sasha[id]'" . ($team == $sasha['id'] ? " selected='selected'" : "") . ">$sasha[name]</option>\n";
        }
        return $teams;
    }
}
