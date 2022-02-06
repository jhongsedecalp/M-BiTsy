<?php
class Countries
{

    public static function echoCountry()
    {
        $countries = "<option value=\"0\">---- " . Lang::T("NONE_SELECTED") . " ----</option>\n";
        $ct_r = DB::raw('countries', 'id,name,domain', '', 'ORDER BY name');
        foreach ($ct_r as $ct_a) {
            $countries .= "<option value=\"$ct_a[id]\">$ct_a[name]</option>\n";
        }
        echo $countries;
    }

    public static function getCountryName($id)
    {
        $res = DB::raw('countries', 'name', ['id'=>$id], 'LIMIT 1');
        if ($res->rowCount() == 1) {
            $arr = $res->fetch();
            $country = "$arr[name]";
        }
        if (!$country) {
            $country = "<b>Unknown</b>";
        }
        return $country;
    }

    public static function pickCountry($country)
    {
        $countries = "<option value='0'>$country</option>\n";
        $ct_r =  DB::raw('countries', 'id, name', '', 'ORDER BY name');
        while ($ct_a = $ct_r->fetch(PDO::FETCH_LAZY)) {
            $countries .= "<option value='$ct_a[id]'" . ($country == $ct_a['id'] ? " selected='selected'" : "") . ">$ct_a[name]</option>\n";
        }
        return $countries;
    }

    public static function showflag($country)
    {
        $cres = DB::raw('countries', 'name,flagpic', ['id'=>$country]);
        if ($carr = $cres->fetch(PDO::FETCH_ASSOC)) {
            return $country = "<img src='" . URLROOT . "/assets/images/languages/$carr[flagpic]' title='" . htmlspecialchars($carr['name']) . "' alt='" . htmlspecialchars($carr['name']) . "' />";
        } else {
            return $country = "<img src='" . URLROOT . "/assets/images/languages/unknown.gif' alt='Unknown' />";
        }
    }

}