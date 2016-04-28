<?php

class User extends Base {
	private $rules = array(
		'Username'  => 'required|min:5|max:30',
		'Password'  => 'required|min:8',
		'Email'     => 'required|email|max:50',
		'g-recaptcha-response' => 'recaptcha',
	);

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
				$data = array(
					'username' => $this->input['POST']['Username'],
					'email' => $this->input['POST']['Email'],
					'password' => $this->generateHash($this->input['POST']['Password'], $this->input['POST']['Username']),
					'date' => date("Y-m-d"),
				);
				if (strlen($data['password']) != 64)
					ErrorHandler::makeError('Password can not be encrypted.');
				if (!$this->db->insert("users", $data))
					$this->popError('Invalid Input', 'registerpopup');
				Response::redirect('/validate-email');
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
		return hash('sha256', (SALT_EXTRA.$input.substr($inputextra, (int)SALT_START, 3)));
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
}