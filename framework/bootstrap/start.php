<?php

// Load the class loader
require_once('loader.php');

// Set timezone
date_default_timezone_set(TIMEZONE);

// Maintenance mode
if (MAINTENANCE_MODE == "1") {
	View::init()->makeStatic('full_pages/maintenance.html');
	die();
}

// require the routes
require_once(APP_PATH . 'routes.php');

// Load our required classes
Log::init();
Session::init();

// Load the router
Route::getRoute();