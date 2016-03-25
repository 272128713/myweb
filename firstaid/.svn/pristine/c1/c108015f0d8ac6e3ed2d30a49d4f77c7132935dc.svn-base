<?php
/**
* 获取自身profile,通常用于服务器赶人情况下使用。
*/ 
	include(dirname(__FILE__) . "/common/inc.php");
	$config = new Config();
	$logger = Logger::getLogger(basename(__FILE__));
	$params = array("ss",true);
	//print_r($_POST);
	$params = Filter::paramCheckAndRetRes($_POST, $params);    
	if(!$params){
		$logger->error(sprintf("params error. params is %s",v($_POST)));
		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
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
		$logger->error(sprintf("invalide session. session is %s",$session));
		ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
	}else{
		$user_id = $sessionInfo['user_id'];
		$push_service = $sessionInfo['push_service_type'];
		$mid = $sessionInfo['mid'];
		$client_type = $sessionInfo['client_type'];
		//获取分配服务器信息 
		$serverInfo = $databaseManager->dispatchServerAndGetInfo($user_id);
		if(!$serverInfo){
			$logger->error(sprintf("get server info is fail. username is %s",$username));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		//更新session相关信息
		$sessionArr = array("client_type" =>$client_type,
							"session" =>$session,
							"mid" =>$mid,
							"push_service_type" =>$push_service,
							"mec_ip" =>$serverInfo['mec_ip'],
							"mec_port" =>$serverInfo['mec_port'],
							"lps_ip" =>$serverInfo['lps_ip'],
							"lps_port" =>$serverInfo['lps_port']);	
	  	$addSession = $databaseManager->addSession($sessionArr,$user_id);
		if(!$addSession){
			$logger->error(sprintf("addSession is fail. username is %s",$username));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}	
	}
	$echo_arr = array(
			"lps_server"=>$serverInfo['lps_ip'],    			//长链接服务器地址
			"lps_server_port"=>$serverInfo['lps_port'],    	 	//长链接服务器端口
			"push_service_type"=>$push_service, 				//服务器支持的push类型
			"firstaid_api_server"=>$serverInfo['api_ip'],    	 	//医信API服务器地址
			"firstaid_api_server_port"=>$serverInfo['api_port'], 	//长链接服务器端口
			"firstaid_news_server"=>$serverInfo['news_ip'],		//news服务器地址
			"firstaid_news_server_port"=>$serverInfo['news_port'], //news服务器端口
			"file_server"=>$serverInfo['file_ip'],				//头像上传/下载服务器的地址
			"file_server_port"=>$serverInfo['file_port']);		//文件服务器http下载的端口号
	ErrCode::echoOkArr("1","操作成功",$echo_arr);
?>
