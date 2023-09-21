<?php
namespace Pejman\Database;
class FactoryModel {

	function init( $class ) {
		if( $_ENV['driver'] == 'PDO' ) {
			if( $_ENV['db'] == 'mysql' ) {
				return new \Pejman\Database\Pdo\Mysql\Model( $class );
			}
		}
		//default model from pdo mysql
		return new \Pejman\Database\Pdo\Mysql\Model( $class );		
	}	
}