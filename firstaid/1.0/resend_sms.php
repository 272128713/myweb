<?php
/**
* 再次发送sms(调用这个接口的作用是实现重发短信)
*/ 

include(dirname(__FILE__) . "/common/inc.php");
$post=$_POST;
$db= new DatabaseManager();
$db->getConn();
$config=new Config();
$sso_url=$config->getConfig("sso_url");
echo $db->posters_ex($sso_url, 'resend_sms.php', $post);
?>