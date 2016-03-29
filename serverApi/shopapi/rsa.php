<?php
// 执行日期：20140728150442
// body=HTC G5 谷歌G5 Google G5 先验货后付款 支票刷卡 易人在线&buyer_email=songdec@163.com&buyer_id=2088102022954238&discount=0.00&gmt_close=2014-07-28 15:00:48&gmt_create=2014-07-28 15:00:48&gmt_payment=2014-07-28 15:00:48&is_total_fee_adjust=N&notify_id=635a60427d59f962b289b09f40b4945b3a&notify_time=2014-07-28 15:04:43&notify_type=trade_status_sync&out_trade_no=072815002815106&payment_type=1&price=0.01&quantity=1&seller_email=sanlingyi2010@163.com&seller_id=2088501188653557&subject=HTC G5 谷歌G5 Google G5 先验货后付款 支票刷卡 易人在线&total_fee=0.01&trade_no=2014072838769923&trade_status=TRADE_FINISHED&use_coupon=N
// 执行日期：20140728150442
// key/alipay_public_key.pem
// 执行日期：20140728150442
// iPhgdN5ESBq4EJqmAKb5ETfWSdqAc/0jb8haaXGzZUiTo8Ljq7RExT55nbsHAGXO6I0DOlIqIro7vjNjP1c9LmqBNs2cKaHq2Lf+javvXM1udeILxGsCGBvOpDiAh0e25OW9c05zBrL5RYz7EaKxs0SWu78D/a2C8PGkoPIm/pE=
// 执行日期：20140728150442
require_once("lib/alipay_rsa.function.php");
$body="body=HTC G5 谷歌G5 Google G5 先验货后付款 支票刷卡 易人在线&buyer_email=songdec@163.com&buyer_id=2088102022954238&discount=0.00&gmt_close=2014-07-28 15:00:48&gmt_create=2014-07-28 15:00:48&gmt_payment=2014-07-28 15:00:48&is_total_fee_adjust=N&notify_id=635a60427d59f962b289b09f40b4945b3a&notify_time=2014-07-28 15:04:43&notify_type=trade_status_sync&out_trade_no=072815002815106&payment_type=1&price=0.01&quantity=1&seller_email=sanlingyi2010@163.com&seller_id=2088501188653557&subject=HTC G5 谷歌G5 Google G5 先验货后付款 支票刷卡 易人在线&total_fee=0.01&trade_no=2014072838769923&trade_status=TRADE_FINISHED&use_coupon=N";
$key_path="key/alipay_public_key.pem";
$sign="iPhgdN5ESBq4EJqmAKb5ETfWSdqAc/0jb8haaXGzZUiTo8Ljq7RExT55nbsHAGXO6I0DOlIqIro7vjNjP1c9LmqBNs2cKaHq2Lf+javvXM1udeILxGsCGBvOpDiAh0e25OW9c05zBrL5RYz7EaKxs0SWu78D/a2C8PGkoPIm/pE=";
$ret=false;
$ret=rsaVerify($body,$key_path,$sign);
var_dump($ret);
?>
