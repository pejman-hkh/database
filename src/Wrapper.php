<?php
namespace Pejman\Database;

class Wrapper implements \Pejman\Database\Interface\Wrapper {

	private $db;
	public static $database;
	function __construct() {
		if( $_ENV['driver'] == 'PDO' ) {
			if( $_ENV['db'] == 'mysql' ) {
				$this->db = new \Pejman\Database\Pdo\Mysql\Wrapper();
				self::$database = $this->db;
				return;
			}
		}

		//default wrapper from pdo mysql
		$this->db = new \Pejman\Database\Pdo\Mysql\Wrapper();
		self::$database = $this->db;						
	}

	public static $dbName;

	function connect( $config ) {
		self::$dbName = $config->dbName;
		return $this->db->connect( $config );
	}

	function query( $sql, $bind = [] ) {
		return $this->db->query( $sql, $bind );
	}

	function setConfig( $config = [] ) {
		return $this->db->config( $config );
	}

	function lastInsertId() {
		return $this->db->lastInsertId();
	}

	function __destruct() {
		return $this->db->__destruct();
	}
}