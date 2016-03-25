<?php
/**
 * Func: 监控人开启监控开关
 * User: 王秀泽
 * Date: 2015/10/29
 * Time: 14:29
 * Last:
 */

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/service/user_damoclean_serv.php";

$logger = Logger::getLogger(basename(__FILE__));

#region 获取参数
$params = array(array("ss", true),array("uid",true),array("flag",true));
$params = Filter::paramCheckAndRetRes($_POST, $params);

if (!$params) {
    $logger->error(sprintf("params error. params is %s", v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
}

$session = trim($params["ss"]);
$uid = trim($params["uid"]);
$flag = trim($params["flag"]);

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

$login_id = (int)$sessionArr['user_id'];

#region 更改开关状态
if (setOnOff($login_id,$uid,$flag)) {
    ErrCode::echoOk("操作成功",1);
}else{
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}

#endregion