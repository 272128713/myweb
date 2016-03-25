<?php
	/* 
	 * Function : 修订个人健康历史信息
	 * Params: 	    ss：session
	 * 					add_number  addbk：新增通讯录，多个号码之间逗号分隔
	 * 					del_number  delbk: 删除通讯录，多个号码之间逗号分隔
	 */
	include(dirname(__FILE__) . "/common/inc.php");
	$logger = Logger::getLogger(basename(__FILE__));
	$params = array(array("ss",true),array("add_number",false),array("del_number",false),array("initial",false));
	$params = Filter::paramCheckAndRetRes($_POST, $params); 
	if(!$params){
		$logger->error(sprintf("params error. params is %s",v($_POST)));
		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
	}
	
	$session = $params["ss"];
//print_r($_POST);die();
    //session校验
	$databaseManager = new DatabaseManager();
	$database = $databaseManager->getConn();
	if(!$database){
	
    	$logger->error("database connect error.");
        ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    }
    $sessionArr = $databaseManager->checkSession($session);
    if($sessionArr){
		$userID= $sessionArr['user_id'];
		$databaseManager->destoryConn();
    }
	else
	{	
		$databaseManager->destoryConn();
	    $logger->error(sprintf("Session check is fail. Error session is [%s]",$session));
        Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
	}

	//通讯录格式校验规则
	function mobileRegex(&$mobileList)
	{
		$tmpMobileList = explode(',',$mobileList);
	    $count=0;
		$rule = "/^1[3|4|5|6|7|8]\d{9}$/";	
		foreach($tmpMobileList as $k=>$line)
		{
			if(preg_match($rule,$line))
			{
				$count ++;
			}else {//不匹配则 清除掉不匹配的的手机号码
				global $logger;
				$logger->info("不符合规则的手机号码为".var_export($line,true));
				unset($tmpMobileList[$k]);
			}
		}
		$mobileList=implode(",", $tmpMobileList);
		if (count($tmpMobileList)>0)
		{
			return true;
		}
		else
		{
			return false;	
		}
	}
	
    //上传通讯录格式校验
    if (!mobileRegex($params["add_number"])){
    	$logger->error(sprintf("addContacts format error."));
		$add_fromat_flag = false;
    }
	else
	{
		$add_fromat_flag = true;
	}
	//删除通讯录格式校验
    if(!mobileRegex($params["del_number"])){
    	$logger->error(sprintf("delContacts format error."));
		$del_fromat_flag = false;
    }
	else
	{
		$del_fromat_flag = true;
	}
	//如果上传和删除联系人格式错误返回1027
	if (!$add_fromat_flag && !$del_fromat_flag)
	{
		$logger->error("contact number format error.");
		ErrCode::echoErr(ErrCode::API_ERR_CONTACT_FORMAT,1);	
	}


	//联系人数据库连接
	$database = $databaseManager->getContactConn();
	if(!$database){
    	$logger->error("contact database connect error.");
        ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    }
   
	if (isset($params['initial'])){//登录的时候调用，清楚数据库现有的通讯录信息
		if ($params['initial']==1)
		$ret=$databaseManager->delAllContact($userID);
	}
	//如果通讯录格式正确,上传联系人	
	if ($add_fromat_flag){
		$addContactList = explode(',',$params["add_number"]);
		$addContactFlag = $databaseManager->addContact($userID,$addContactList);
	    if(!$addContactFlag){
			$upload_add_flag = false;
      		$logger->error(sprintf("Add Contact error. userID=%s",$userID));
		}
		else
		{
			$upload_add_flag = true;
		}
	}
    //如果上传删除联系人格式正确，进行删除
	if ($del_fromat_flag){
		$delContactList = explode(',',$params["del_number"]);
		$editContactFlag = $databaseManager->delContact($userID,$delContactList);
        if(!$editContactFlag){
			$upload_del_flag = false;
           	$logger->error(sprintf("Edit Contact error. userID=%s",$userID));
		}
		else
		{
			$upload_del_flag = true;
		}
    }
	//端口联系人数据库连接
	$databaseManager->destoryContactConn();
	//如果上传和删除通讯录错误返回1028
	if (!$upload_add_flag && !$upload_del_flag)
	{
		$logger->error("addContact or delContact error.");
		ErrCode::echoErr(ErrCode::API_ERR_CONTACT_UPLOAD,1);	
	}
	ErrCode::echoOk("ok",1);
    
?>