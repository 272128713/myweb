<?php
/**
* 下载MMS文件
* 处理MMS消息的下载
*/        
	include(dirname(__FILE__) . "/common/inc.php");
	include(dirname(__FILE__) . "/common/MMS_FileManager.php");	
	$logger = Logger::getLogger(basename(__FILE__));
	$params = array(array("ss",true),array("id",true),array("thumbnail",true));
	$params = Filter::paramCheckAndRetRes($_POST, $params);
	if(!$params){
		$logger->error(sprintf("params is err. params is %s",v($_POST)));
		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
	}
	$session = $params["ss"];	
	$databaseManager = new DatabaseManager();
	$database = $databaseManager->getConn();
	//数据库链接失败
	if(!$database){
		$logger->error(sprintf("Database connect fail."));
		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	}
	//session校验
	$sessionInfo = $databaseManager->checkSession($session);
	$databaseManager->destoryConn();   //断开主DB
	if(!$sessionInfo){
		$logger->error(sprintf("Session check is fail. Error session is [%s]",$session));
	    ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
	    
	}else{
		$fileId = $params["thumbnail"] == "1"?$params["id"] . "-thumb":$params["id"];
		//查找文件ID对应的fastDFS的信息
		$mmsFileManager = new MMS_FileManager();
	    $getFileResult = $mmsFileManager->downLoadFile($fileId);
	    if(!$getFileResult){
	        $logger->error(sprintf("file mms download error.mms_id:".$params["id"]));
	        ErrCode::echoErr(ErrCode::API_ERR_MMS_FILE_NO_EXSIT,1);
	    }
		$logger->info(sprintf("GET_MMS,MOBILE:".$sessionInfo["mobile"].",MMS-ID is %s" . $params["id"]));
		Header("Content-type: application/octet-stream");
	    Header("Accept-Ranges: bytes");
	    Header("Accept-Length: ".strlen($getFileResult));
	    Header("Content-Disposition: attachment; filename=" . rand(123456789,999999999));
	    echo $getFileResult;
	}
?>