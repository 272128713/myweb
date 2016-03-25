<?php
 
// Or if you just download the medoo.php into directory, require it with the correct path.
require  'php/medoo.php';
 
$database = new medoo(array(
	// required
	'database_type' => 'mysql',
	'database_name' => 'website',
	'server' => '117.34.72.251',
	'username' => 'rongke',
	'password' => 'rongke',
	'charset' => 'utf8',
 
	// [optional]
	'port' => 3306,


));




$page_lenth=6;
?>