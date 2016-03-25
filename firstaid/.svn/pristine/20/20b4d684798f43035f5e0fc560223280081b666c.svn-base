<?php

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";
include_once dirname(__FILE__) . "/service/repos_get_chart.php";

//获取参数
$params = array(array("ss", true));

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

$chat = getChatById($uid);

if ($chat) {
    $chatData = getLevelNameByNum($chat['nums']);
    $chat['level'] = $chatData['levelName'];
    $chat['nextLevelNeed'] = $chatData['nextLevelNeed'];
}else{
    $chat['nums'] = 0;
    $chat['level'] = '稻草童子';
    $chat['nextLevelNeed'] = 6;
}

ErrCode::echoJson('1', 'success', $chat);

//函数部分
function logger()
{
    return Logger::getLogger(basename(__FILE__));
}

