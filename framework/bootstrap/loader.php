<?php
/**
 * Load all the required files for the MVC framework
 * Models and Controllers are (temporary?) in the same class
 */

$handle = fopen("__DIR__ . '/../.env", "r");
if ($handle) {
	while (($line = fgets($handle)) !== false) {
		$val = explode("=", preg_replace('/\s+/', '', $line));
		if (!empty($val[1])) {
			if ($val[1] == "{EMPTY}") {
				define(str_replace( array( "'",'"' ),'',$val[0] ), "");
			} else {
				define(str_replace( array( "'",'"' ),'',$val[0] ), str_replace( array( "'",'"' ),'',$val[1] ));
			}
		}
	}
	fclose($handle);
}

define('APP_PATH', __DIR__ . '/../' . PATH_APP);
define('FW_PATH', __DIR__ . '/../' . PATH_FW);

$dirs = array(
	APP_PATH . 'main/',
	FW_PATH . 'classes/'
);

function __autoload($class) {
	global $dirs;

	foreach ($dirs as $dir) {
		if(file_exists($dir . $class . '.php')) {
			require_once($dir . $class . '.php');
			return;
		}
	}
}
