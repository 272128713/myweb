<?php
/*
 * 1.2.23. get_user_apply_auth_info.php(获取用户申请资质状态)
 */

include(dirname(__FILE__) . "/common/inc.php");
$post=$_POST;
$db= new DatabaseManager();
$db->getConn();
$config=new Config();
$sso_url=$config->getConfig("sso_url");
//检查session 
$session=$post['ss'];
$sessionInfo = $db->checkSession($session);
if(!$sessionInfo){
	ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}else{
	$post['user_id']=$sessionInfo['user_id'];
	$post['mobile']=$sessionInfo['mobile'];
     echo $db->posters_ex($sso_url, 'get_user_apply_auth_info.php', $post);
}
?>