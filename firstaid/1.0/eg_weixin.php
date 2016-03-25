<?php
//微信支付范例
define('API_PATH', dirname(__FILE__));
define('WEIXIN_PATH',API_PATH .'/../pay/weixin/' );
require_once(WEIXIN_PATH . 'lib/WxPay.Api.php');
require_once (WEIXIN_PATH . 'pay/WxPay.AppPay.php');
include(dirname(__FILE__) . "/common/inc.php");

$logger = Logger::getLogger(basename(__FILE__));
$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if ('/' == substr($php_self, -1))
{
    $php_self .= 'index.php';
}

define('PHP_SELF', $php_self);


//参数校验
$params = array(array("ss",true),array("id",true));
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
    $logger->error(sprintf("params error. params is %s",v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

//过滤参数

$session = $params["ss"]; //用户sesssion
//session校验
$databaseManager = new DatabaseManager();
$database = $databaseManager->getConn();
if(!$database){
    $logger->error("database connect error.");
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
$sessionArr = $databaseManager->checkSession($session);
if($sessionArr){
    $userID= $sessionArr['user_id'];
}
else
{
    $databaseManager->destoryConn();
    $logger->error(sprintf("Session check is fail. Error session is [%s]",$session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}

$oid=$params['id'];
//取价格,商品名称
$sql = "select buy.price,info.goods_name from user_buy_goods_info AS buy LEFT JOIN first_aid_goods_info AS info ON info.id = buy.goods_id WHERE buy.order_nums = '$oid'";
$res = $database->getAll($sql);
//var_dump($res);
$total = 0;
$gname = '';
foreach ($res as $p) {
    $total = $total + $p['price'];
    $gname .= $p['goods_name'].',';
}
$gname = substr($gname,0,-1);
//
//var_dump($total);
//var_dump(substr($gname,0,-1));
//计算异步回调地址
$notify_url=base_url()."/pay/weixin/notify/"."notify_weixin.php";

$appPay=new AppPay;
$input = new WxPayUnifiedOrder();
$input->SetBody("空中急救商品".$gname."支付");//支付描述
$input->SetAttach("test");//附加数据  一般不用管
$input->SetOut_trade_no($oid); //商户订单号，我们自己的订单号
$input->SetTotal_fee($total*100); //商品总价  单位为分
$input->SetTime_start(date("YmdHis")); //开始时间
$input->SetTime_expire(date("YmdHis", time() + 600)); //过期时间
$input->SetGoods_tag("空中急救商品");//商品标签  暂时用不着
$input->SetNotify_url($notify_url); //异步回调地址
$input->SetTrade_type("APP");//默认的，不要修改
//$logger->error(v($input));
$result = WxPayApi::unifiedOrder($input);
//$logger->error(v($result));
$appParam=$appPay->GetAppParameters($result);
$json = array(
    'code' => 1,
    'msg' => '',
    'result' => $appParam

);
echo json_encode($json);




/*
 * 本文件使用的函数定义
 */
function base_url() {
    /* 协议 */
    $protocol = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $host = $_SERVER['HTTP_HOST'];
    }
    else
    {
        /* 端口 */
        if (isset($_SERVER['SERVER_PORT']))
        {
            $port = ':' . $_SERVER['SERVER_PORT'];

            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
            {
                $port = '';
            }
        }
        else
        {
            $port = '';
        }

        if (isset($_SERVER['SERVER_NAME']))
        {
            $host = $_SERVER['SERVER_NAME'] . $port;
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $host = $_SERVER['SERVER_ADDR'] . $port;
        }
    }

    return $protocol . $host . dirname(dirname(PHP_SELF));
}