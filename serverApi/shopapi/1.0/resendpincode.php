<?php
/*
 * 医信商城模块API
 * 1.0.1. register.php (注册)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("un",true));




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
$username=$params['un'];
$_POST['service_type'] = 1;
$res = '';
$num_exp = '/^1[034578][0-9]{9}$/';
if(!preg_match($num_exp,$username)){
    $logger->error(sprintf("user format error"));
    ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
}
//验证码重新获取找回

if($_POST['service_type']==1){

    $code=httpRequest('http://210.14.72.56/yixin/1.0/resend_sms.php.php',$_POST,'post');
    $logger->error(v($code));
    $arr=explode('=',trim($code));
    if($arr[1]!=0){
        $result=array(
            "code" => $arr[1],
            "msg" => "帐号已注册"
        );
        echo json_encode($result);
        die();
    }else{
        $result = array(
            "code" => $arr[1],
            "msg" => "注册成功"
            );
        echo json_encode($result);
        die();
    }
}
