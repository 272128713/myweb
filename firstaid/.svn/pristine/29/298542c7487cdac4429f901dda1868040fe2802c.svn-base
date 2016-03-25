<?php
//预约业务
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
    		if ($index=="body"){
    			$objectArr[$index]=addslashes($_POST["$index"]);
    		}
    	}
    	$body=$_POST['body'];
    	$out_trade_no=$_POST['out_trade_no'];
    	$out_trade_arr=explode("-", $out_trade_no);
    	$wtid=$out_trade_arr[1];
    	$bodyarr=explode("-", $body);
    	$patientid=$bodyarr[0];
    	$auth=$bodyarr[1];
    	$reason=$bodyarr[2];
    	$reason=addslashes($reason);
    	$state=0;
    	$databaseManager = new DatabaseManager();
    	$database = $databaseManager->getConn();
    	function make_seed()
    	{
    		list($usec, $sec) = explode(' ', microtime());
    		return (float) $sec + ((float) $usec * 100000);
    	}
    	srand(make_seed());
    	$randval = rand(10000000,99999999);
    	$pscode=$randval;
    	//数据库链接失败
    	if(!$database ){
    		$logger->error(sprintf("Database connect fail."));
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR);
    	}
    	$doctoridSql="SELECT user_id FROM doctor_working_hours WHERE wtid=".$wtid;
    	$doctorid=$database->getOne($doctoridSql);
    	$result=$databaseManager->insertAlipayData($objectArr,$patientid,$wtid,$state,$reason,$pscode,$auth);
    	$logger->info(print_r($objectArr,true));
    	$logger->info(print_r($result,true));
        if ($result['result']){//只有成功才回传给阿里“success”
    		echo "success";
    		//执行成功才给医生打钱到健康宝
    		$optData=array("uid"=>$patientid,"mn"=>$_POST["total_fee"],"did"=>$doctorid,'st'=>2,'pm'=>1);
    		$databaseManager->poster(2, 'IaiSHService', 'privateService', $optData, true);
    	}
    	$databaseManager->destoryConn();//销毁主数据库

    	//老徐的功能先注释掉
    	/*
    	if ($result['result']){  //只有insertAlipayData执行返回true才执行下面的动作，防止 alipay的多次数据提交导致的插入多次记录
		
    	////////////////////为健康宝的数据库插入相关记录////////////////////////////
    	//给医生插入对应的数据
    	$databasefit=$databaseManager->getFitConn(); //获取健康宝数据库
    	if(!$databasefit ){
    		$logger->error(sprintf("无法打开健康宝数据库."));
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR);
    	}
    	$id_key=$_POST["trade_no"];
    	$amount=$_POST["total_fee"];
    	$datetime=date("Y-m-d H:i:s");
    	
    	$fitresult=$databaseManager->insert_fitpay_data($id_key,$amount,$doctorid,$datetime);
    	if (!$fitresult)
    		$this->logger->error("健康宝数据插入失败");
    	////////////////////////////////////////////////////////////////////
    	}
    	*/
    	
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {

        logResult("TRADE_SUCCESS");
    }

	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
   echo "fail";
   $logger->error("数据验签失败");
}
?>
