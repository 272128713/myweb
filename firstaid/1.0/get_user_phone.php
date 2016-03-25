<?php

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";
include_once dirname(__FILE__) . "/service/repos_get_user_phone.php";

//获取参数
$params = array(array("ss",true));

$params = Filter::paramCheckAndRetRes($_POST, $params);

if(!$params){
    logger()->error(sprintf("params error. params is %s",v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

$config = new Config();
$databaseManager = new DatabaseManager();
//数据库链接
if (!$db = $databaseManager->getConn()) {
    logger()->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}

//session处理
$sessionArr = $databaseManager->checkSession(trim($params["ss"]));

//var_dump($sessionArr);

if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",trim($params["ss"])));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}
//获取登录者的用户ID
$userId = $sessionArr['user_id'];

$res = getSuperList($userId) ? getSuperList($userId) : array();

if(getFriendsList($userId)){
    foreach (getFriendsList($userId) as $user) {
        array_push($res,$user);
    }
}

ErrCode::echoJson('1','success',$res);



function logger()
{
    return Logger::getLogger(basename(__FILE__));
}