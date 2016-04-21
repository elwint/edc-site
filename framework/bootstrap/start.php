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

// Start log
new Log();

// Load our required classes
new Session();

// Load the router
Route::getRoute();