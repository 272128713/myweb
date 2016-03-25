<?php
/**
* 消息确认
*/ 
	include(dirname(__FILE__) . "/common/inc.php");
	include(dirname(__FILE__) . "/common/TcpConnection.php");
	$config = new Config();
	$logger = Logger::getLogger(basename(__FILE__));
	$challengeStep = false;	
	$params = array(array("ss",true),array("serial",true));
	$logger->debug(sprintf("Request params is %s",v($_POST)));
	//print_r($_POST);
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
	
	$uer_id= $sessionInfo["user_id"];
	$mec_ip = $sessionInfo["mec_ip"];
	$mec_port = $sessionInfo["mec_port"];
	$mid = $sessionInfo["mid"];
	
	$tcpConnection = new TcpConnection($mec_ip,$mec_port);
	if(!$tcpConnection->isConnected()){
		 $logger->error(sprintf("Connect to MEC server fail.mec ip=%s,port=%s",$mec_ip,$mec_port));
	     ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	}	
	//ACK MY_MID SERIAL
    $message = "ACK " . $mid . " " . $params["serial"] . "\n";
    $result = $tcpConnection->tcpSend($message);
	if(!$result){
		$logger->error(sprintf("Send message to MEC fail.message=%s",$message));
	    ErrCode::echoErr(ErrCode::API_ERR_MESSAGE_ACK_ERROR,1);
	} else {
	    $resArr = explode("\n", $result);
        $resCode = explode(" ",$resArr[0]);
        if($resCode[0] == "11003"){
            ErrCode::echoErr(ErrCode::API_ERR_ACK_HAVE_MESSAGE,1);
        }else{
            ErrCode::echoOk("MESSAGE_ACK_OK",1);
        }  
	}
?>