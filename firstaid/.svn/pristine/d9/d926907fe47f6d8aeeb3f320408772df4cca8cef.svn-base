<?php
/**
* 处理消息的取得流程
*/ 

	include(dirname(__FILE__) . "/common/inc.php");
	include(dirname(__FILE__) . "/common/TcpConnection2.php");
	$logger = Logger::getLogger(basename(__FILE__));
	$params = array(array("ss",true));

	$params = Filter::paramCheckAndRetRes($_POST, $params);
	if(!$params){
		$logger->error(sprintf("params error. params is %s",v($_POST)));
		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER);
	}
	
	$databaseManager = new DatabaseManager();
	$database = $databaseManager->getConn();
	//数据库链接失败
	if(!$database){
		$logger->error(sprintf("Database connect fail."));
		ErrCode::echoErr(ErrCode::SYSTEM_ERR);
	}	

	$session = $params["ss"];
	$sessionInfo = $databaseManager->checkSession($session);
	
	if(!$sessionInfo){
		$logger->error(sprintf("Session check is fail. Error session is [%s]",$session));
	    ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION);
	}
	$uer_id= $sessionInfo["user_id"];
	$mec_ip = $sessionInfo["mec_ip"];
	$mec_port = $sessionInfo["mec_port"];
	$mid = $sessionInfo["mid"];
	
	//echo $mec_ip."-----".$mec_port;die();
	//更新当前账号最后取得消息的时间
	$databaseManager->updateLastGetDate($uer_id);
	$databaseManager->destoryConn();
	
	$tcpConnection = new TcpConnection2($mec_ip,$mec_port);
	if(!$tcpConnection->isConnected()){
		
         $logger->error(sprintf("Tcp connect fail.",$uer_id));
	     ErrCode::echoErr(ErrCode::SYSTEM_ERR);
	}	
  
	//GET MY_MID APPNAME
    $message = "FETCH " . $mid . " \n";
    $result = $tcpConnection->tcpSend($message);
    $resultArr = "";
    $resultFlag = true;
    if($result){
        $resArr = explode("\n", $result);
        $resCode = explode(" ",$resArr[0]);
        if($resCode[0] == "0" || $resCode[0] == "11002"){
            for($i = 1 ; $i < count($resArr) ;$i++){
                $res = explode("=",$resArr[$i]);
                if(!isset($res[1])) continue;
                $resultArr .= $res[0] . "=" . $res[1] . "\n";
            }            
        }else{
            $resultFlag = false;
        }
       
    }else{
        $resultFlag = false;
    }
	if(!$resultFlag){
	    ErrCode::echoErr(ErrCode::API_ERR_MESSAGE_GET_ERROR);
	} else {
	     ErrCode::echoOkArr__("获取消息成功",$resultArr);
	}
?>
