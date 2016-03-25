<?php

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";

//获取参数
$params = array(array("ss", true),array('info_id',true));

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

if (!$sessionArr) {
$databaseManager->destoryConn();
logger()->error(sprintf("Session check is fail. Error session is [%s]", $session));
Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION, 1);
}

$uid = $sessionArr['user_id'];

$res = deleteUserAddFriendsInfo($params['info_id']);

if(!$res){
    ErrCode::echoJson('0','用户添加好友信息删除失败。');
}

ErrCode::echoJson('1','用户添加好友信息删除失败。');

//函数部分
function logger()
{
    return Logger::getLogger(basename(__FILE__));
}

function deleteUserAddFriendsInfo($info_id)
{
    $sql = <<<T_ECHO
DELETE FROM user_add_friends_info WHERE id = $info_id
T_ECHO;

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    return $db->execute($sql);

}