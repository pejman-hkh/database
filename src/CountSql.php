<?php
namespace Pejman\Database;
class CountSql {
	public static function make( $sql ) {
		$sql = substr($sql, strpos( $sql, 'from') );

		if( $lp = strpos( $sql, 'limit') )
			$sql = substr($sql, 0, $lp );

		$sql = preg_replace_callback('#order\s*by(.*?)(asc|desc)#isU', function( $m ) {
			return '';
		}, $sql);

		return "select count(*) as count ".$sql;		
	}	
}