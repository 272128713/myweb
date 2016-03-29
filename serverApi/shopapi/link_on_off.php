<?php
include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";

//include_once dirname(dirname(__FILE__)) . "/service/link_on_off_serv.php";

$logger = Logger::getLogger(basename(__FILE__));

#region 获取参数
$params = array(array("ss", true), array("flag", true));
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

#region 获取失联联系人信息

/* 开启监护的联系人信息 */
/** SELECT * FROM sky_first_aid.user_supervise_info WHERE prey_id= "{$uid}" and (type=0 or (type=1 and state=1)) */
/* 被动联系人信息 */
/** SELECT * FROM sky_first_aid.user_supervise_info WHERE prey_id= "{$uid}" and type="0"; */


$sql = <<<T_ECHO
SELECT hunter_id,type,state FROM sky_first_aid.user_supervise_info WHERE prey_id= "{$uid}";
T_ECHO;

$data = $db->getAll($sql);

if (count($data) == 0) {
    ErrCode::echoErr(ErrCode::API_ERR_NO_ALERT_LINKMAN, 1);
}

#endregion

#region 更新失联开关状态
$sql = <<<T_ECHO
UPDATE sky_first_aid.user_privilege_list SET linkman_flag="{$flag}" WHERE user_id= "{$uid}";
T_ECHO;

if ($db->execute($sql)) {
    $arr=array();
    $arr['flag_status']=$flag;
    $arr['data']=$data;

    ErrCode::echoOkArr('1','操作成功',$arr);

} else {
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}

#endregion

$db->disConnect();
