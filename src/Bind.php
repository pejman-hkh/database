<?php
namespace Pejman\Database;
class Bind {
	public static function make( $columns, $model ) {
		$vals = [];
		foreach( $columns as $v ) {
			$type = gettype( $model->$v );
			if( is_array( $model->$v ) ) {
				$model->$v = implode(",", $model->$v);
			}
			
			if( $type == "integer" ) {
				$vals[] = (int)$model->$v;
			} else if( $type == "string" ) {
				$vals[] = (string)$model->$v;
			} else if( $type == "double" ) {
				$vals[] = (double)$model->$v;			
			} else {
				$vals[] = (string)$model->$v;
			}
		}
		
		if( $model->recordExists )
			$vals[] = $model->id;
		
		return $vals;	
	}	
}