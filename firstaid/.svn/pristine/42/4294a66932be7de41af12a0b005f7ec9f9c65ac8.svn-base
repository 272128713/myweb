<?php
/**
 * 功能:获取医院信息
* 参数:
* ss：session（必填）
* section：区域代码（必填） 
*/
include(dirname(__FILE__) . "/common/inc.php");
include(dirname(__FILE__) . "/service/get_sys_hospital_info.php");
$logger = Logger::getLogger(basename(__FILE__));
//参数校验
$params = array(array("ss",true),array("section",true),array("type",false));
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER);
}
$session = $params["ss"]; //用户sesssion
$section =$params["section"];
if(isset($params["type"])){
	$type = $params["type"];
	if($type>2||$type<0){
		$type = 2;
	}
}else{
	$type = 2;
}
//session校验
$databaseManager = new DatabaseManager();
$database = $databaseManager->getConn();
if(!$database){
	$logger->error("database connect error.");
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
$sessionArr = $databaseManager->checkSession($session);
if($sessionArr){
	$userID= $sessionArr['user_id'];
}
else
{
	$databaseManager->destoryConn();
	$logger->error(sprintf("Session check is fail. Error session is [%s]",$session));
	Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}


//根据条件返回结果集
//$databaseManager->getCircleConn();
$arr=get_sys_hospital_info($section,$type);
if(is_array($arr)){
	ErrCode::echoOkArr("1","请求成功",$arr);
}else{
	$logger->error("get_sys_hospital_info error.");
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
