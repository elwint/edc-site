<?php

class NormalPage extends Base {

	function show($params) {
		if (!isset($params['page'])) {
			$params['page'] = "";
		}
		$page = $this->db->selectBy("pages", array("linktitle" => $params['page']));

		if (!empty($page)) {
			View::init('partials/header', 'partials/footer')->set('username', Session::get('username'))
			->set('title', $page['title'])
			->set('content', $page['content'])
			->make('page');
		} else {
			$this->showNotFound();
//			View::init('partials/header', 'partials/footer')->set('username', Session::get('username'))
//			->makeStatic('404.html');
		}
	}

	function showNotFound() {
		header("HTTP/1.0 404 Not Found");
		echo '404';
	}
}