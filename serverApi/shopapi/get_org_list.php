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
$sql = "select user_id from sky_user_data_master.user_base_info WHERE mobile ='15389249006'";
$resultuid = $dbMaster->getOne($sql);
$sql = "select org.id,org.user_id,org.name,org.build_time,org.features,org.logo_url,
(select count(*) from sky_organization_user_join_apply where org_id = org.id AND apply_state = 1) as sign_num,
(select count(*) from sky_organization_activity where org_id = org.id AND  apply_state = 1) as ac_num
from sky_organization_apply as org
WHERE org.apply_state = 1
ORDER BY org.user_id = '$resultuid' DESC ,org.user_id = '$userId' DESC, apply_time DESC
";
$result = $dbMaster->getAll($sql);
foreach ($result as $k=>$v){
    $list[$k] = $v;
    if($v['user_id']==$userId){
        $list[$k]['isMe'] = '1';
    }else{
        $list[$k]['isMe'] = '0';
    }
}
$result = $list;

ErrCode::echoJson('1','success',$result);

?>
































