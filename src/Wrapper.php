<?php
namespace Pejman\Database;

class Wrapper implements \Pejman\Database\Interface\Wrapper {

	private $db;

	function __construct() {
		if( $_ENV['driver'] == 'PDO' ) {
			if( $_ENV['db'] == 'mysql' ) {
				$this->db = new \Pejman\Database\Pdo\Mysql\Wrapper();
			}
		}
	}

	function connect( $config ) {
		return $this->db->connect( $config );
	}

	function query( $sql, $bind = [] ) {
		return $this->db->query( $sql, $bind );
	}

	function setConfig( $config = [] ) {
		return $this->db->config( $config );
	}

	function __destruct() {
		return $this->db->__destruct();
	}
}