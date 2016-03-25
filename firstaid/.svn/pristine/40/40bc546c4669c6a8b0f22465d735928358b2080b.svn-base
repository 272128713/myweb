<?php
//专家组服务购买后银联回调
include(dirname(__FILE__) . "/../../1.0/common/MecManager.php");
require_once (dirname ( __FILE__ ) . "/../../1.0/common/inc.php");
$logger = Logger::getLogger ( basename ( __FILE__ ) );
header('Content-Type:text/html;charset=utf-8');
require_once("lib/upmp_service.php");
$logger->info(var_export($_POST,true));
if (UpmpService::verifySignature($_POST)){// 服务器签名验证成功
	$transStatus = $_POST['transStatus'];// 交易状态
	if (""!=$transStatus && "00"==$transStatus){
		//交易处理成功
		$out_trade_no = $_POST ['orderNumber']; //我们的业务订单号
		$upmp_trade_no = $_POST ['qn']; //银联的查询流水号
		$tot_fee=$_POST ['settleAmount'];
		$databaseManager = new DatabaseManager ();
		$database = $databaseManager->getConn ();
		// 数据库链接失败
		if (! $database) {
			$logger->error ( sprintf ( "Database connect fail." ) );
		}
		
		//检测实际支付金额和应该支付金额
		
		$need_pay=$database->getOne("SELECT price FROM eg_tmp_order WHERE id='$out_trade_no'");
		if ($tot_fee<$need_pay*100){
			$databaseManager->errorReport($out_trade_no);
			$logger->info ( "实际支付的钱小于应该支付的钱，可能被HACK" );
			exit;
		}
		$optData=array(
			'tmp_id'=>$out_trade_no,
			'pay_money'=>$tot_fee/100,
			'qn'=>$upmp_trade_no,
			'method'=>2 //2银联支付方式		
		);
		//将临时订单表数据更新到订单主表
		$result = $databaseManager->createMainOrder ($optData);
		if ($result) {
			echo "success"; // 给支付报返回
			$logger->info ( "已经给银联返回成功标志" );
	
		}else{ //业务系统内部错误，需要银联重传
			header('HTTP/1.1 500 Internal Server Error');
		}
	}else {
	}
	//echo "success";
}else {// 服务器签名验证失败
	echo "fail";
}