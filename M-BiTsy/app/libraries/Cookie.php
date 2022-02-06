<?php
class Cookie
{
    public static function destroyAll()
    {
        setcookie("id", null, time() - 7000000, "/");
        setcookie("password", null, time() - 7000000, "/");
        setcookie("key_token", null, time() - 7000000, "/");
        setcookie("PHPSESSID", null, time() - 7000000, "/");
        $_SESSION = array();
        unset($_SESSION);
        @session_destroy();
    }

    public static function set($name, $value, $expiry)
    {
        if (setcookie($name, $value, time() + $expiry, "/")) {
            return true;
        }
        return false;
    }

    public static function setAll($id, $pass, $token)
    {
        self::set('id', $id, 5485858, "/");
        self::set('password', $pass, 5485858, "/");
        self::set("key_token", $token, 5485858, "/");
    }

    public static function csrf_token()
	{
		// Check if a token is present for the current session
		if (! isset($_COOKIE['csrf_token'])) {
			$token = base64_encode(openssl_random_pseudo_bytes(32));
			self::set("csrf_token", $token, 5485858, "/");
        } else {
            $token = $_COOKIE['csrf_token'];
        }
        return $token;
    }

    public static function csrf_check()
	{
		if (!$_POST["csrf_token"] == $_COOKIE["csrf_token"]) {
            // Reset token
            setcookie("csrf_token", null, time() - 7000000, "/");
            Redirect::autolink(URLROOT . "/logout", Lang::T("CSRF token validation failed"));
            return false;
        } else {
            setcookie("csrf_token", null, time() - 7000000, "/");
            return true;
        } 
    }

	public static function get($name) {
		if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        } else {
            return false;
        }
	}
}