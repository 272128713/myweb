<?php
/**
* 重置密码
*/ 
include(dirname(__FILE__) . "/common/inc.php");
$post=$_POST;
$db= new DatabaseManager();
$db->getConn();
$config=new Config();
$sso_url=$config->getConfig("sso_url");
echo $db->posters_ex($sso_url, 'reset_pw.php', $post);



?>