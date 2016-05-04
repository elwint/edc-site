<?php

class Result extends PageBase {

	function show($params) {
		if (!isset($params['linktitle'])) {
			Route::routeCode("404");
			return;
		}

		$result = $this->db->selectBy('results', array('linktitle' => $params['linktitle']));
		if ($result) {
			$this->view
				->set('title', "Results - ".$result['title'])
				->set('date', $this->db->getDateTime($result['datetime'], $this->getTimeZone(), "j-M-Y"))
				->set('spreadsheet_id', $result['spreadsheet_id'])
				->make('result');
		} else {
			Route::routeCode("404");
		}
	}
}