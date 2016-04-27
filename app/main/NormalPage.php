<?php

class NormalPage extends PageBase {

	function show($params) {
		if (!isset($params['page'])) {
			$params['page'] = "";
		}
		$page = $this->db->selectBy("pages", array("linktitle" => $params['page']));

		if (!empty($page)) {
			$this->view
			->set('title', $page['title'])
			->set('content', $page['content'])
			->make('page');
		} else {
			$this->showNotFound();
		}
	}

	function showNotFound() {
		header("HTTP/1.0 404 Not Found");
		$this->view
			->set('title', '404 Not Found')
			->makeStatic('404.html');
	}
}