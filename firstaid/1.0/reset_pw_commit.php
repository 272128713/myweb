<?php
/**
* 重置密码pincode提交验证
*/ 
include(dirname(__FILE__) . "/common/inc.php");
$post=$_POST;
$db= new DatabaseManager();
$db->getConn();
$config=new Config();
$sso_url=$config->getConfig("sso_url");
echo $db->posters_ex($sso_url, 'reset_pw_commit.php', $post);


?>
