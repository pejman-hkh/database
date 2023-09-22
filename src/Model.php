<?php
namespace Pejman\Database;

class Model {
	use \Pejman\Database\Traits\Model;
	function __construct() {
		$this->modelResult = $this->newModelResult();
	}

	private $data;
	public function __set( $name, $value ) {
		$setMethod = 'set'.$name;

		if( method_exists($this, $setMethod ) )
			return $this->$setMethod( $name, $value );

		$this->$name = $value;
	}

	private $cacheGetter;
	public function __get( $name ) {
		$getMethod = 'get'.$name;
		if( @$this->cacheGetter[ $getMethod ] )
			return $this->cacheGetter[ $getMethod ];

		if( method_exists($this, $getMethod ) ) {
			$this->cacheGetter[ $getMethod ] = $ret = $this->$getMethod();
			return $ret;
		}

		if( @$this->data->$name )
			return $this->data->$name;
	

		return @$this->$name;
	}

	public $columns;
	function setData( $obj ) {
		$this->data = $obj;
		foreach( $obj as $k => $v ) {
			$this->columns[] = $k;
		}
	}

	public static function newModelResult() {
		return \Pejman\Database\Factory::modelResult( get_called_class() );		
	}

	public static function sql( $sql, $bind = [] ) {
		return self::newModelResult()->sql( $sql, $bind );
	}

	function delete() {
		return $this->modelResult->delete( $this );
	}

	function save() {
		return $this->modelResult->save( $this );
	}

	public static function find( $bind = [] ) {
		return self::newModelResult()->find( $bind );
	}

	public static function field( $fields ) {
		return self::newModelResult()->field( $bind );
	}

	public static function where( $a, $b = '', $c = '' ) {
		return self::newModelResult()->where( $bind );
	}

	public static function getPaginate() {
		return self::newModelResult()->getPaginate();
	}
}