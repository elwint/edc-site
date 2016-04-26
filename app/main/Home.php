<?php

class Home extends PageBase {

	function show() {
		$this->view
			->set('title', 'Home')
			->set('slider', true)
			->make('home');
	}
}