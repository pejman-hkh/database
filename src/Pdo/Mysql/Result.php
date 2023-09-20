<?php
namespace Pejman\Database\Pdo\Mysql;

class Result implements \Pejman\Database\Interface\Result {
	function __construct( $statement, $wrapper ) {
		$this->statment = $statement;
		$this->wrapper = $wrapper;
	}

	function next() {
		return $this->statment->fetch( \PDO::FETCH_ASSOC  );
	}

	function find() {
		return $this->statment->fetchAll( \PDO::FETCH_ASSOC );
	}
	
	function count() {
		return $this->statment->rowCount();
	}
}