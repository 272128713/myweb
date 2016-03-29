<?php
/*
 * 空中急救注册流程
 * 1.2.12. get_avatar.php（获取个人信息）
 */
header("content-type:text/html; charset=utf-8");
include (dirname(__FILE__) . "/common/MMSFileManager.php");
include(dirname(__FILE__) . "/common/inc.php");

$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$challengeStep = false;
$params = array(array("ss",true),array("uid",true),array("flag",true));

$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

$databaseManager = new DatabaseManager();
$database = $databaseManager->getConn();
if(!$database){
	$logger->error(sprintf("Database connect fail."));
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}

$session = $params["ss"];
$sessionCheck = $databaseManager->checkSession($session);
if(!$sessionCheck){
	$logger->error(sprintf("Session check is fail. Error session is [%s]",$session));
	ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}

$post['user_id']= $sessionCheck["user_id"];  //登陆的用户id
$post['uid'] = $params["uid"];  //要获取的用户的id(被获取人的UID)
$post['flag'] = $params["flag"]; //1代表返回缩略图，2代表大图
$flag=$params["flag"];
if($flag > 2){
	$logger->error(sprintf("params error. flag is $flag,should be 1 or 2"));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}
$config = new Config();
$sso_url=$config->getConfig("sso_url");

//Header("Content-Disposition: attachment; filename=" . rand(123456789,999999999));
$fileObj = $databaseManager->posters_ex($sso_url, 'get_avatar.php', $post);
Header("Content-type: application/octet-stream");
Header("Accept-Ranges: bytes");
Header("Accept-Length: ".strlen($fileObj));
Header("Content-Disposition: attachment; filename=" . rand(123456789,999999999));
echo $fileObj
?>