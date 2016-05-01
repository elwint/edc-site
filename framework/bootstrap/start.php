<?php

// Load the class loader
require_once('loader.php');

// Set default timezone
date_default_timezone_set(TIMEZONE);

// Define client ip
define('CLIENT_IP', $_SERVER['REMOTE_ADDR']);

// Maintenance mode
if (MAINTENANCE_MODE == "1") {
	$chk = true;
	foreach (explode(",",MAINTENANCE_IP) as $ip) {
		if (CLIENT_IP == $ip) {
			$chk = false;
			break;
		}
	}
	if ($chk) {
		header("HTTP/1.0 503 Service Unavailable");
		try {
			View::init()->makeStatic(MAINTENANCE_VIEW);
		} catch (Exception $e) {
			echo "Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. We&rsquo;ll be back online shortly!";
		}
		die();
	}
}

// Initiate our required classes
Log::init();
ErrorHandler::init();
Session::init();

// require the routes
require_once(APP_PATH . 'routes.php');

// Load the router
Route::getRoute();