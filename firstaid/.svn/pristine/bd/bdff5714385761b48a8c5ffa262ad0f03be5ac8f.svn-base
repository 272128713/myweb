<?php
//k服务购买业务
require_once ("alipay.config.php");
require_once ("lib/alipay_notify.class.php");
require_once (dirname ( __FILE__ ) . "/../1.0/common/inc.php");
$logger = Logger::getLogger ( basename ( __FILE__ ) );
// 计算得出通知验证结果
$alipayNotify = new AlipayNotify ( $alipay_config );
$verify_result = $alipayNotify->verifyNotify ();
$logger->info ( print_r ( $_POST, true ) );

if ($verify_result) { // 验证成功
	
	if ($_POST ['trade_status'] == 'TRADE_FINISHED') {
		$out_trade_no = $_POST ['out_trade_no'];
		$ali_trade_no = $_POST ['trade_no'];
		$tot_fee=$_POST ['total_fee'];
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
		$result = $databaseManager->k_async_purchase_confirm ( $out_trade_no, $ali_trade_no );
		
		if ($result) {
			echo "success"; // 给支付报返回
			$logger->info ( "已经给支付宝返回成功标志" );
			// 将数据同步到健康宝
			$d = $database->getRow ( "SELECT id,userId,k_state,docId,district,sys_content,doc_content FROM k_user_buy WHERE id='" . $out_trade_no . "'" );
			
			$s = $databaseManager->getService ( $d ['k_state'] );
			//记录操作
			$databaseManager->save_h_k_user_handle_info($d['userId'],1,$out_trade_no);
			// 预订K服务
			//徐航修改 支付宝预定K服务成功以后，调用健康宝的接口进行后续操作；
			$optData=array(
				'id'=>$out_trade_no,
				'pm'=>1,
			);
			$databaseManager->poster(2, 'IaiSHService', 'serviceBook', $optData, true);
			
			/* $optDate = array (
			 'id' => $d ['id'],
					'service_id' => $d ['k_state'],
					'user_id' => $d['userId'],
					'doctor_id' => $d ['docId'],
					'district' => $d ['district'],
			); */
			//$databaseManager->optService ( 8, $optDate ,1);
		} else {
			$logger->error ( "交易处理失败，等待支付宝发送下一次数据" );
		}
		
		$databaseManager->destoryConn (); // 释放数据库资源
	} 
} else {
	// 验证失败
	echo "fail";
	$logger->error ( "数据验签失败" );
}
?>