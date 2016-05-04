<?php

/**
 * Database PDO class
 *
 */

class Database {
	private $dbh;
	private $stmt;

	/*
	 * Connect to database
	 */
	function __construct() {
		$dsn = 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_DATABASE;
		
		try {
			$this->dbh = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		} catch (PDOException $e) {
			ErrorHandler::makeError('Connection error: ' . $e->getMessage());
		}
		
		$this->dbh->exec("set names utf8");
	}

	/*
	 * Check of which data type the given value is
	 */
	private function getDataType($value) {
		switch (true) {
			case is_int($value):
				$type = PDO::PARAM_INT;
				break;
			case is_bool($value):
				$type = PDO::PARAM_BOOL;
				break;
			case is_null($value):
				$type = PDO::PARAM_NULL;
				break;
			default:
				$type = PDO::PARAM_STR;
		}
		return $type;
	}

	/*
	 * Prepare query and bind values and/or execute query
	 */
	public function query($sql, $values = array(), $execute = true) {
		$this->stmt = $this->dbh->prepare($sql);
		foreach ($values as $param => $value) {
			$this->stmt->bindValue(":{$param}", $value, $this->getDataType($value));
		}
		if ($execute == true) {
			return $this->execute();
		} else {
			return null;
		}
	}

	/*
	 * Insert method
	 */
	public function insert($table, $values) {
		$sql = "INSERT INTO {$table} (";
		$sqlvalues = " VALUES (";
		foreach ( array_keys ( $values ) as $column ) {
			$sql .= "{$column},";
			$sqlvalues .= ":{$column},";
		}
		$sql [strlen ( $sql ) - 1] = ')';
		$sqlvalues [strlen ( $sqlvalues ) - 1] = ')';
		$sql .= $sqlvalues;
		return $this->query($sql, $values);
	}

	/*
	 * Update method
	 */
	public function update($table, $values, $extra="", $extravalues=array()) {
		$sql = "UPDATE {$table} SET ";
		foreach ( array_keys ( $values ) as $column ) {
			$sql .= "{$column}=:{$column},";
		}
		$sql [strlen ( $sql ) - 1] = ' ';
		$sql .= $extra;
		$values = array_merge($values, $extravalues);
		return $this->query($sql, $values);
	}

	/*
	 * Select method
	 */
	public function select($table, $columns = "*", $fetchAll=true, $extra="", $extravalues=array()) {
		$sql = "SELECT {$columns} FROM {$table} {$extra}";
		$this->query($sql, $extravalues);
		return $this->fetch($fetchAll);
	}

	/*
	 * Delete method
	 */
	public function delete($table, $extra="", $extravalues=array()) {
		return $this->query("DELETE FROM {$table} ".$extra, $extravalues);
	}

	/*
	 * Select join method
	 */
	public function selectJoin($table, $jointables=array('jointable' => 'condition=condition'), $columns = "*", $join="INNER", $fetchAll=true, $extra="", $extravalues=array()) {
		$sqlextra = "";
		foreach ($jointables as $jointable => $condition) {
			$sqlextra .= $join . " JOIN " . $jointable . " ON " . $condition . " ";
		}
		$sqlextra .= $extra;
		return $this->select($table, $columns, $fetchAll, $sqlextra, $extravalues);
	}

	/*
	 * Select all
	 */
	public function selectAll($table, $fetch=true) {
		$result = $this->query("SELECT * FROM {$table}");
		return ($fetch ? $this->fetch(true) : $result);
	}

	/*
	 * Select by
	 */
	public function selectBy($table, $wherearray, $fetchall=false) {
		$result = $this->select($table, "*", $fetchall, "WHERE ".key($wherearray)."=:".key($wherearray), $wherearray);
		return $result;
	}

	/*
	 * Update by
	 */
	public function updateBy($table, $values, $wherearray) {
		$result = $this->update($table, $values, "WHERE ".key($wherearray)."=:".key($wherearray), $wherearray);
		return $result;
	}

	/*
	 * Delete by
	 */
	public function deleteBy($table, $wherearray) {
		return $this->delete($table, "WHERE ".key($wherearray)."=:".key($wherearray), $wherearray);
	}

	/*
	 * Executes prepared query
	 */
	public function execute() {
		try {
			return $this->stmt->execute();
		} catch (PDOException $e) {
			ErrorHandler::makeError('Database error: ' . $e->getMessage());
			return null;
		}
	}

	/*
	 * Returns row count
	 */
	public function getRowCount() {
		return $this->stmt->rowCount();
	}

	/*
	 * Fetch result, query must be executed first
	 */
	public function fetch($all = true) {
		if ($this->getRowCount() > 0) {
			return ($all ? $this->stmt->fetchAll(PDO::FETCH_ASSOC) : $this->stmt->fetch(PDO::FETCH_ASSOC));
		} else {
			// No records found
			return false;
		}
	}

	public function getGMTDateTime($format="Y-m-d H:i:s") {
		return gmdate($format);
	}

	public function getDateTime($gmt, $timezone, $format="Y-m-d H:i:s") {
		$dt = new DateTime($gmt." +00");
		$tz = new DateTimeZone($timezone);
		$dt->setTimezone($tz);
		return $dt->format($format);
	}
}
