<?php
/**
 * Session helper class
 *
 */

class Session {

	public static function init() {
		session_start();
		$currentCookieParams = session_get_cookie_params();
		$sidvalue = session_id();
		setcookie(
			'PHPSESSID',//name
			$sidvalue,//value
			0,//expires at end of session
			$currentCookieParams['path'],//path
			$currentCookieParams['domain'],//domain
			(HTTPS === "FALSE") ? false : true,//secure
			true //HttpOnly
		);
	}

	/**
	 * Set a session variable
	 */
	public static function set($key, $value) {
		$_SESSION['cstm'][$key] = $value;
	}

	/**
	 * Get a session variable
	 */
	public static function get($key) {
		if (isset($_SESSION['cstm']) && array_key_exists($key, $_SESSION['cstm']))
			return $_SESSION['cstm'][$key];
		return null;
	}

	/**
	 * Get a session variable and unset the variable
	 */
	public static function pull($key) {
		if (isset($_SESSION['cstm']) && array_key_exists($key, $_SESSION['cstm'])) {
			$var = $_SESSION['cstm'][$key];
			unset($_SESSION['cstm'][$key]);
			return $var;
		}
		return null;
	}

	/**
	 * Remove a session variable
	 */
	public static function del($key) {
		if (isset($_SESSION['cstm']) && array_key_exists($key, $_SESSION['cstm'])) {
			unset($_SESSION['cstm'][$key]);
			return true;
		}
		return false;
	}

	/**
	 * Returns all set session variables
	 */
	public static function all() {
		return $_SESSION['cstm'];
	}


	/**
	 * Clear the current session
	 *
	 */
	public static function clear() {
		session_unset();
		session_destroy();
		session_start();
	}

	/**
	 * Regenerate the current session id
	 */
	public static function regenerate() {
		session_regenerate_id();
	}
}