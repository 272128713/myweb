<?php

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";

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

if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",trim($params["ss"])));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}

echo json_encode(array(
    'code' => 1,
    'msg' => "空中急救挺不错的，不仅可以监护全家人的安全，遇到紧急情况还能一键呼救每个家庭成员，推荐你下载 http://www.kfirstaid.com ，我们也会相互成为救命稻草。"
//    'msg' => "我在空中急救送了你一根救命稻草。我倒了你扶不扶？如果你愿意扶我，点击 http://www.kfirstaid.com/ 下载空中急救app，至少我们可以互相搀扶。"
));




//函数部分
function logger()
{
    return Logger::getLogger(basename(__FILE__));
}