<?php

class Post extends PageBase {

	function show($params) {
		if (!isset($params['linktitle'])) {
			Route::routeCode("404");
			return;
		}
		//TODO: COMMENTS
		$post = $this->db->selectBy('posts', array('linktitle' => $params['linktitle']));
		if ($post) {
			$this->view
				->set('title', $post['title'])
				->set('date', $this->db->getDateTime($post['datetime'], $this->getTimeZone(), "j-M-Y"))
				->set('content', $post['content'])
				->make('post');
		} else {
			Route::routeCode("404");
		}
	}
}