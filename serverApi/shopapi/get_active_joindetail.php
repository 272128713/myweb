<?php
/*
 * 空中急救模块API
 * 1.3.6. get_active_joindetail.php (加入活动人员列表)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("ss",true),array("aid",true));

//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

//获取参数
$ss = $params['ss'];
$aid = $params['aid'];


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
$sql = "select aj.organization_user_join_id,aj.join_time,oj.name,oj.user_id from sky_organization_activity_user_join as aj
LEFT JOIN sky_organization_user_join_apply as oj
on oj.id = aj.organization_user_join_id
where organization_activity_id ='$aid'
ORDER BY aj.join_time DESC";



$result = $dbMaster->getAll($sql);
ErrCode::echoJson('1','success',$result);

?>
































