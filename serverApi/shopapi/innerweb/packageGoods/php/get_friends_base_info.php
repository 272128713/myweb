
<?php
include dirname(dirname(__DIR__)).'/common/php/common.php';
error_reporting(0);
session_start();
if($_REQUEST['ss']){
    $ss=$_REQUEST['ss'];
    $_SESSION['ss']=$ss;
}
$_POST['ss']=$_SESSION['ss'];
$_POST['flag']= 2;
$result=httpRequest($URLHOST.'/firstaid/1.0/get_friends_base_info.php',$_POST,'post');
$result = json_decode($result);
//var_dump($result);

if($result->code == 1){
    $result = $result->result;
}