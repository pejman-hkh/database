<?php
namespace Pejman\Database\PDO\Mysql;
class Columns {
	public static $columns = [];
	public static function init( $connection, $table ) {
		if( @self::$columns[ $table ] ) return self::$columns[ $table ];

		$getcolumns = $connection->query("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$table."' and table_schema = '".\Pejman\Database\Wrapper::$dbName."'  ")->find();
		$columns = [];
		foreach( $getcolumns as $v ) {
			$columns[] = $v->COLUMN_NAME;
			$columnsType[] = [ $v->COLUMN_NAME,  $v->DATA_TYPE ];
		}

		self::$columns[ $table ] = [ $columns, $columnsType ];
		return self::$columns[ $table ];	
	}	
}