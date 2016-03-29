<?php
/*
 * 医信商城模块API
 * 1.0.1. goods_buy.php (商品预定)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("ss",false));



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
$ss=$params['ss'];
$res = '';
//校验session
$sql = "select user_id from user_session_info where `session` = '$ss'";
$sessionUid = $database->getOne($sql);
if(!$sessionUid){
    $logger->error(sprintf("session error"));
    ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}

//取对应用户的收货信息

$sql = "select * from shop_receive_address_info WHERE user_id = '$sessionUid' ORDER BY createDate DESC ";
$result = $database->getRow($sql);


if(!$result){
    $result = array();
}else{
    $area_code = $result['area_code'];
    $sql = "SELECT province.province,city.city,area.AREA from hat_area as area
left JOIN hat_city as city on city.cityID = area.father
LEFT JOIN hat_province as province on province.provinceID = city.father
where area.areaID = '$area_code'";
    $resultarea = $database->getRow($sql);
    $result['area'] = $resultarea['province'].' '.$resultarea['city'].' '.$resultarea['AREA'];
}
$logger->error(v($result));
ErrCode::echoJson('1','success',$result);














