<?php
/*
 * 医信商城模块API
 * 1.0.1. register.php (注册)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("fasion",true),array("pin",true),array("pw",true));

$databaseManager = new DatabaseManager();
$database = $databaseManager->getConn();

if(!$database){
    $logger->error(sprintf("Database sky_first_aid connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
    $logger->error(sprintf("params error. params is %s",v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}
$fasion=$params['fasion'];
$_POST['ot'] = 1;
$res = '';
$num_exp = '/^1[034578][0-9]{9}$/';
if(!preg_match($num_exp,$fasion)){
    $logger->error(sprintf("user format error"));
    ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
}
//密码重设

if($_POST['ot']==1){
    $code=httpRequest('http://210.14.72.56/yixin/1.0/reset_pw_commit.php',$_POST,'post');
    $arr=explode('=',trim($code));
    if($arr[1]!=0){
        $result=array(
            "code" => $arr[1],
            "msg" => "重置失败，请重新操作"
        );
        $logger->error(v($arr[1]));
        echo json_encode($result);
        die();
    }else{
        $result = array(
            "code" => 1,
            "msg" => "重置成功"
            );
        echo json_encode($result);
        die();
    }
}
