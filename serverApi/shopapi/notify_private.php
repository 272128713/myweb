<?php
//私人医生业务
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
require_once(dirname(__FILE__) . "/../1.0/common/inc.php");
// require_once(dirname(__FILE__) . "/common/inc.php");
// require_once(dirname(__FILE__) . "/../pay/alipay.config.php");
// require_once(dirname(__FILE__) . "/../pay/lib/alipay_notify.class.php");
$logger = Logger::getLogger(basename(__FILE__));

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();


if($verify_result) {//验证成功

    if($_POST['trade_status'] == 'TRADE_FINISHED') {

    	$listarr=array("notify_time", "notify_type", "notify_id", "sign_type", "sign", "out_trade_no", "subject", "payment_type", "trade_no", "trade_status", "seller_id", "seller_email", "buyer_id", "buyer_email", "total_fee", "quantity", "price", "body", "gmt_create", "gmt_payment", "is_total_fee_adjust", "use_coupon", "discount");
    	foreach ($listarr as $index){
    	
    		$objectArr[$index]=$_POST["$index"];
    	}
    	$body=$_POST['body'];
    	$out_trade_no=$_POST['out_trade_no'];
    	$out_trade_arr=explode("-", $out_trade_no);
    	$psid=$out_trade_arr[1];
    	$bodyarr=explode("-", $body);
    	$patientid=$bodyarr[0];
    	$auth=$bodyarr[1];
    	$databaseManager = new DatabaseManager();
    	$database = $databaseManager->getConn();
    	//数据库链接失败
    	if(!$database){
    		$logger->error(sprintf("Database connect fail."));
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR);
    	}
    	$get_doctor_sql = "SELECT user_id FROM doctor_home_set WHERE psid = $psid";
    	$doctor_user_id=$database->getOne($get_doctor_sql); 
    	$result=$databaseManager->insertAlipayData_for_private($objectArr,$patientid,$psid,$auth);
    	if ($result){
    		echo "success";//给支付报返回
    		$logger->info("已经给支付宝返回成功标志");
    		$optData=array("uid"=>$patientid,"mn"=>$_POST["total_fee"],"did"=>$doctor_user_id,'st'=>3,'pm'=>1);
    		$databaseManager->poster(2, 'IaiSHService', 'privateService', $optData, true);
    	}
    	else 
    		$logger->error("交易处理失败，等待支付宝发送下一次数据");
    	
    	
    	$databaseManager->destoryConn();//释放数据库资源

    	//老徐的功能先注释掉
    	/*
    	if ($result){ //只有insertAlipayData_for_private执行返回true才执行下面的动作，防止 alipay的多次数据提交导致的插入多次记录
    	////////////////////为健康宝的数据库插入相关记录////////////////////////////
    	//给医生插入对应的数据

    	$databaseFit = $databaseManager->getFitConn();
    	if(!$databaseFit){
    		$logger->error(sprintf("无法打开健康宝数据库."));
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR);
    	}
    	$id_key=$_POST["trade_no"];
    	$amount=$_POST["total_fee"];
    	$datetime=date("Y-m-d H:i:s");
    	$fitresult=$databaseManager->insert_fitpay_data_for_private($id_key,$amount,$doctor_user_id,$datetime);
    	if (!$fitresult)
    		$this->logger->error("健康宝数据插入失败");
    	$databaseManager->destoryFitConn();//释放数据库资源
    	////////////////////////////////////////////////////////////////////
    	}
    	*/
    	
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
	
        logResult("TRADE_SUCCESS");
    }

}
else {
    //验证失败
    echo "fail";
    $logger->error("数据验签失败");
}
?>