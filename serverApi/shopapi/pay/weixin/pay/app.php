<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once "WxPay.AppPay.php";
require_once 'log.php';


$appPay=new AppPay;
$input = new WxPayUnifiedOrder();
$input->SetBody("test").rand();
$input->SetAttach("test");
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee("1");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://paysdk.weixin.qq.com/pay/notify.php");
$input->SetTrade_type("APP");
$result = WxPayApi::unifiedOrder($input);
/*$result['partnerid']=WxPayConfig::MCHID;
$result['prepayid']=$result['prepay_id'];
$result['package']='Sign=WXPay';
$result['timestamp']=time();
$result['sign']=2;
$result=array_intersect_key($result,array_flip(explode(" ","appid partnerid prepayid package noncestr timestamp sign")));*/
$appParam=$appPay->GetAppParameters($result);
echo json_encode($appParam)
?>