<?php
namespace Pejman\Database\PDO\Mysql;
class Columns {
	public static function get( $table ) {
		$getcolumns = $this->db->query("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$table."' and table_schema = '".\Pejman\Database\Wrapper::$dbName."'  ")->find();
		$columns = [];
		foreach( $getcolumns as $v ) {
			$columns[] = $v['COLUMN_NAME'];
			$columnsType[] = [ $v['COLUMN_NAME'],  $v['DATA_TYPE'] ];
		}

		return [ $columns, $columnsType ];	
	}	
}