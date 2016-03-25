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
        Log::DEBUG("处理成功，给微信服务器回复成功" );
        return true;
    }
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
