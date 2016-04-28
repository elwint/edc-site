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

	protected function setModuleData($module) {
		switch ($module) {
			case 'news':
				$side_news = $this->db->select('posts', 'title,linktitle', true, "ORDER BY datetime DESC LIMIT 7");
				$this->view->set('side_news', $side_news);
				break;

			case 'matches':
				$db_matches = $this->db->selectJoin('matches', array('posts' => 'matches.post_id=posts.id'), "matches.title,matches.datetime,posts.linktitle", "lEFT", true, "ORDER BY matches.datetime, matches.title LIMIT 10");
				//TODO: Remove check (match al geweest)
				$side_matches = array();
				if (is_array($db_matches)) {
					foreach ($db_matches as &$match) {
						$matchdate = $this->db->getDateTime($match['datetime'], $this->getTimeZone(), "j F Y");
						$match['time'] = $this->db->getDateTime($match['datetime'], $this->getTimeZone(), "H:i T");
						if (!is_array($side_matches[$matchdate]))
							$side_matches[$matchdate] = array();
						array_push($side_matches[$matchdate], $match);
					}
				}
				$this->view->set('side_matches', $side_matches);
				break;
			
			case 'results':
				$side_results = $this->db->select('results', 'title,linktitle', true, "ORDER BY datetime DESC LIMIT 5");
				$this->view->set('side_results', $side_results);
				break;

			case 'server_banner':
				$server_info = $this->db->select("server_info", "ORD(status) as status", false);

				if ($server_info['status'] == "1")
					$this->view->set('server_online', true);
				else
					$this->view->set('server_online', false);
		}
	}

	protected function getTimeZone() {
		if (Session::get('timezone'))
			return Session::get('timezone');
		else
			return TIMEZONE;
	}
}