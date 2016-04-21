<?php
/**
 * Session helper class
 *
 */

session_start();
$currentCookieParams = session_get_cookie_params();  
$sidvalue = session_id();  
setcookie(  
    'PHPSESSID',//name  
    $sidvalue,//value  
    0,//expires at end of session  
    $currentCookieParams['path'],//path  
    $currentCookieParams['domain'],//domain  
    true //secure  
); 

class Session {

    function __construct() {
    }

    /**
     * Set a session variable
     */
    public function set($key, $value) {
        $_SESSION['cstm'][$key] = $value;
    }

    /**
     * Push a value to an array in the session
     */
    public function push($key, $value) {
        $keys = explode('.', $key);

    }

    /**
     * Get a session variable
     */
    public function get($key) {
        if (isset($_SESSION['cstm']) && array_key_exists($key, $_SESSION['cstm']))
            return $_SESSION['cstm'][$key];
    }

    /**
     * Get a session variable and unset the variable
     */
    public function pull($key) {
        if (isset($_SESSION['cstm']) && array_key_exists($key, $_SESSION['cstm'])) {
            $var = $_SESSION['cstm'][$key];
            unset($_SESSION['cstm'][$key]);
            return $var;
        }
    }

    /**
     * Remove a session variable
     */
    public function del($key) {
        if (isset($_SESSION['cstm']) && array_key_exists($key, $_SESSION['cstm'])) {
            unset($_SESSION['cstm'][$key]);
            return true;
        }
        return false;
    }

    /**
     * Returns all set session variables
     */
    public function all() {
        return $_SESSION['cstm'];
    }


    /**
     * Clear the current session
     *
     */
    public function clear() {
        session_unset();
    }

    /**
     * Regenerate the current session id
     */
    public function regenerate() {
        session_regenerate_id();
    }
}