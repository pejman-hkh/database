<?php
namespace Pejman\Database;
class Bind {
	public static function make( $columns, $data ) {
		$vals = [];
		foreach( $columns as $v ) {
			$type = gettype( $this->$v );
			if( is_array( $this->$v ) ) {
				$data->$v = implode(",", $data->$v);
			}
			
			if( $type == "integer" ) {
				$vals[] = (int)$data->$v;
			} else if( $type == "string" ) {
				$vals[] = (string)$data->$v;
			} else if( $type == "double" ) {
				$vals[] = (double)$data->$v;			
			} else {
				$vals[] = (string)$data->$v;
			}
		}
		
		return $vals;	
	}	
}