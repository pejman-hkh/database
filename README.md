# database
Database model implementation for php for every driver and every database like ( mysql,sqlite,...)

I'm working on this project for writing standard php database

# All Examples Exists On Tests Directory

# Connect to Database
```php
$db = new \Pejman\Database\Wrapper();
$config = require(__dir__.'/../config.php');
$db->connect( $this->config );
```

# Query on Database With Query Method
```php
$last = $this->db->query("select * from test order by id desc")->find()[0];
```

# Model Defination
```php
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
```

# Query on Model
```php
$test = \App\Model\Test::sql("where id = ? ")->findFirst([1]);
echo $test->testGetter;
```
