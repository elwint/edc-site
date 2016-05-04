<?php

class Post extends PageBase {

	function show($params) {
		if (!isset($params['linktitle'])) {
			Route::routeCode("404");
			return;
		}
		//TODO: COMMENTS
		$post = $this->db->selectBy('posts', array('linktitle' => $params['linktitle']));
		$msg_box = false;
		$cup_reg_datetime = false;
		$cup_participated = false;
		$cup_reg_end = false;
		$username = Session::get('username');

		$curdate=strtotime($this->db->getGMTDateTime());
		$regdate=strtotime($post['cup_reg_datetime']);
		if ($curdate > $regdate)
			$cup_reg_end = true;

		if (!empty($post['cup_reg_datetime'])) {
			if (!empty($_POST)) {
				if (empty($username)) {
					$msg_box = 'Please log in to sign up for ' . $post['title'] . '.';
				} elseif ($cup_reg_end) {
					$msg_box = 'Sorry, registrations closed.';
				} else {
					$user = $this->db->selectBy('users', array("username" => $username));
					if ($_POST['participate'] == 'false') {
						if ($this->db->delete('participants', 'WHERE user_id=:user_id AND post_id=:post_id',  array('user_id' => $user['id'], 'post_id' => $post['id'])))
							$msg_box = 'Signed out succesfully for ' . $post['title'] . '.';
						else
							$msg_box = 'Sorry, an error occurred.';
					} elseif ($_POST['participate'] == 'true') {
						if ($this->db->insert('participants', array('user_id' => $user['id'], 'post_id' => $post['id'])))
							$msg_box = 'Thank you for signing up for ' . $post['title'] . '!\nYou will receive email notifications about important information about the cup.\nIf you dont want such notifications, you can disable them in your profile settings.';
						else
							$msg_box = 'Sorry, an error occurred.';
					}
				}
			}

			$cup_reg_datetime = $this->db->getDateTime($post['cup_reg_datetime'], $this->getTimeZone(), "j F Y H:i T");
			if (!empty($username)) {
				if ($this->getCupUser($username, $post['id'], 'users.id'))
					$cup_participated = true;
			}
		}

		if ($post) {
			$this->view
				->set('title', $post['title'])
				->set('date', $this->db->getDateTime($post['datetime'], $this->getTimeZone(), "j F Y"))
				->set('content', $post['content'])
				->set('msg_box', $msg_box)
				->set('reg_datetime', $cup_reg_datetime)
				->set('participated', $cup_participated)
				->set('reg_end', $cup_reg_end)
				->make('post');
		} else {
			Route::routeCode("404");
		}
	}

	/*
	 * Return user if user participated cup
	 */
	private function getCupUser($username, $post_id, $columns='users.*') {
		return $this->db->selectJoin('participants', array('users' => 'participants.user_id=users.id'), $columns, 'INNER', false, 'WHERE users.username=:username AND participants.post_id=:post_id', array('username' => $username, 'post_id' => $post_id));
	}

	private function getCupUsers($post_id, $columns='users.*') {
		return $this->db->selectJoin('participants', array('users' => 'participants.user_id=users.id'), $columns, 'INNER', true, 'WHERE participants.post_id=:post_id', array('post_id' => $post_id));
	}
}