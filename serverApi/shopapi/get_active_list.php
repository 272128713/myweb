<?php
/*
 * 空中急救模块API
 * 1.3.6. set_org_apply.php (获取机构list)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("ss",true));

//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

//获取参数
$ss = $params['ss'];


$databaseManager = new DatabaseManager();
$dbMaster = $databaseManager->getConn(); //连接sky_first_aid



//数据库链接失败

if(!$dbMaster){
	$logger->error(sprintf("Database sky_first_aid connect fail."));
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
//验证session{}
$sessionArr = $databaseManager->checkSession($ss);

if(!$sessionArr){
	$logger->error(sprintf(" Session fail."));
	ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);

}

$userId = (int)$sessionArr['user_id'];

//数据逻辑

//所有的活动
$sql = "select act.*,org.`name` as orgname,org.logo_url from sky_organization_activity as act
LEFT JOIN sky_organization_apply as org
ON org.id = act.org_id
 where act.apply_state=1 ORDER BY act.apply_time desc";
$result[0] = $dbMaster->getAll($sql);
//我的活动
$sql = "select act.*,org.`name` as orgname,org.logo_url from sky_organization_activity as act
LEFT JOIN sky_organization_apply as org
ON org.id = act.org_id
 where act.apply_state=1 and act.org_id in (select id from sky_organization_apply where user_id = '$userId') ORDER BY act.apply_time desc";
$result[1] = $dbMaster->getAll($sql);
ErrCode::echoJson('1','success',$result);

?>
































