
<?php
include dirname(dirname(__DIR__)).'/common/php/common.php';
error_reporting(0);
session_start();
if(!isset($_GET['oid'])){
    die();
}
$_POST['ss']=$_SESSION['ss'];
$_POST['oid']=$_GET['oid'];
$result=httpRequest($URLHOST.'/firstaid/1.0/get_org_detail.php',$_POST,'post');

$result = json_decode($result);
//var_dump($result);
if($result->code ==1){
    $detail = $result->result;
//    var_dump($detail);
}