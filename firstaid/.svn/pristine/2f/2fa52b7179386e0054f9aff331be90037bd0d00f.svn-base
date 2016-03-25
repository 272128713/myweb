<?php
/**
 * 1.7.5. get_public_welfare_activity_people.php (获取参加公益活动人员)
 */
	include(dirname(__FILE__) . "/common/inc.php");
	include(dirname(__FILE__) . "/service/get_public_welfare_activity_people.php");
	$config = new Config();
	$logger = Logger::getLogger(basename(__FILE__));
	$params = array(array("ss",true),array("activity_id",true));
	//print_r($_POST);
	$params = Filter::paramCheckAndRetRes($_POST, $params);
	if(!$params){
		$logger->error(sprintf("params error. params is %s",v($_POST)));
		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
	}
	
//获取参数
$session = $params["ss"];
$activity_id = $params["activity_id"];
$databaseManager = new DatabaseManager();
$dbMaster = $databaseManager->getConn(); //连接sky_first_aid
//数据库链接失败
if(!$dbMaster){
	$logger->error(sprintf("Database sky_first_aid connect fail."));
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
//验证session{}
$sessionArr = $databaseManager->checkSession($session);
if(!$sessionArr){
	$logger->error(sprintf(" Session fail."));
	ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);

}
$userId = (int)$sessionArr['user_id'];
//获取参加获取的人的基本信息
$result= getPublicWelfareActivityPeople($userId,$activity_id);
ErrCode::echoJson("1","请求成功",$result);

?>