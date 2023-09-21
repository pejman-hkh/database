<?php
namespace App\Model;
class Test extends \Pejman\Database\Model {
	public static $table = 'test';

	function getTestGetter() {
		return 'getter '.$this->id;
	}

	function getTest() {
		//if you want change some field you should use ->data inside it because you changed it with getter
		return $this->data->test.' '.$this->id;
	}
}