<?php
/*
 * 医信商城模块API
 * 1.0.2. logindo.php (登录)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("un",true),array("pw",true));

//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}


$databaseManager = new DatabaseManager();
$database = $databaseManager->getConn();

if(!$database){
    $logger->error(sprintf("Database sky_first_aid connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}


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

//登录（会踢掉空中医院登录）
$code=httpRequest('http://210.14.72.56/yixin/1.0/login.php',$_POST,'post');
$arr=explode("\n",trim($code));
//$logger->error(v($arr));
$arroper = explode("=",$arr[0]);




if($arroper[1]!=0){
    $arr = array(
        'code'=>1014,
        'msg'=>'帐号密码错误'

    );
}else{


    $arrss = explode("=",$arr[3]);
    $_POST['ss']=$arrss[1];
    //获取个人信息
    $code=httpRequest('http://210.14.72.56/yixin/1.0/get_personal_info.php',$_POST,'post');
    $arrinfo=explode("\n",trim($code));
//    $arr=json_encode($arr);
    $logger->error(v($arrinfo));

    $arrinfo = explode("=",$arrinfo[1]);
    $yixinuname = $arrinfo[1];
    $yixinuname = urldecode($yixinuname);
    $sql = "select name,id from user_base_info WHERE phone = '$ac'";
    $res = $database->getRow($sql);
    if($res){
        $uname = $res['name'];
    }else{
        $sql = "insert into user_base_info (name,phone,pwd,regtime) values ('$yixinuname','$ac','$key',NOW())";
        $database->execute($sql);
        $uname = $yixinuname;

        $sql = "select name,id from user_base_info WHERE phone = '$ac'";
        $res = $database->getRow($sql);
    }


    $sql = "select user_id from user_session_info WHERE user_id = '".$res['id']."'";
    $ssin = $database->getOne($sql);
    if($ssin){
        $sql = "update user_session_info set session='".$arrss[1]."',last_get_session_date=NOW() where user_id = '".$res['id']."'";
        $database->execute($sql);
    }else{
        $sql = "insert into user_session_info (user_id,session,last_get_session_date) VALUES ('".$res['id']."','".$arrss[1]."',NOW())";
        $database->execute($sql);
    }


    $arr = array(
        'code'=>1,
        'msg'=>'登录成功',
        'result'=>$arr,
        'uname'=>$uname

    );

}
//$logger->error(v($arr));
echo json_encode($arr);
//生成session，token
die();


















