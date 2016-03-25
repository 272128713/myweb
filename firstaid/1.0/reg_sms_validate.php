<?php
/**
* 注册pincde验证(短信验证，实现真正的注册)
*/ 
include(dirname(__FILE__) . "/common/inc.php");
$logger = Logger::getLogger(basename(__FILE__));
$databaseManager = new DatabaseManager();
$database = $databaseManager->getConn();
//数据库链接失败
if(!$database){
	$logger->error(sprintf("Database connect fail."));
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
$config=new Config();
$post=$_POST;
$post['reg_source']=4;//0 空中医院 1 健康宝  2 商城  3 电子健康档案 4 空中急救
$sso_url=$config->getConfig("sso_url");
$results = $databaseManager->posters_ex($sso_url,'reg_sms_validate.php',$post);
$result = json_decode($results,true);

if($result['code'] != '1'){
	echo  $results;
	exit();
}

$user_id = $result['result']['user_id'];
if(strlen($user_id)<10){
	
	$res = $databaseManager->addPrivilegeList($user_id);
	if($res){
		ErrCode::echoJson("1","注册成功");
	}else{
	
		ErrCode::echoJson("2","注册失败");
	}
	
}
?>