<?php
namespace Pejman\Database\Traits;

trait Model {
	public function __set($name,$value) {
		$a = 'set'.$name;
		if( method_exists($this, $a ) ) {
			return $this->$a( $name, $value );
		}

		$this->$name = $value;
	}

	private $cacheGet;
	public function __get($name) {
		$a = 'get'.$name;
		if( @$this->cacheGet[$a] ) {
			return $this->cacheGet[$a];
		}

		if( method_exists($this, $a ) ) {
			$this->cacheGet[$a] = $ret = $this->$a();
			return $ret;
		}

		return @$this->$name;
	}	
}