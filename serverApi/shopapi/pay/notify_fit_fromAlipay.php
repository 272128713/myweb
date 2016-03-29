<?php
//健康宝充值处理   从支付宝充入健康宝
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
$logger->info(print_r($_POST,true));

if($verify_result) {//验证成功
	
    if($_POST['trade_status'] == 'TRADE_FINISHED') {
    	$out_trade_no=$_POST['out_trade_no'];
    	//$ali_trade_no=$_POST['trade_no'];
    	$databaseManager = new DatabaseManager();
    	$database = $databaseManager->getConn();
    	//数据库链接失败
    	if(!$database){
    		$logger->error(sprintf("Database connect fail."));
    	}
    	$result=$databaseManager->fit_setTmpOrder($out_trade_no,1);
    	if ($result==1){
    		echo "success";//给支付报返回
    		$logger->info("已经给支付宝返回成功标志");
    	}elseif($result==2){
    		$logger->error("订单".$out_trade_no."重复操作");
    	}elseif($result==3){
    		$logger->error("订单".$out_trade_no."交易处理失败，等待支付宝发送下一次数据");
    	}
       
    	$databaseManager->destoryConn();//释放数据库资源
    	
    	
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
	
        
    }

}
else {
    //验证失败
    echo "fail";
    $logger->error("数据验签失败");
}
?>