<?php
/*
 * 空中急救模块API
 * 1.3.6. get_receive_list.php (接受稻草包列表)
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
$sql = "select base.user_name,buy.order_nums,buyla.createDate,SUM(price) as totalprice,COUNT(buy.order_nums) as count from user_buy_goods_largess_info as buyla
        LEFT JOIN user_buy_goods_info as buy
        ON buy.order_nums = buyla.order_nums
        LEFT JOIN sky_user_data_master.user_base_info as base
        ON base.user_id = buyla.accepter
        WHERE buyla.sender = '$userId'
        GROUP BY buyla.id
        ORDER BY buyla.createDate DESC ";
$result = $dbMaster->getAll($sql);
ErrCode::echoJson('1','success',$result);

?>
































