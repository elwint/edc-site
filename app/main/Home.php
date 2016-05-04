<?php

class Home extends PageBase {

	function show() {
		$latestpost = $this->db->select('posts', 'title,content,datetime', false, "ORDER BY datetime DESC LIMIT 1");
		$sliders = $this->db->select('posts', 'title,linktitle,sliderimage', true, "WHERE sliderimage IS NOT NULL ORDER BY datetime DESC LIMIT 3");
		$this->view
			->set('title', 'Home')
			->set('slider', true)

			->set('post_title', $latestpost['title'])
			->set('post_date', $this->db->getDateTime($latestpost['datetime'], $this->getTimeZone(), "j F Y"))
			->set('post_content', $latestpost['content'])
			->set('sliders', $sliders);

		$this->setModuleData('news');
		$this->setModuleData('matches');
		$this->setModuleData('results');
		$this->setModuleData('server_banner');
		$this->view->make('home');
	}
}