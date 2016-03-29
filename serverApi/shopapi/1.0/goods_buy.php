<?php
/*
 * 医信商城模块API
 * 1.0.1. goods_buy.php (商品预定)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("un",true),array("rn",false),array("srn",true),array("stel",true),array("spro",true),array("saddr",true),array("gid",true),array("goods_num",true),array("price",true));
//un 手机号;rn 账户用户名;srn 收货人姓名; stel 收货人电话;spro 收货人省市区;saddr 收货人详细地址



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
$un=$params['un'];
$rn=$params['rn'];
$srn=$params['srn'];
$stel=$params['stel'];
$spro=$params['spro'];
$saddr=$params['saddr'];
$gid=$params['gid'];
$goods_num=$params['goods_num'];
$price=$params['price'];
$res = '';
$num_exp = '/^1[034578][0-9]{9}$/';
if(!preg_match($num_exp,$un)){
    $logger->error(sprintf("user format error"));
    ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
}
if(!preg_match($num_exp,$stel)){
    $logger->error(sprintf("user format error"));
    ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
}
//订货
$sql = "select id from user_base_info WHERE phone = '$un'";
$resuid = $database->getOne($sql);
if($resuid){
    //update
    $sql = "update user_base_info set name = '$rn' WHERE phone = '$un'";
    $resupdateuid = $database->execute($sql);
    if(!$resupdateuid){
        $logger->error(sprintf("update user error"));
        ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    }
}else{
    //插入用户名
    $sql = "insert into user_base_info (name,phone,regtime) VALUES ('$rn','$un',NOW())";
    $resinsertid = $database->execute($sql);
    if(!$resinsertid){
        $logger->error(sprintf("insert uid error"));
        ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    }
    //获取用户ID
    $sql = "select id from user_base_info WHERE phone = '$un'";
    $resuid = $database->getOne($sql);

}
//用户ID $resuid
//插入收货信息
$sql = "insert into shop_receive_address_info (user_id,area_code,address,createDate,receive_name,receive_phone) VALUES ('$resuid','$spro','$saddr',NOW(),'$srn','$stel')";
$resaddid = $database->execute($sql);
if($resaddid){
    $sql = "select last_insert_id() from shop_receive_address_info WHERE user_id = '$resuid'";
    $send_address_id = $database->getOne($sql);
}else{
    $logger->error(sprintf("address id error"));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR);
}
//shop_buy_goods_info 预定表
$order_nums = createNewID();
$sql = "insert into shop_buy_goods_info (user_id,goods_id,price,buy_time,numbers,order_nums,send_address_id) VALUES ('$resuid','$gid','$price',NOW(),'$goods_num','$order_nums','$send_address_id')";
$resbuy = $database->execute($sql);
if(!$resbuy){
    $logger->error(sprintf("buy error"));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR);
}else{
    ErrCode::echoJson('1','success',array('oid'=>$order_nums));
}


function createNewID() {
    $id = rand(100,199).strtotime("now")*rand(1,5);
    return $id;
}
