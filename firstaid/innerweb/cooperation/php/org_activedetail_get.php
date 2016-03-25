<?php
session_start();
error_reporting(0);
include dirname(dirname(__DIR__)).'/common/php/common.php';
include dirname(dirname(__DIR__)).'/common/php/config.php';

$_POST['ss']=$_SESSION['ss'];
$_POST['org_id']=$_GET['oid'];
if($_GET['aid']){
    $_POST['active_id']=$_GET['aid'];
}
$result=httpRequest($URLHOST.'/firstaid/1.0/get_org_activedetail.php',$_POST,'post');
$result = json_decode($result);
//var_dump($result);
if($result->code==1){
    if($result->result){
        $result=$result->result;

    }

}