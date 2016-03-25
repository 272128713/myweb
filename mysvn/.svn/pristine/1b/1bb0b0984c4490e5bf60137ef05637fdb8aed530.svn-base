<?php
include 'common.php';
include '../lnk.php';
$username=$_POST['un'];
$pwd=$_POST['pw'];
$rn=$_POST['rn'];
$pin=$_POST['pin'];
$province = $_POST['province'];
$city = $_POST['city'];
$mail = $_POST['mail'];
$_POST['ct']=1;
$_POST['role']=1;
$ip = getIP();
$num_exp = '/^1[034578][0-9]{9}$/';
if(!preg_match($num_exp,$username)){
		echo 001;
		die();
}
$pwd_exp = '/^\w{8,20}$/';
if(!preg_match($pwd_exp,$pwd)){
		echo 002;
		die();
}
$rn_exp = "/^[\x{4e00}-\x{9fa5}]{1,20}$/u";
if(!preg_match($rn_exp,$rn)){
		echo 003;
		die();
}
if($_POST['type']==2){
 $pin_exp = "/^[0-9]{6}$/";
  if(!preg_match($pin_exp,$pin)){
		  echo 004;
		  die();
  }
  $code=httpRequest('http://210.14.72.56/yixin/1.0/reg_sms_validates.php',$_POST,'post');
  $arr=explode('=',trim($code));

    if($arr[1]==0){
        $pwd = md5($pwd);
        $sql = "insert into user_base_info SET name='$rn', pwd='$pwd', phone='$username',regtime=NOW(),ip='$ip',province='$province',city='$city',mail='$mail'";
        $res = $database->exec($sql);
    }
    if($res){
        echo $arr[1];
    }else{
        echo 005;
    }
}
//获取验证码
else if($_POST['type']==1){
  $code=httpRequest('http://210.14.72.56/yixin/1.0/register.php',$_POST,'post');
  $arr=explode('=',trim($code));

    echo $arr[1];
}



function getIP() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    }
    elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');

    }
    elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}