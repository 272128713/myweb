<?php
/*
 * 医信商城模块API
 * 1.0.1. register.php (注册)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("un",true),array("pin",false),array("pw",true),array("type",true));




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
$pwd=$params['pw'];
$pin=$params['pin'];
$_POST['rn']="用户".rand(10001,99999);
$rn = $_POST['rn'];
$_POST['ct']=1;
$_POST['role']=1;
$res = '';
$num_exp = '/^1[034578][0-9]{9}$/';
if(!preg_match($num_exp,$username)){
    $logger->error(sprintf("user format error"));
    ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
}
$pwd_exp = '/^\w{8,20}$/';
if(!preg_match($pwd_exp,$pwd)){
    $logger->error(sprintf("user format error"));
    ErrCode::echoErr(ErrCode::API_ERR_PAY_ACCOUNT_OR_PASSWD,1);
}
//注册
if($params['type']==2){
    $pin_exp = "/^[0-9]{6}$/";
    if(!preg_match($pin_exp,$pin)){
        $result = array(
            "code"=>"004",
            "msg"=>"验证码错误"
        );
        echo json_encode($result);
        die();
    }
    $code=httpRequest('http://210.14.72.56/yixin/1.0/reg_sms_validates.php',$_POST,'post');
    $arr=explode('=',trim($code));
    $logger->error(v($arr[1]));
    if($arr[1]==0){
        $pwd = md5($pwd);
        //如果网页上支付了订单，则update base_info
        $sql = "select id from user_base_info WHERE phone = '$username'";
        $resuid = $database->getOne($sql);
        if($resuid){
            $sql = "update user_base_info set pwd = '$pwd' where phone = '$username'";
            $resupdate = $database->execute($sql);
            if(!$resupdate){
                $logger->error(v($sql));
                ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
            }

        }else{
            $sql = "insert into user_base_info (name,pwd,phone,regtime,ip,province,city,mail) VALUES ('$rn','$pwd','$username',NOW(),'','','','')";
            $res = $database->execute($sql);
        }
    }
    if($res){
        $result = array(
          "code"=>1,
          "msg"=>"ok"
        );
        echo json_encode($result);
        die();
    }else{
        if($arr[1]==1001){
            $msg = "帐号格式不对";

        }elseif($arr[1]==1002){
            $msg = "帐号已经注册成功,请直接登录";

            $pwd = md5($pwd);
            $sql = "select id from user_base_info WHERE phone = '$username'";
            $resu = $database->getOne($sql);
            $logger->error(v($resu));
            if(!$resu){
                $sql = "insert into user_base_info (name,pwd,phone,regtime,ip,province,city,mail) VALUES ('$rn','$pwd','$username',NOW(),'','','','')";
                $res = $database->execute($sql);
            }
        }elseif($arr[1]==1007){
            $msg = "短信验证码错误";
        }elseif($arr[1]==1008){
            $msg = "短信验证码过期";
        }
        $result = array(
            "code"=>"005",
            "msg"=>$msg
        );
        echo json_encode($result);
        die();
    }
}
//获取验证码
else if($params['type']==1){

    $code=httpRequest('http://210.14.72.56/yixin/1.0/register.php',$_POST,'post');
    $arr=explode('=',trim($code));
    if($arr[1]!=1003){
        $result=array(
            "code" => $arr[1],
            "msg" => "注册失败"
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


























