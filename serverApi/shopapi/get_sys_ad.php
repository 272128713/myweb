<?php
/*
 * 1.2.25. get_sys_ad.php （获取系统广告）
 */
header("content-type:text/html; charset=utf-8");
include(dirname(__FILE__) . "/common/inc.php");
include(dirname(__FILE__) . "/service/get_sys_ad.php");
$logger = Logger::getLogger(basename(__FILE__));
//参数校验
$params = array(array("ss", true),array("type", true));
$params = Filter::paramCheckAndRetRes($_POST, $params);
if (!$params) {
	$logger->error(sprintf("params is err. params is %s", v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}
//获取参数
$session = $params["ss"];    //用户sesssion
$type=$params["type"];  //type：广告类型 (必填) 1 急救行动 2 慈善公益
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

$result= get_sys_ad($userID,$type)? get_sys_ad($userID,$type) : array();

$databaseManager->destoryConn();

ErrCode::echoOkArr("1","请求成功",$result);

?>