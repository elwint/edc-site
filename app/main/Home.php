<?php

class Home extends Base {

	function show() {
		View::init('partials/header', 'partials/footer')->set('username', Session::get('username'))
			->set('title', 'Home')
			->make('home');
	}
}