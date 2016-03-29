<?php
session_start();
error_reporting(0);
include dirname(dirname(__DIR__)).'/common/php/common.php';
include dirname(dirname(__DIR__)).'/common/php/config.php';

$_POST['ss']=$_SESSION['ss'];
$result=httpRequest($URLHOST.'/firstaid/1.0/set_org_active.php',$_POST,'post');

echo $result;
//if($result->code==1){
//    if($result->result){
//        $result=$result->result;
//    }
//
//}