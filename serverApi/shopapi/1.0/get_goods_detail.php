<?php
/*
 * 医信商城模块API
 * 1.0.1. get_goods_detail.php (获取商品detail)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("gid",true));

//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

//获取参数
$gid = $params['gid'];


$databaseManager = new DatabaseManager();
$dbMaster = $databaseManager->getConn(); //连接sky_first_aid



//数据库链接失败

if(!$dbMaster){
	$logger->error(sprintf("Database sky_first_aid connect fail."));
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
//验证session{}
//$sessionArr = $databaseManager->checkSession($ss);
//$logger->error(v($sessionArr));
//if(!$sessionArr){
//	$logger->error(sprintf(" Session fail."));
//	ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
//
//}
//
//$userId = (int)$sessionArr['user_id'];

//数据逻辑

$sql = "select info.goods_name, info.goods_en_name, info.goods_price, info.goods_summary, info.goods_content, img.img_url
from shop_goods_info as info
 LEFT JOIN shop_goods_img_info AS img
 ON img.goods_id = info.id
 where info.id = $gid AND img.type = 0";
$result = $dbMaster->getAll($sql);
ErrCode::echoJson('1','success',$result);

?>
































