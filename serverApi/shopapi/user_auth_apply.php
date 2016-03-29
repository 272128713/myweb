<?php
/*
 * 1.2.19. user_auth_apply.php （用户资质申请）
 */
    include(dirname(__FILE__) . "/common/inc.php");
	$config = new Config();
	$logger = Logger::getLogger(basename(__FILE__));
	$params = array(array("ss",true),array("auth",true));
	$params = Filter::paramCheckAndRetRes($_POST, $params);
	if(!$params){
		$logger->error(sprintf("ack params error. params is %s",v($_POST)));
		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);    //参数错误
	}
	
	$databaseManager = new DatabaseManager();
	$database = $databaseManager->getConn();
	//数据库链接失败
	if(!$database){
		$logger->error(sprintf("Database connect fail."));
		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	}	

	$session = $params["ss"];
	$sessionInfo = $databaseManager->checkSession($session);
	if(!$sessionInfo){
		$logger->error(sprintf("get sessionInfo is fail. Error session is [%s]",$session));
	    ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
	}	
	
	$post['user_id']= $sessionInfo["user_id"];  //申请人
	$post['auth'] = $params["auth"]; //申请的资质类型

	$config=new Config();
	$sso_url=$config->getConfig("sso_url");
	echo $databaseManager->posters_ex($sso_url, 'user_auth_apply.php', $post);
	

?>