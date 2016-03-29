<?php
/*
 * 空中急救注册流程
 * 1.3.9. get_rescue_card.php（获取急救卡信息）
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$databaseManager = new DatabaseManager();


//数据库链接

if (!$dbMaster = $databaseManager->getConn()) {
	logger()->error(sprintf("Database connect fail."));
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}

$params = array(array("ss",true));

//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

//获取参数
$ss = trim($params["ss"]);     //session


//验证session{}
 $sessionArr = $databaseManager->checkSession($ss);

if(!$sessionArr){
	$logger->error(sprintf(" Session fail."));  
	ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
	
}
//ID
$userId = (int)$sessionArr['user_id'];

//数据交互
$sql = "select * from user_rescue_card_info where user_id = '$userId'";
$result = $dbMaster->getRow($sql);

$result_arr = array(
	"height" => $result['height'],
	"weight" => $result['weight'],
	"blood" => $result['blood'],
	"allergic_drug" => $result['allergic_drug'],
	"chronic_disease" => $result['chronic_disease'],
	"surgery" => $result['surgery']
);

$sql_man  = "select * from user_rescue_card_linkman_info where user_id = '$userId'";
$result_man = $dbMaster->getAll($sql_man);

foreach ($result_man as $k=>$v){
	$result_arr['man'][$k]=array(
		"linkman_name"=>$v['linkman_name'],
		"createDate"=>$v['createDate'],
		"phone"=>$v['phone'],
		"linkman_id"=>$v['linkman_id']
	);
	
}




	//关闭数据库连接
	$dbMaster->disConnect($dbMaster);
// 	$logger->info(v($dbSso->getLog()));
	
	

	ErrCode::echoOkArr(1,'ok',$result_arr);
?>
































