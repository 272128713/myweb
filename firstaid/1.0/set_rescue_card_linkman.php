<?php
/*
 * 空中急救注册流程
 * 1.3.8. set_rescue_card_linkman.php（设置急救卡联系人信息）
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

$params = array(array("ss",true),array("flag",true),array("linkman_id",false));

//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}



//获取参数
$ss = trim($params["ss"]);     //session
$flag = trim($params["flag"]);	 //0.添加   1.删除
$linkman_id = trim($params["linkman_id"]);	//联系人id 2005001,2005002,2005003
$link_dh = substr($linkman_id, -1);
if($link_dh==','){
	$linkman_id=substr($linkman_id,0,-1);
}


//查紧急联系人电话name
if(!$dbSso = $databaseManager->getSsoConn()){
	logger()->error(sprintf("Database sso connect fail."));
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
$sql_linkman = "select user_id,user_name,mobile from user_base_info where user_id in ($linkman_id)";
$linkman_arr = $dbSso->getAll($sql_linkman);
$dbSso->disConnect();

//验证session{}
 $sessionArr = $databaseManager->checkSession($ss);

 
 
 
 
if(!$sessionArr){
	$logger->error(sprintf(" Session fail."));  
	ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
	
}
//ID
$userId = (int)$sessionArr['user_id'];

//数据交互
$sql_sel = "select linkman_id from user_rescue_card_linkman_info where linkman_id in ($linkman_id)";

$result = $dbMaster->getCol($sql_sel);

$sql_sel_uid = "select id from user_rescue_card_linkman_info where user_id = '$userId'";
$result_uid = $dbMaster->getCol($sql_sel_uid);
$result_uid = implode(",", $result_uid);
if($flag==0){
// 	foreach ($linkman_arr as $k=>$v){
// 		$linkmanid = $v['user_id'];
// 		$phone = $v['mobile'];
// 		$linkman_name = $v['user_name'];
// 		$ff= 0;
// 		foreach ($result as $ki=>$vi){
// 			if($linkmanid == $vi){
// 				//添加记录
// 				$ff=1;
// 			}
// 		}
// 		if($ff==0){
// 			$sql_add = "insert into user_rescue_card_linkman_info
// 			(user_id,linkman_name,createDate,phone,linkman_id)
// 			values
// 			('$userId','$linkman_name',now(),'$phone','$linkmanid')";
// 			$link_man = $dbMaster->execute($sql_add);
// 		}
		
// 	}
	
	//全删后添加
	$sql_del = "delete from user_rescue_card_linkman_info where id in ($result_uid)";
	$del = $dbMaster->execute($sql_del);
	
	foreach ($linkman_arr as $k=>$v){
		$linkmanid = $v['user_id'];
		$phone = $v['mobile'];
		$linkman_name = $v['user_name'];
			$sql_add = "insert into user_rescue_card_linkman_info
			(user_id,linkman_name,createDate,phone,linkman_id)
			values
			('$userId','$linkman_name',now(),'$phone','$linkmanid')";
			$link_man = $dbMaster->execute($sql_add);
		}
		
	
	
}elseif($flag==1){

	if(!$result){
		$logger->error(sprintf("get linkman select fail."));  
		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	}
	$sql_del = "DELETE from user_rescue_card_linkman_info where linkman_id in ($linkman_id)";
	$link_man = $dbMaster->execute($sql_del);
	if(!$link_man){
		$logger->error(printf("do or undo linkman failed"));
		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	}
}

	//关闭数据库连接
	$dbMaster->disConnect($dbMaster);
// 	$logger->info(v($dbSso->getLog()));
	ErrCode::echoOk("ok",1);
?>
































