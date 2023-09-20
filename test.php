<?php
include __dir__.'/vendor/autoload.php';

$_ENV['driver'] = 'PDO';
$_ENV['db'] = 'mysql';

$db = new \Pejman\Database\Wrapper();
$db->connect( (object)['host' => 'localhost', 'dbName' => 'blog', 'username' => 'root', 'password' => '12c' ] );

$query = $db->query("select * from posts", [])->find();

print_r( $query );