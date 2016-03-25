<?php
include './common.php';
include '../lnk.php';

$key=$_POST['pw'];
$ac=$_POST['un'];
$res="";
$num_exp = '/^1[034578][0-9]{9}$/';
$_POST['pushsvc']='APNS';
$_POST['ct']=1;


//echo json_encode($_POST);
//die();
if(!preg_match($num_exp,$ac)){
    $res['code'] = '1001';
    $res['result'] ='';
    echo json_encode($res);//帐号格式错误
    die();
}
$key = md5($key);


$code=httpRequest('http://210.14.72.56/yixin/1.0/login.php',$_POST,'post');
$arr=explode("\n",trim($code));
echo json_encode($arr);
die();





$sql="select * from user_base_info WHERE phone = $ac";
$result = $database->query($sql)->fetch();
if(!$result){
    $res['code'] = '001';
    $res['result'] ='';
    echo json_encode($res);//帐号不存在
}else{
    if($key != $result['pwd']){
        $res['code'] = '002';
        $res['result'] ='';
        echo json_encode($res);//密码不正确
    }else{
        $res['code'] = '000';
        $res['result'] = $result['name'];
        echo json_encode($res);//OK
    }
}