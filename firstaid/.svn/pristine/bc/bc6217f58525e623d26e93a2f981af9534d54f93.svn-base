<?php
/**
* 更新个人基本信息
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
//	$logger->error(sprintf("invalide session. session is %s",$session));
	ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}else{
	$post['user_id']=$sessionInfo['user_id'];
	$post['mobile']=$sessionInfo['mobile'];
     echo $db->posters_ex($sso_url, 'update_personal_info.php', $post);
}
?>