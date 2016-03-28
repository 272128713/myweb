<?php
/**
* 商城登录
*/ 
 	include(dirname(__FILE__) . "/common/inc.php");
	include(dirname(__FILE__) . "/common/TcpConnection.php");
	$logger = Logger::getLogger(basename(__FILE__));
	$params = array(array("un",true));
	//print_r($_POST);
	$params = Filter::paramCheckAndRetRes($_POST, $params);    
	if(!$params){
		$logger->error(sprintf("params error. params is %s",v($_POST)));
		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
	}
	
	$username = $params["un"];
	$num_exp = "/^1[034578][0-9]{9}$/"; 
	
	if(!preg_match($num_exp, $username)){   //判断用户名是否为手机号码
		$logger->error(sprintf("the username format is error.username is %s",$username));
		ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
	}
	
	$databaseManager = new DatabaseManager();
	$database = $databaseManager->getConn();
	//数据库链接失败
	if(!$database){
		$logger->error(sprintf("Database connect fail."));
		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	}
	
	//用户是否cun'z
	$user = $databaseManager->checkUsernameInvalid($username);
	if(!$user){
		$logger->error(sprintf("username is error. username is %s",$username));
		ErrCode::echoErr(ErrCode::API_ERR_PHONE_NO_EXSIT,1);
		
	}else{
		//检查是否存在角色
		$user_id = $user['member_id'];
		$mid=$user_id;
		$session = $databaseManager->createSession($user_id);   //创建session
		$push_service=2;
		//获取分配服务器信息 
		$serverInfo = $databaseManager->dispatchServerAndGetInfo($user_id);
		if(!$serverInfo){
			$logger->error(sprintf("get server info is fail. username is %s",$username));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		//更新session相关信息
		$sessionArr = array("client_type" =>1,
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
		//获取个人版本号
		$verInfo = $databaseManager->getInfoVersion($user_id);
		if(!$verInfo){  //获取个人信息版本失败，不返回错误。
			$logger->error(sprintf("get info version is fail. username is %s",$username));
		}
	}
	$echo_arr = array(
			"uid"=>$user_id,         		 					//用户uid
			"role"=>$user["user_type_id"],        				//用户角色
			"ss"=>$session,                  		 			//用户session
			"push_service_type"=>$push_service, 				//服务器支持的push类型
			"piv"=>$verInfo["base_ver"],						//个人信息版本号
			"pav"=>$verInfo["image_ver"],						//头像版本号
			"lps_server"=>$serverInfo['lps_ip'],    			//长链接服务器地址
			"lps_server_port"=>$serverInfo['lps_port'],    	 	//长链接服务器端口
			"mall_api_server"=>$serverInfo['api_ip'],    	 	//医信API服务器地址
			"mall_api_server_port"=>$serverInfo['api_port'], 	//长链接服务器端口
			"mall_news_server"=>$serverInfo['news_ip'],		//news服务器地址
			"mall_news_server_port"=>$serverInfo['news_port'], //news服务器端口
			"file_server"=>$serverInfo['file_ip'],				//头像上传/下载服务器的地址
			"file_server_port"=>$serverInfo['file_port']);		//文件服务器http下载的端口号
	ErrCode::echoJson(1,'登录成功',$echo_arr); 
				
?>