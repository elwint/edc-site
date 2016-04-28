<?php

class Api extends Base {
	/*
	 * Only accessible by server ip
	 */
	function setServer($params) {
		$ip = $this->clientIp;
		$error = 'none';
		if (($this->db->selectBy('server_info', array('ip'=>$ip))) == false) {
			$result = false;
			$error = "no-permission";
		} else {
			switch ($params['bool']) {
				case "false":
					$result = $this->db->update('server_info', array('status' => 0));
					if (!$result)
						$error = "database";
					break;
				case "true":
					$result = $this->db->update('server_info', array('status' => 1));
					if (!$result)
						$error = "database";
					break;
				default:
					$result = false;
					$error = "not-boolean";
			}
		}
		Response::json(array('success'=>$result,'error'=>$error,'ip'=>$ip));
	}
}