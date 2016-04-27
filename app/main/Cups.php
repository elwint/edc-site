<?php

class Cups extends PageBase {

	function show() {
		$this->view
			->set('title', 'Cups')
			->make('cups');
	}
}