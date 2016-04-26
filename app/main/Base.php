<?php

class Base {
	protected $input = array();
	protected $db;
	protected $dv;

	public function __construct() {
		$this->input['POST'] = $_POST;
		$this->input['GET'] = $_GET;
		$this->db = new Database();
		$this->dv = new DataValidator();
	}
}