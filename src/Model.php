<?php
namespace Pejman\Database;

class Model {
	use \Pejman\Database\Traits\Model;
	function __construct() {
		$this->modelResult = $this->newModelResult();
	}

	private $data;
	public function __set($name,$value) {
		$a = 'set'.$name;
		if( method_exists($this, $a ) )
			return $this->$a( $name, $value );

		$this->$name = $value;
	}

	private $cacheGetter;
	public function __get($name) {
		$a = 'get'.$name;
		if( @$this->cacheGetter[$a] )
			return $this->cacheGetter[$a];

		if( method_exists($this, $a ) ) {
			$this->cacheGetter[$a] = $ret = $this->$a();
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
		$class = get_called_class();
		return \Pejman\Database\FactoryModelResult::init( $class );		
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