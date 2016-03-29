<?php

//健康宝充值处理
require_once (dirname ( __FILE__ ) . "/../../1.0/common/inc.php");
$logger = Logger::getLogger ( basename ( __FILE__ ) );
header('Content-Type:text/html;charset=utf-8');
require_once("lib/upmp_service.php");
//logResult(var_export($_POST,true));
$logger->info(var_export($_POST,true));
if (UpmpService::verifySignature($_POST)){// 服务器签名验证成功
	//请在这里加上商户的业务逻辑程序代码
	//获取通知返回参数，可参考接口文档中通知参数列表(以下仅供参考)
	$transStatus = $_POST['transStatus'];// 交易状态
	if (""!=$transStatus && "00"==$transStatus){
		// 交易处理成功
		$orderNumber=$_POST['orderNumber'];
		$databaseManager = new DatabaseManager();
		$database = $databaseManager->getConn();
		//数据库链接失败
		if(!$database){
			$logger->error(sprintf("Database connect fail."));
		}
		$result=$databaseManager->fit_setTmpOrder($orderNumber,2);
		if ($result==1){
			echo "success";//给支付报返回
			$logger->info("已经给银联返回成功标志");
		}elseif($result==2){
			$logger->error("订单".$out_trade_no."重复操作");
		}elseif($result==3){
			$logger->error("订单".$out_trade_no."交易处理失败，等待支付宝发送下一次数据");
		}
		 
		$databaseManager->destoryConn();//释放数据库资源
		   
		   
		
	}else {
	}
	//echo "success";
}else {// 服务器签名验证失败
	echo "fail";
}

?>