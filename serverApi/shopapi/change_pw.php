<?php
/**
* 医信修改密码
*/ 

include(dirname(__FILE__) . "/common/inc.php");
$post=$_POST;
$db= new DatabaseManager();
$db->getConn();
$config=new Config();
$sso_url=$config->getConfig("sso_url");
//检查session
//print_r($post);die();
$session=$post['ss'];
$sessionInfo = $db->checkSession($session);
if(!$sessionInfo){
	$logger->error(sprintf("invalide session. session is %s",$session));
	ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION);
}else{
	$post['user_id']=$sessionInfo['user_id'];
	$post['mobile']=$sessionInfo['mobile'];
	echo $db->posters_ex($sso_url, 'change_pw.php', $post);
}


?>