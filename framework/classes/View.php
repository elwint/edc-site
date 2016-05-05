<?php

/**
 * Our class for passing data onto the view
 *
 */

class View
{
	protected $vars = array();
	protected $header = null;
	protected $footer = null;

	/**
	 * Initiate a view incase the we want to call it use 'View::' so we can use method chaining
	 *
	 */
	public static function init($header = null, $footer = null) {
		$view = new View;
		$view->header = $header;
		$view->footer = $footer;
		return $view;
	}


	/**
	 * Non-existing property is being called, let's check if it has been set in our data array
	 */
	public function __get($name) {
		if (array_key_exists($name, $this->vars))
			return $this->vars[$name];
		return false;
	}

	/**
	 * Set the given key=>value in our data array
	 */
	public function set($key, $value) {
		$this->vars[$key] = $value;
		return $this;
	}

	/**
	 * Include the finalized view
	 *
	 */
	public function make($view) {
			$error_debug = debug_backtrace();

			if($this->header != null)
				if (! @include(APP_PATH . 'views/' . $this->header . '.php'))
					ErrorHandler::makeError("View '<b>{$this->header}</b>' does not exist", $error_debug[1]['class'], $error_debug[1]['function']);
			
			if (! @include(APP_PATH . 'views/' . $view . '.php'))
				ErrorHandler::makeError("View '<b>{$view}</b>' does not exist", $error_debug[1]['class'], $error_debug[1]['function']);

			if($this->footer != null)
				if (! @include(APP_PATH . 'views/' . $this->footer . '.php'))
					ErrorHandler::makeError("View '<b>{$this->footer}</b>' does not exist", $error_debug[1]['class'], $error_debug[1]['function']);
	}
	
	public function makeStatic($viewWithExt) {
			$error_debug = debug_backtrace();
		
			if($this->header != null)
				if (! @include(APP_PATH . 'views/' . $this->header . '.php'))
					ErrorHandler::makeError("View '<b>{$this->header}</b>' does not exist", $error_debug[1]['class'], $error_debug[1]['function']);
			
			if (! @include(APP_PATH . 'views/' . $viewWithExt))
				ErrorHandler::makeError("Static view '<b>{$viewWithExt}</b>' does not exist", $error_debug[1]['class'], $error_debug[1]['function']);

			if($this->footer != null)
				if (! @include(APP_PATH . 'views/' . $this->footer . '.php'))
					ErrorHandler::makeError("View '<b>{$this->footer}</b>' does not exist", $error_debug[1]['class'], $error_debug[1]['function']);
	}

	public function sendMail($subject, $view, $addr=array()) {
		$error_debug = debug_backtrace();
		$email_content = "";

		if($this->header != null)
			if (! file_exists(APP_PATH . 'views/' . $this->header . '.php'))
				ErrorHandler::makeError("View '<b>{$this->header}</b>' does not exist", $error_debug[1]['class'], $error_debug[1]['function']);
			else
				$email_content .= $this->return_output(APP_PATH . 'views/' . $this->header . '.php');

		if (! file_exists(APP_PATH . 'views/' . $view . '.php'))
			ErrorHandler::makeError("View '<b>{$view}</b>' does not exist", $error_debug[1]['class'], $error_debug[1]['function']);
		else
			$email_content .= $this->return_output(APP_PATH . 'views/' . $view . '.php');

		if($this->footer != null)
			if (! file_exists(APP_PATH . 'views/' . $this->footer . '.php'))
				ErrorHandler::makeError("View '<b>{$this->footer}</b>' does not exist", $error_debug[1]['class'], $error_debug[1]['function']);
			else
				$email_content .= $this->return_output(APP_PATH . 'views/' . $this->footer . '.php');

		if (empty($addr)) {
			ErrorHandler::makeError("Can't send email, no addresses defined", $error_debug[1]['class'], $error_debug[1]['function']);
		}
		foreach ($addr as $a) {
			$send_data = array(
				'from' => EMAIL_NOREPLY,
				'subject' => $subject,
				'to' => $a,
				'text' => str_ireplace(array("<br />","<br>","<br/>"), "\r\n", preg_replace('/\s+/', ' ', trim($email_content))));
			$this->sendMailgun($send_data);
		}


	}

	private function sendMailgun($data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, (EMAIL_SECURE == "false") ? false : true);
		curl_setopt($ch, CURLOPT_USERPWD, 'api:' . EMAIL_MG_KEY);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/' . EMAIL_MG_DOMAIN . '/messages');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$r = curl_exec($ch);
		if ($r === false)
			throw new Exception(curl_error($ch), curl_errno($ch));

		$info = curl_getinfo($ch);
		curl_close($ch);

		if ($info['http_code'] != 200)
			ErrorHandler::makeError('Could not send email request. ' . $r);
	}

	private function return_output($file){
		ob_start();
		include $file;
		return ob_get_clean();
	}

	protected function makeMod($viewMod) {
		$error_debug = debug_backtrace();
		if (! @include(APP_PATH . 'views/' . $viewMod . '.php'))
			ErrorHandler::makeError("View '<b>{$viewMod}</b>' does not exist", $error_debug[1]['class'], $error_debug[1]['function']);
	}

	protected function pr($val, $maxLength=null, $echo=true) {
		if (is_null($maxLength))
			$maxLength = strlen($val);
		if ($echo) {
			echo substr(htmlspecialchars($val, ENT_QUOTES, 'UTF-8'), 0, $maxLength);
			return true;
		} else {
			return substr(htmlspecialchars($val, ENT_QUOTES, 'UTF-8'), 0, $maxLength);
		}
	}

	protected function isEmpty($var) {
		return empty($var);
	}
}