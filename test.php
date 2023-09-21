<?php
include __dir__.'/vendor/autoload.php';

$_ENV['driver'] = 'PDO';
$_ENV['db'] = 'mysql';

$db = new \Pejman\Database\Wrapper();
$db->connect( (object)['host' => 'localhost', 'dbName' => 'blog', 'username' => 'root', 'password' => '12c' ] );
//$query = $db->query("select * from posts", [])->find();


$test = \App\Model\Test::sql("where 1")->paginate(1,1)->find();
print_r( \App\Model\Test::getPaginate() );

print_r( $test );

/*$p = new \App\Model\Test;
$p->title = 'test';
$id = $p->save();
echo $id;
exit();*/