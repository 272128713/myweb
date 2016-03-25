<?php
//K服务预订处理
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
		//取出K服务级别，根据K服务级别找到对应的钱数
		$k_state=$database->getOne("SELECT k_state FROM k_user_buy_pre WHERE id='$out_trade_no'");
		$money=$databaseManager->getService($k_state);
		$money=$money['deposit'];
		$logger->info ( "需支付的钱".$money);
		if ($tot_fee<$money){
			$databaseManager->errorReport($out_trade_no);
			$logger->info ( "实际支付的钱小于应该支付的钱，可能被HACK" );
			exit;
		}
		$result = $databaseManager->k_async_purchase_confirm ( $out_trade_no, $upmp_trade_no );
		
		if ($result) {
			echo "success"; // 给支付报返回
			$logger->info ( "已经给支付宝返回成功标志" );
			// 将数据同步到健康宝
			$d = $database->getRow ( "SELECT id,userId,k_state,docId,district,sys_content,doc_content FROM k_user_buy WHERE id='" . $out_trade_no . "'" );
		
			$s = $databaseManager->getService ( $d ['k_state'] );
			$optDate = array (
					'id' => $d ['id'],
					'service_id' => $d ['k_state'],
					'user_id' => $d['userId'],
					'doctor_id' => $d ['docId'],
					'district' => $d ['district'],
			);
			// 预订K服务
		
			$databaseManager->optService ( 8, $optDate ,2);
			$databaseManager->destoryConn();
		}else{
			//业务系统内部错误，需要银联重传
			header('HTTP/1.1 500 Internal Server Error');
		}
	}else {
	}
	//echo "success";
}else {// 服务器签名验证失败
	echo "fail";
}