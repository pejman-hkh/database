<?php
namespace Pejman\Database;
class FactoryModelResult {

	public static function init( $class ) {
		if( $_ENV['driver'] == 'PDO' ) {
			if( $_ENV['db'] == 'mysql' ) {
				return new \Pejman\Database\Pdo\Mysql\ModelResult( $class );
			}
		}
		//default model from pdo mysql
		return new \Pejman\Database\Pdo\Mysql\ModelResult( $class );		
	}	
}