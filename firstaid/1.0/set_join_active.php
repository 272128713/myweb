<?php
/*
 * 空中急救模块API
 * 1.3.6. set_org_active.php (提交机构活动)
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
//判断是否是自己的活动
//$sql = "select user_id from sky_organization_apply where id = (select org_id from sky_organization_activity where id = '$aid')";
//$my_id = $dbMaster->getOne($sql);
//if($userId==$my_id){
//    ErrCode::echoJson('2','my org',array());
//}

//等待审批,报名
$sql = "select id from sky_organization_user_join_apply WHERE user_id = '$userId' AND org_id = (select org_id from sky_organization_activity where id = '$aid') and apply_state=0";
$resultid = $dbMaster->getOne($sql);
if($resultid){
    ErrCode::echoJson('7','join wait',array());
}
//判断是否是志愿者
$sql = "select user_id from sky_organization_user_join_apply where org_id = (select org_id from sky_organization_activity where id = '$aid') and apply_state =1";
$vl_id = $dbMaster->getCol($sql);
if(!in_array($userId,$vl_id)){
    ErrCode::echoJson('3','not vl',array());
}
//参与活动
    //人数已满
    $sql = "select people_nums from sky_organization_activity where id='$aid'";
    $people_num = $dbMaster->getOne($sql);
    $sql = "select count(id) from sky_organization_activity_user_join where organization_activity_id = '$aid'";
    $people_joined = $dbMaster->getOne($sql);
    if($people_joined>=$people_num){
        ErrCode::echoJson('4','full',array());
    }
    //活动过期
    $sql = "select activity_time_begin,activity_time_finish from sky_organization_activity WHERE id='$aid'";
    $time_in = $dbMaster->getRow($sql);
    if(time()>strtotime($time_in['activity_time_finish'])){

        ErrCode::echoJson('5','time out',array());
    }
    //已参与
    $sql = "select uapp.user_id from sky_organization_activity_user_join as uj
            LEFT JOIN sky_organization_user_join_apply as uapp
            on uapp.id = uj.organization_user_join_id
             WHERE uj.organization_activity_id = '$aid'";
    $joined=$dbMaster->getCol($sql);
    if(in_array($userId,$joined)){

        ErrCode::echoJson('6','already joined',array());
    }
//参加
//志愿者ID
$sql = "select id from sky_organization_user_join_apply WHERE user_id = '$userId' AND org_id = (select org_id from sky_organization_activity where id = '$aid')";
$ouid = $dbMaster->getOne($sql);
$sql = "insert into sky_organization_activity_user_join (organization_user_join_id, organization_activity_id, join_time) VALUES ('$ouid', '$aid', NOW())";
$result = $dbMaster->execute($sql);
if(!$result){

    $logger->error(sprintf("insert fail"));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
ErrCode::echoJson('1','success',array());

?>
































