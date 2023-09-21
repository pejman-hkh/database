<?php
namespace Pejman\Database\Pdo\Mysql;

class Wrapper implements \Pejman\Database\Interface\Wrapper {

	private $statment, $connection;

	public function setConfig( $config = [] ) {
		foreach( $config as $k => $v ) {
			$this->connection->setAttribute( $k, $v );
		}
	}

	function getConnection() {
		return $this->connection;
	}
	function connect( $config ) {
		try {
			$this->connection = new \PDO( "mysql:host=".$config->host.";dbname=".$config->dbName.";charset=utf8", $config->username, $config->password );
			$this->setConfig([\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);

		} catch(\PDOException $e) {
			throw new \Exception("Error in connect");
		}		
	}

	function query( $sql, $bind = [] ) {
		try {
	
			$this->statment = $this->connection->prepare( $this->sql = $sql );
		} catch ( \PDOException $e ) {
			throw new \Exception( $e );
		}
		return $this->exec( $bind );		
	}

	private function bind( &$bind = [] ) {

		try {
			if( is_array( $bind ) && @count( $bind ) > 0 ) foreach( $bind as $k => $v ) {
				$this->statment->bindValue( $k + 1, $v,  is_int( $v ) ? \PDO::PARAM_INT : \PDO::PARAM_STR );
			}
		} catch (\PDOException $e) {
			throw new \Exception( $e );
		}

		return $this;

	}

	private function exec( $bind = [] ) {
		$this->bind( $bind );
		try {
			$this->statment->execute();
		} catch (\PDOException $e) {
			throw new \Exception( $e );
		}

		$res = new Result( $this->statment, $this );
		return $res;		
	}

	function lastInsertId() {
		return $this->connection->lastInsertId();
	}

	public function __destruct() {
		$this->connection = null;
		unset( $this->connection );
	}	
}