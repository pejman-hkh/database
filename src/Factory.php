<?php
namespace Pejman\Database;
class Factory {
	function init() {
		if( $_ENV['driver'] == 'PDO' ) {
			if( $_ENV['db'] == 'mysql' ) {
				return new \Pejman\Database\Pdo\Mysql\Wrapper();
			}
		}
		//default wrapper from pdo mysql
		return new \Pejman\Database\Pdo\Mysql\Wrapper();
	}
}