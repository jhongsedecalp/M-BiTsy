<?php

class Validate
{

    public static function isEmpty($data)
    {
        if (is_array($data)) {
            return empty($data);
        } elseif ($data == "") {
            return true;
        } else {
            return false;
        }
    }

    public static function Email($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }
        return true;
    }

    public static function Filename($name)
    {
        return preg_match('/^[^\0-\x1f:\\\\\/?*\xff#<>|]+$/si', $name);
    }

    public static function Id($id)
    {
        return is_numeric($id) && ($id > 0) && (floor($id) == $id);
    }

    public static function username($username) {
		$allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-";
		for ($i = 0; $i < strlen($username); ++$i)
			if (strpos($allowedchars, $username[$i]) === false)
			return false;
		return true;
    }

    public static function Int($id)
    {
        return is_numeric($id) && (floor($id) == $id);
    }

    public static function cleanstr($s)
    {
        if (function_exists("filter_var")) {
            return filter_var($s, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        } else {
            return preg_replace('/[\x00-\x1F]/', "", $s);
        }
    }

}
