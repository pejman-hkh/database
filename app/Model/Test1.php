<?php
namespace App\Model;
class Test1 extends \Pejman\Database\Model {
	public static $table = 'test1';

	function getTestGetter() {
		return 'getter '.$this->id;
	}

	function getTest() {
		return Test::sql("where id = ?")->findFirst( [ $this->testid ]);
	}

}