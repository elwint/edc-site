<?php

class Route {

    /**
     * Set a prefix for the route
     *
     */
    public function prefix($preview) {
        $GLOBALS['routeprefix'] = $preview;
    }
    /**
     * Register a get type route
     *
     */
    public function get($route, $controller) {
        Route::generate($route, $controller, 'GET');
    }

    /**
     * Register a post type route
     *
     */
    public function post($route, $controller) {
        Route::generate($route, $controller, 'POST');
    }

    /**
     * Register a delete type route
     *
     */
    public function delete($route, $controller) {
        Route::generate($route, $controller, 'DELETE');
    }

    /**
     * Register a put type route
     *
     */
    public function put($route, $controller) {
        Route::generate($route, $controller, 'PUT');
    }

    /**
     * Add the route to the route group
     *
     */
    private function generate($route, $controller, $type) {
        $insert = array();
		if ($route == '/' || $route == '') {
			$insert['request'] = array_values(array(''));
		} else {
			$insert['request'] = array_values(array_filter(explode('/', $route)));
		}
        if($GLOBALS['routeprefix'] != '')
            array_unshift($insert['request'], $GLOBALS['routeprefix']);
        $ctrl = explode('@', $controller);
        $insert['controller'] = $ctrl[0];
        $insert['function'] = $ctrl[1];
        $insert['type'] = $type;

        $GLOBALS['routes'][] = $insert;
    }

    /**
     * Find our route
     *
     */
    public function getRoute() {
        // default values
		$request_uri=strtok($_SERVER["REQUEST_URI"],'?');
		if ($request_uri == '/') {
			$path = array_values(array(''));
		} else {
			$path = array_values(array_filter(explode('/', $request_uri)));
		}
		
        $params = array();
        $match = array();
        $error = true;

        // loop through the routes, check if there is a viable route otherwise throw our 404 page
        foreach($GLOBALS['routes'] as $route) {
            foreach($route['request'] as $idx => $part) {
                // check if the request method matches the route's request method
                if($route['type'] != $_SERVER['REQUEST_METHOD'])
                    continue 2;

                // check if the index is set, and if so if the current iteration doesn't match the param in the path and if it isn't a 'any' param
                if(isset($path[$idx]) && $part != $path[$idx] && $part[0] != ':') {
                    $error = true;
                    continue 2;
                }

                // fill our param array
                if(isset($path[$idx]) && $part[0] == ':') {
                    $params[str_replace(':', '', $part)] = $path[$idx];
                }

                // check if it's the last iteration, if so verify that the length of the given route matches the length of the set route also set the matched route
                if($part === end($route['request'])) {
                    // set the amount of route items
                    $count = count($path);

                    if ($count - 1 == $idx) {
                        $error = false;
                        $match = $route;
                        break 2;
                    }
                }
            }
        }

        // no route has been found, show 404 page
        if($error) {
			View::init('partials/header', 'partials/footer')->set('username', Session::get('username'))
			->makeStatic('404.html');
            die();
        }

        // hooray, route has been found lets load the controller.
        Route::createObject($match['controller'], $match['function'], $params);
    }

    /**
     * Creates a controller object based on the parameters
     *
     *
     */
    function createObject($name, $function, $params) {
        $obj = new $name;
        $obj->$function($params);
    }
}