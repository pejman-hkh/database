<?php
namespace Pejman\Database;
class Factory {
	public static function wrapper() {
		if( $_ENV['driver'] == 'PDO' ) {
			if( $_ENV['db'] == 'mysql' ) {
				return new \Pejman\Database\Pdo\Mysql\Wrapper();
			}
		}
		//default wrapper from pdo mysql
		return new \Pejman\Database\Pdo\Mysql\Wrapper();
	}

	public static function modelResult( $class ) {
		if( $_ENV['driver'] == 'PDO' ) {
			if( $_ENV['db'] == 'mysql' ) {
				return new \Pejman\Database\Pdo\Mysql\ModelResult( $class );
			}
		}
		//default model from pdo mysql
		return new \Pejman\Database\Pdo\Mysql\ModelResult( $class );	
	}
}