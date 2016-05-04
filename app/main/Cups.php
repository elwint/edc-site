<?php

class Cups extends PageBase {

	function show() {
		$cups = $this->db->select('posts', 'title,linktitle,sliderimage', true, "WHERE cup_reg_datetime IS NOT NULL ORDER BY datetime DESC");
		$this->view
			->set('title', 'Cups')
			->set('cups', $cups);
		$this->setModuleData('news');
		$this->setModuleData('matches');
		$this->setModuleData('results');
		$this->setModuleData('server_banner');
		$this->view->make('cups');
	}
}