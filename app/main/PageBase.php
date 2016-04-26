<?php

class PageBase extends Base {
	protected $view;
	
	public function __construct() {
		parent::__construct();
		$this->view = View::init('partials/header', 'partials/footer')
			->set('title', 'Untitled')
			->set('username', Session::get('username'))
			->set('recaptchakey', RECAPTCHA_SITEKEY)
			->set('recaptcha', false)
			->set('slider', false)
			->set('attempts', false)
			->set('poperror', $this->input['GET']['poperror']);

		if (Session::get('attempts') == true)
			$this->view->set('attempts', true);
	}

	//TODO: MODULES
}