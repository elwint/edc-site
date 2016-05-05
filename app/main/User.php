<?php

class User extends PageBase {
	private $rules = array(
		'Username'  => 'required|min:5|max:30',
		'Email'     => 'required|email|max:50',
		'g-recaptcha-response' => 'recaptcha',
	);

	function account($params) {
		$username = Session::get('username');
		if (empty($username)) {
			$this->view
				->set('title', 'Not Logged In')
				->set('content', 'Please log in and try again.')
				->make('page');
			return;
		}
		$user = $this->getUserByUsername($username);

		if (!empty($_POST)) {
			if (isset($_POST['Timezone'])) {
				$error = false;
				if ($this->updateUserByUsername($username, array('timezone' => $_POST['Timezone'])) === false) {
					$this->view->set('msg_box', 'Invalid input.');
					$error = true;
				} else {
					$parts = $this->db->selectBy('participants', array('user_id' => $user['id']), true);
					if (is_array($parts)) {
						foreach ($parts as $part) {
							if (isset($_POST[$part['post_id']]) && $_POST[$part['post_id']] == "1")
								$result = $this->db->updateBy('participants', array('notify' => 1), array('post_id' => $part['post_id'], 'user_id' => $user['id']));
							else
								$result = $this->db->updateBy('participants', array('notify' => 0), array('post_id' => $part['post_id'], 'user_id' => $user['id']));
							if ($result === false) {
								$this->view->set('msg_box', 'Invalid input.');
								$error = true;
								break;
							}
						}
					}
				}
				if (!$error) {
					Session::set('timezone', $_POST['Timezone']);
				}
			} elseif (isset($_POST['OldPassword'])) {
				//TODO: From e-mail no old pw required
				$error = "";
				if ($this->generateHash($_POST['OldPassword'], $username) != $user['password'])
					$error = "Old password wrong!";
				if (empty($error)) {
					if ($result = $this->dv->validateData($_POST, array('NewPassword' => 'required|min:8'))) {
						$firstresult = reset($result);
						$error = $firstresult['msg'];
						$this->view->set('msg_box', $error);
					} else {
						if ($this->updateUserByUsername($username, array('password' => $this->generateHash($_POST['NewPassword'], $username))) === false)
							$this->view->set('msg_box', 'Invalid input.');
						else
							$this->view->set('msg_box', 'Password changed succesfully!');
					}
				} else {
					$this->view->set('msg_box', $error);
				}
			}
		}

		if (!isset($params['page'])) {
			$cups = $this->getUserCups($username, 'participants.notify,posts.id,posts.title');
			$this->view
				->set('title', 'Account Settings')
				->set('email', $user['email'])
				->set('cups', $cups)
				->set('user_tz', $this->getTimeZone())
				->set('timezones', DateTimeZone::listIdentifiers())
				->make('account/main');
		} else {
			switch ($params['page']) {
				case 'temppw':
					$this->view
						->set('title', 'Change Temporary Password')
						->set('oldreq', true)
						->make('account/password');
					break;
				case 'password':
					$this->view
						->set('title', 'Change Password')
						->set('oldreq', true)
						->make('account/password');
					break;
				case 'delete':
					$this->view
						->set('title', 'Delete Account')
						->make('account/delete');
					break;
				default:
					Route::routeCode("404");
			}
		}
	}

    function login() {
		if (isset($this->input['POST'])) {
			$user = $this->getUserByUsername($this->input['POST']['Username']);
			if (!$user) {
				$this->popError('Invalid Credentials', 'loginpopup');
				return;
			}
			
			$attempts = (int)$user['login_attempts'];
			if ($attempts >= (int)FAST_LOGIN_ATTEMPTS) {
				Session::set('attempts', true);
				if ($result = $this->dv->validateData($this->input['POST'], array('g-recaptcha-response' => 'recaptcha'))) {
					$firstresult = reset($result);
					$poperror = $firstresult['msg'];
					$this->popError($poperror, 'loginpopup');
					return;
				}
			}
			
			if ($this->generateHash($this->input['POST']['Password'], $this->input['POST']['Username']) == $user['password']) {
				Session::clear();
				Session::regenerate();
				Session::set('username', $user['username']);
				Session::set('timezone', $user['timezone']);
				$this->updateUserByUsername($user['username'], array('login_attempts' => 0));
				Response::redirect($this->input['POST']['path']);
			} else {
				$this->updateUserByUsername($user['username'], array('login_attempts' => ++$attempts));
				$this->popError('Invalid Credentials', 'loginpopup');
			}
		} else {
			ErrorHandler::makeError('Function called without POST input');
		}
    }

	function logout() {
		Session::clear();
		Session::regenerate();
		Response::redirect('/');
	}

	function register() {
		if (isset($this->input['POST'])) {
			$poperror = "";
			if($result = $this->dv->validateData($this->input['POST'], $this->rules)) {
				$firstresult = reset($result);
				$poperror = $firstresult['msg'];
			} elseif ($this->getUserByUsername($this->input['POST']['Username']))
				$poperror = "Username already in use.";
			elseif ($this->getUserByEmail($this->input['POST']['Email']))
				$poperror = "Email address already in use.";
			if (empty($poperror)) {
				$pw = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
				$data = array(
					'username' => $this->input['POST']['Username'],
					'email' => $this->input['POST']['Email'],
					'password' => $this->generateHash($pw, $this->input['POST']['Username']),
					'datetime' => $this->db->getGMTDateTime(),
				);
				if (!$this->db->insert("users", $data))
					$this->popError('Invalid Input', 'registerpopup');

				View::init(null, 'emails/footer')
					->set('username', $data['username'])
					->set('password', $pw)
					->sendMail('EDC | Account Registration', 'emails/register', array($data['email']));
				Response::redirect('/registration-success');
			} else {
				$this->popError($poperror, 'registerpopup');
			}
		} else {
			ErrorHandler::makeError('Function called without POST input');
		}
	}

	private function popError($error, $popupName) {
		Response::redirect($this->input['POST']['path'].'?poperror='.$error.'#'.$popupName);
	}

	private function generateHash($input, $inputextra) {
		$hash = hash('sha256', (SALT_EXTRA.strtolower($input).substr(strtolower($inputextra), (int)SALT_START, 3)));
		if (strlen($hash) != 64)
			ErrorHandler::makeError('Password can not be encrypted.');
		return $hash;
	}

	private function getUserByUsername($username) {
		return $this->db->selectBy('users', array("username" => $username));
	}

	private function getUserByEmail($email) {
		return $this->db->selectBy('users', array("email" => $email));
	}

	private function updateUserByUsername($username, $values) {
		$this->db->updateBy('users', $values, array('username' => $username));
	}

	private function getUserCups($username, $columns="posts.*") {
		return $this->db->selectJoin('participants', array('posts' => 'participants.post_id=posts.id', 'users' => 'participants.user_id=users.id'), $columns, 'INNER', true, 'WHERE users.username=:username ORDER BY posts.datetime DESC', array('username' => $username));
	}
}