<?php
namespace Pejman\Database;
class Query {
	public static function getUpdateQuery( $table, $columns ) {
		return "UPDATE `".$table."` SET ".'`'.implode('` = ?, `', $columns ).'` = ? '." WHERE id = ? ";
	}

	public static function getInsertQuery( $table, $columns ) {
		return "INSERT INTO `".$this->table."`(".'`'.implode("` , `", @$this->columns ).'`'.") VALUES(".( str_repeat('?,', count( @$this->columns ) - 1 ).'?' ).")";
	}

	public static function makeWhereQuery( $a, $b, $c ) {
		$sql = "where 1 = 1 ";
		$bind = [];
		if( is_array( $a ) ) foreach( $a as $k => $v ) {
			$bind[] = $v;
			$sql .= " and $k = ? ";
		} else {
			$bind[] = $c;
			$sql .= " and $a $b = ? ";
		}

		return [ $sql, $bind ];		
	}
}