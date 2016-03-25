<?php
/*
 * 空中急救注册流程
 * 1.2.1. register.php（注册）
 */
include(dirname(__FILE__) . "/common/inc.php");
$post=$_POST;
$db= new DatabaseManager();
$db->getConn();
$config=new Config();
$sso_url=$config->getConfig("sso_url");
echo $db->posters_ex($sso_url, 'register.php', $post);

?>