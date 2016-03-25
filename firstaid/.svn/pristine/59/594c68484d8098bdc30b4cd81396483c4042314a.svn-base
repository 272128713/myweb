<?php

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";
//include_once dirname(__FILE__) . "/service/repos_get_chart.php";



//获取参数
$params = array(array("ss", true),array('uid',true),array('flag',true));

$params = Filter::paramCheckAndRetRes($_POST, $params);


if (!$params) {
    logger()->error(sprintf("params error. params is %s", v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
}

$config = new Config();
$databaseManager = new DatabaseManager();

//数据库链接
if (!$db = $databaseManager->getConn()) {
    logger()->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}

$session = trim($params["ss"]);
//session处理
$sessionArr = $databaseManager->checkSession($session);

//logger()->info(printf("this session is %s",$session));

//var_dump($params);exit;

if (!$sessionArr) {
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]", $session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION, 1);
}

$uid = $sessionArr['user_id'];


$res  = updateUserFriendState($uid,$params['uid'],$params['flag']);

//var_dump($res);

if(!$res){
    ErrCode::echoJson('0','用户好友状态更新失败。');
}

ErrCode::echoJson('1','用户好友状态更新成功。');




//函数部分
function logger()
{
    return Logger::getLogger(basename(__FILE__));
}

function updateUserFriendState($uid, $fid, $is_friend)
{
    $sqlL = <<<T_ECHO
UPDATE user_friend_list SET is_friend = $is_friend WHERE user_id = $uid AND friend_id = $fid;
T_ECHO;
    $sqlR = <<<T_ECHO
UPDATE user_friend_list SET is_friend = $is_friend WHERE user_id = $fid AND friend_id = $uid;
T_ECHO;
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    return $db->execute($sqlL) && $db->execute($sqlR);

}