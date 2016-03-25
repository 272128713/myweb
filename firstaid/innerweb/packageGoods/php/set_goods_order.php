
<?php
include dirname(dirname(__DIR__)).'/common/php/common.php';
error_reporting(0);
session_start();
if($_REQUEST['ss']){
    $ss=$_REQUEST['ss'];
    $_SESSION['ss']=$ss;
}
$_POST['ss']=$_SESSION['ss'];
$orderid = $_POST['orderid'];
$orderid = substr($orderid,0,-1);
$_POST['orderid'] = $orderid;
$result=httpRequest($URLHOST.'/firstaid/1.0/set_goods_order.php',$_POST,'post');

echo $result;