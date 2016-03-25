<?php
include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";

$logger = Logger::getLogger(basename(__FILE__));

#region 获取参数
$params = array(array("ss", true));
$params = Filter::paramCheckAndRetRes($_POST, $params);

if (!$params) {
    $logger->error(sprintf("params error. params is %s", v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
}

$session = trim($params["ss"]);
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

$uid = (int)$sessionArr['user_id'];


#region 删除所有监控请求
$sql = <<<T_ECHO
DELETE FROM `user_supervise_info` WHERE `type`=1 AND state=0 AND prey_id='{$uid}'
T_ECHO;

if ($db->execute($sql)) {
    ErrCode::echoOkArr('1','操作成功');
} else {
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}

#endregion