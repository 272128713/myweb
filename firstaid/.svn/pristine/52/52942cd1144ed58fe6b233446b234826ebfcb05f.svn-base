<?php
/*
 * 空中急救模块API
 * 1.3.6. set_org_userstate.php (审核用户)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("ss",true),array("id",true),array("org_id",true),array("flag",true));
//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

//获取参数
$ss = $params['ss'];
$id = $params['id'];
$org_id = $params['org_id'];
$flag = $params['flag'];

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
if($flag == 1){
    //通过
    $sql = "update sky_organization_user_join_apply set apply_state = 1 WHERE id='$id' AND org_id = '$org_id' ";
}elseif($flag == 2){
    //拒绝
    $sql = "update sky_organization_user_join_apply set apply_state = 2 WHERE id='$id' AND org_id = '$org_id'";
}elseif($flag == 3){
    $sql = "delete from sky_organization_user_join_apply WHERE id='$id' AND org_id = '$org_id'";
}

$result = $dbMaster->execute($sql);
if(!$result){
    $logger->error(sprintf("error!"));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR);
}
ErrCode::echoJson('1','success',array());

?>
































