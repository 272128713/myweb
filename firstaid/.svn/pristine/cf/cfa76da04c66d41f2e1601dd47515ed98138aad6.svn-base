
<?php
include dirname(dirname(__DIR__)).'/common/php/common.php';
error_reporting(0);
session_start();
if($_REQUEST['ss']){
    $ss=$_REQUEST['ss'];
    $_SESSION['ss']=$ss;
}
$_POST['ss']=$_SESSION['ss'];
$_POST['aid']=$_GET['aid'];
$result=httpRequest($URLHOST.'/firstaid/1.0/get_active_detail.php',$_POST,'post');

$result = json_decode($result);
if($result->code == 1){
    $result =$result->result;
//    var_dump($result);
}

$peoplelist = httpRequest($URLHOST.'/firstaid/1.0/get_active_joindetail.php',$_POST,'post');
$peoplelist = json_decode($peoplelist);
if($peoplelist->code == 1){
    $pl =$peoplelist->result;
//    var_dump($pl);
    $count = count($pl);
}