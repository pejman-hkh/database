<?php
namespace Pejman\Database;

class Model {

	function __construct() {
		$this->model = $this->newModel();
	}

	public function __set($name,$value) {
		$a = 'set'.$name;
		if( method_exists($this, $a ) ) {
			return $this->$a( $name, $value );
		}

		if( @$this->data->$name ) {
			$this->data->$name = $value; 
		}

		$this->$name = $value;
	}

	private $cacheGetter;
	public function __get($name) {
		$a = 'get'.$name;
		if( @$this->cacheGetter[$a] ) {
			return $this->cacheGetter[$a];
		}

		if( method_exists($this, $a ) ) {
			$this->cacheGetter[$a] = $ret = $this->$a();
			return $ret;
		}

		if( @$this->data->$name ) {
			return $this->data->$name;
		}

		return @$this->$name;
	}

	private $columns = [];
	private $data;
	function setData( $obj ) {
		$this->data = $obj;
		foreach( $obj as $k => $v ) {
			$this->columns[] = $k;
		}
	}

	public static function newModel() {
		$class = get_called_class();
		$factory = new \Pejman\Database\FactoryModel;
		return $factory->init( $class );		
	}

	public static $models = [];
	public static function modelInstance() {
		$class = get_called_class();
		if( ! @self::$models[ $class ] )
			self::$models[ $class ] = self::newModel();

		return self::$models[ $class ];
	}

	public static function sql( $sql, $bind = [] ) {
		return self::modelInstance()->sql( $sql, $bind );
	}

	function delete() {
		return $this->model->delete();
	}

	function save() {
		return $this->model->save();
	}

	public static function find( $bind = [] ) {
		return self::modelInstance()->find( $bind );
	}

	public static function field( $fields ) {
		return self::modelInstance()->field( $bind );
	}

	public static function where( $a, $b = '', $c = '' ) {
		return self::modelInstance()->where( $bind );
	}

	public static function getPaginate() {
		return self::modelInstance()->getPaginate();
	}
}