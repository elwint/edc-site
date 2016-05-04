<?php

class NormalPage extends PageBase {

	function show($params) {
		if (!isset($params['page'])) {
			$params['page'] = "";
		}
		$page = $this->db->selectBy("pages", array("linktitle" => $params['page']));

		if ($page) {
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
		die();
	}

	function showForbidden() {
		header("HTTP/1.0 403 Forbidden");
		echo "<h1>Forbidden</h1>";
		echo "You don't have permission to access the requested object. It is either read-protected or not readable by the server.";
		die();
	}
}