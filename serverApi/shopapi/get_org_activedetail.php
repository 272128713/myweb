<?php
/*
 * 空中急救模块API
 * 1.3.6. get_org_activelist.php (获取活动list)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("ss",true),array("org_id",true),array("active_id"),false);

//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

//获取参数
$ss = $params['ss'];
$org_id = $params['org_id'];
if($params['active_id']){
    $active_id = $params['active_id'];
}else{
    $active_id = "";
}


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

//获取机构名称
$sql = "select user_id from sky_organization_apply WHERE id='$org_id'";
$resultid = $dbMaster->getRow($sql);
if($resultid['user_id']!=$userId){
    $logger->error(sprintf(" fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}else{
    $sql = "select * from sky_organization_activity WHERE org_id = '$org_id' AND id='$active_id'";


//    $logger->error(v($sql));
    $result = $dbMaster->getRow($sql);
    ErrCode::echoJson('1','success',$result);
}

?>































