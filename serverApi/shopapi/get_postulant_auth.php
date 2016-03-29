<?php
/*
 * 1.7.4. get_postulant_auth.php （获取志愿者资质）
 */
include(dirname(__FILE__) . "/common/inc.php");
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("ss",true));
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}
$session = $params["ss"];
/* 验证该用户的省市代码是否正确
$num_exp = "[0-9]{6}";
if(!preg_match($num_exp, $username)){   //判断用户名是否为手机号码
	$logger->error(sprintf("the username format is error.username is %s",$username));
	ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
} */
$databaseManager = new DatabaseManager();
$database = $databaseManager->getConn();
//数据库链接失败
if(!$database){
	$logger->error(sprintf("Database connect fail."));
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
//检查session 是否正确
$sessionArr = $databaseManager->checkSession($session);
if (!$sessionArr){
    $databaseManager->destoryConn();
    $logger->error(sprintf("Session check is fail. Error session is [%s]", $session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION, 1);
}
$user_id=$sessionArr['user_id']; //从session中取出用户的id
$config=new Config();
$sso_url=$config->getConfig("sso_url");
$post['user_id']=$user_id;
echo  $databaseManager->posters_ex($sso_url, 'get_postulant_auth.php', $post);  //把数据发送到主服务器进行数据验证


?>

