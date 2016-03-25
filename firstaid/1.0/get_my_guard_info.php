<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/18 0018
 * Time: 17:29
 */
include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/service/user_damoclean_serv.php";
include_once dirname(__FILE__) . "/common/RKMongo.php";

$logger = Logger::getLogger(basename(__FILE__));

#region 获取参数
$params = array(array("ss", true));
$params = Filter::paramCheckAndRetRes($_POST, $params);

if (!$params) {
    $logger->error(sprintf("params error. params is %s", v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
}

$session = trim($params["ss"]);

#endregion

#region 获取数据库连接
$config = new Config();
$databaseManager = new DatabaseManager();

if (!$db = $databaseManager->getConn()) {
    $logger->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}

#endregion

#region 检查Session合法性
$sessionArr = $databaseManager->checkSession($session);

if (!$sessionArr || !$sessionArr['user_id']) {
    $databaseManager->destoryConn();
    $logger->error(sprintf("Session check is fail. Error session is [%s]", $session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION, 1);
}
#endregion

$uid = (int)$sessionArr['user_id'];

#region 获取本用户所关注的失联人信息

if ($data=getMyGuardInfo($uid)) {
    ErrCode::echoOkArr("1",'请求成功',$data);
}else{
    ErrCode::echoJson("1",'请求成功',array());
}

#endregion