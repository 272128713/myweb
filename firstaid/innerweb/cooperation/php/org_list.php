
<?php
include dirname(dirname(__DIR__)).'/common/php/common.php';
error_reporting(0);
session_start();
$num= 0;
if($_REQUEST['ss']){
    $ss=$_REQUEST['ss'];
    $_SESSION['ss']=$ss;
}
$_POST['ss']=$_SESSION['ss'];
$result=httpRequest($URLHOST.'/firstaid/1.0/get_org_list.php',$_POST,'post');

$result = json_decode($result);
//var_dump($result);
if($result->code == 1){
    $list = $result->result;
    $num = count($list);
}