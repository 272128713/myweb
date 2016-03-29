<?php
/*
 * 1.7.3. join_public_welfare_activity.php (参加公益活动)
 */



header("content-type:text/html; charset=utf-8");
include(dirname(__FILE__) . "/common/inc.php");
include(dirname(__FILE__) . "/service/join_public_welfare_activity.php");
// require_once dirname(__FILE__) .'/service/repos_save_rescue_scene_image.php';
$logger = Logger::getLogger(basename(__FILE__));
//参数校验
$params = array(array("ss", true),array("activity_id", true));
$params = Filter::paramCheckAndRetRes($_POST, $params);
if (!$params) {
	$logger->error(sprintf("params is err. params is %s", v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}
//获取参数
$session = $params["ss"];    //用户sesssion
$activity_id=$params["activity_id"];  //获取参数活动id
$databaseManager = new DatabaseManager();
$database = $databaseManager->getConn();
//数据库链接失败
if (!$database) {
	$logger->error("database connect error.");
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
//session校验
$sessionInfo = $databaseManager->checkSession($session);
if ($sessionInfo) {
	$userID = $sessionInfo['user_id'];
} else {
	$databaseManager->destoryConn();
	$logger->error(sprintf("Session check is fail. Error session is [%s]", $session));
	Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}
//实现业务逻辑区域

$result= joinPublicWelfareActivity($userID,$activity_id);
//$matchResult = $databaseManager->getDocPrivateServiceNum($userID);
$databaseManager->destoryConn();
if ($result == 1) {
	ErrCode::echoOkArr("5000","该用户没有志愿者资质");
}else if($result == 2){
	ErrCode::echoOkArr("5001","该活动过期了");
	
}else if($result == 3){
	
	ErrCode::echoOkArr("5002","该活动的人数已经满了");
}else if($result == 4){
	
	ErrCode::echoOk("操作成功",1);
}else if($result==5){
	ErrCode::echoOkArr("5003","不能重复参加!");
	
}


?>