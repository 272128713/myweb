<?php
/**
 * 1.3.10. get_cooperation_message.php（获取合作商家信息）
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
$sql = "select * from sky_cooperation_organization ";
$result = $dbMaster->getAll($sql);
foreach ($result as $k=>$v){
	$result_arr[$k] = array(

			'name'=>$v['name'],
			'summary'=>$v['summary'],
			'competency'=>$v['competency'],
			'url'=>$v['url'],
			'createDate'=>$v['createDate']
					
	);
}




	//关闭数据库连接
	$dbMaster->disConnect($dbMaster);
// 	$logger->info(v($dbSso->getLog()));
	
	

	ErrCode::echoOkArr(1,'ok',$result_arr);
?>
































