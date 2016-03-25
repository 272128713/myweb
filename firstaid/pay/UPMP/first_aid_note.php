<?php
//急救服务购买后银联回调
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
        //取价格,商品名称
        $sql = "select buy.price,info.goods_name from user_buy_goods_info AS buy LEFT JOIN first_aid_goods_info AS info ON info.id = buy.goods_id WHERE buy.order_nums = '$out_trade_no'";
        $res = $database->getAll($sql);
        $total = 0;
        foreach ($res as $p) {
            $total = $total + $p['price'];
        }

		if ((int)$tot_fee<(int)$total*100){
            $logger->error(v($tot_fee));
            $logger->error(v($total));
			$logger->info ( "实际支付的钱小于应该支付的钱，可能被HACK" );
			exit;
		}
		
		
		//更新支付状态
    	$sql = "update user_buy_goods_info set pay_type = 2,buy_time=now(),pay_time=now(),pay_state=1,order_state=1,pay_nums='$upmp_trade_no'
    			where order_nums ='$out_trade_no'";
    	$result = $database->execute($sql);
    	
		if ($result) {
			echo "success"; // 给支付报返回
			$logger->info ( "已经给银联返回成功标志" );
			$sql = "select goods_id from user_buy_goods_info  where order_nums = '$out_trade_no'" ;
			$rs = $database->getCol($sql);
            foreach($rs as $v){
                $sql = "update first_aid_goods_info set goods_stock = goods_stock-1,goods_sell_nums = goods_sell_nums+1
                        where id ='$v'";
                $res = $database->execute($sql);
            }
            //处理赠送关系表user_buy_goods_largess_info
            $sql = "select user_id,pay_user_id from user_buy_goods_info where order_nums = '$out_trade_no'";
            $payid = $database->getRow($sql);
            $sql = "insert into user_buy_goods_largess_info (order_nums,accepter,sender,createDate) VALUES ('$out_trade_no','".$payid['user_id']."','".$payid['pay_user_id']."',NOW())";
            $database->execute($sql);
	
		}else{ //业务系统内部错误，需要银联重传
			header('HTTP/1.1 500 Internal Server Error');
		}
	}else {
	}
	//echo "success";
}else {// 服务器签名验证失败
	$logger->info("签名失败");
	echo "fail";
}