<?php
require_once dirname(__FILE__) ."/../lib/WxPay.Api.php";
require_once dirname(__FILE__) .'/../lib/WxPay.Notify.php';
require_once dirname(__FILE__) .'/../pay/log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();
        /*
         * {"appid":"wxa1dd713d50308b65","attach":"test","bank_type":"CFT","cash_fee":"1","fee_type":"CNY","is_subscribe":"N","mch_id":"1307497801","nonce_str":"2xjf3phv9np1tj36wekru99jskqdlnti","openid":"okjgav--VnLKjnITR4-TieZW_LnE","out_trade_no":"130749780120160120153058","result_code":"SUCCESS","return_code":"SUCCESS","sign":"3E7B2E9850572C9AF24B35D7B229E2CB","time_end":"20160120153123","total_fee":"1","trade_type":"APP","transaction_id":"1007021021201601202802731233"}
[2016-01-20 15:31:23][debug] query:{"appid":"wxa1dd713d50308b65","attach":"test","bank_type":"CFT","cash_fee":"1","fee_type":"CNY","is_subscribe":"N","mch_id":"1307497801","nonce_str":"H6ghmjnT9vB417Dv","openid":"okjgav--VnLKjnITR4-TieZW_LnE","out_trade_no":"130749780120160120153058","result_code":"SUCCESS","return_code":"SUCCESS","return_msg":"OK","sign":"B647DEA8D83008960EA7B26879A3DF38","time_end":"20160120153123","total_fee":"1","trade_state":"SUCCESS","trade_type":"APP","transaction_id":"1007021021201601202802731233"}
         */
        /*商户订单id:$data['out_trade_no']
         *支付总费，单位，分:$data['total_fee']
         *
         */
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            Log::DEBUG("输入参数不正确:缺少transaction_id" );
            return false;

        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            Log::DEBUG($msg );
            return false;
        }


        //处理回调

        include(dirname(__FILE__) . "/../../../1.0/common/MecManager.php");
        require_once (dirname ( __FILE__ ) . "/../../../1.0/common/inc.php");
        $logger = Logger::getLogger ( basename ( __FILE__ ) );
        header('Content-Type:text/html;charset=utf-8');


        //交易处理成功
        $out_trade_no = $data['out_trade_no']; //我们的业务订单号
        $weixin_trade_no = $data['transaction_id']; //银联的查询流水号
        $tot_fee = $data['total_fee'];
        $databaseManager = new DatabaseManager();
        $database = $databaseManager->getConn();
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
        if ($tot_fee<$total*100){
//            $databaseManager->errorReport($out_trade_no);
            $logger->info ( "实际支付的钱小于应该支付的钱，可能被HACK" );
            exit;
        }


        //更新支付状态
        $sql = "update user_buy_goods_info set pay_type = 3,buy_time=now(),pay_time=now(),pay_state=1,order_state=1,pay_nums='$weixin_trade_no'
    			where order_nums ='$out_trade_no'";
        $result = $database->execute($sql);

        if ($result) {
//            echo "success"; // 给支付报返回
            $logger->info ( "已经给微信返回成功标志" );
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

            Log::DEBUG("处理成功，给微信服务器回复成功" );
            return true;
        }else{

            Log::DEBUG("处理失败" );
            return false;
        }




    }
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
