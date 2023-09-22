<?php
namespace Pejman\Database;
class Query {
	public static function makeUpdateQuery( $table, $columns ) {
		return "UPDATE `".$table."` SET ".'`'.implode('` = ?, `', $columns ).'` = ? '." WHERE id = ? ";
	}

	public static function makeInsertQuery( $table, $columns ) {
		return "INSERT INTO `".$table."`(".'`'.implode("` , `", @$columns ).'`'.") VALUES(".( str_repeat('?,', count( @$columns ) - 1 ).'?' ).")";
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

	public static function makeSelectQuery( $sql, $table, $fields = '*') {
		if( substr( trim( $sql ), 0, 5 ) != 'select' ) {
			$sql = "select $fields from ".$table." ".($sql?:"");
		}
		return $sql;
	}

	public static function makeDeleteQuery( $table ) {
		return "DELETE FROM ".$table." where id = ? ";
	}
}