<?php

class Lang
{
    public static function T($s)
    {
        global $LANG;
        if ($ret = (isset($LANG[$s]) ? $LANG[$s] : null)) {
            return $ret;
        }
        if ($ret = (isset($LANG["{$s}[0]"]) ? $LANG["{$s}[0]"] : null)) {
            return $ret;
        }
        return $s;
    }

    public static function N($s, $num)
    {
        global $LANG;
        $num = (int) $num;
        $plural = str_replace("n", $num, $LANG["PLURAL_FORMS"]);
        $i = eval("return intval($plural);");
        if ($ret = (isset($LANG["{$s}[$i]"]) ? $LANG["{$s}[$i]"] : null)) {
            return $ret;
        }
        return $s;
    }

    public static function langlist()
    {
        $ret = array();
        $stmt = DB::raw('torrentlang', 'id, name, image', '', 'ORDER BY sort_index, id');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ret[] = $row;
        }
        return $ret;
    }

    public static function select()
    {
        $language = "<select name=\"lang\">\n<option value=\"0\">" . Lang::T("UNKNOWN_NA") . "</option>\n";
        $langs = self::langlist();
        foreach ($langs as $row) {
            $language .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";
        }
        $language .= "</select>\n";
        return $language;
    }

    public static function letters()
    {
        print("<center><p><br />\n");
        // Print Each Letter
        for ($i = 97; $i < 123; ++$i) {
            $l = chr($i);
            $L = chr($i - 32);
            if ($l == $_GET['letter']) {
                print("<b><font color=#ff0000>$L</font></b>\n");
            } else {
                print("<a href=?letter=$l><b>$L</b></a>\n");
            }
        }
        print("</p><br /></center>\n");
    }
}
