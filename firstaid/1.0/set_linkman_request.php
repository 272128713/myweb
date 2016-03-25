<?php
/**
 * Func: 审批/驳回监控请求
 * User: 王秀泽
 * Date: 2015/10/30
 * Time: 10:12
 * Last: 2015-10-30 11:54:31 接受请求发送通知
 * Last: 2016-1-7 18:24:54 接受监控请求时 检查是否已有被动监控关系 有则删除请求不做通知 && 仅删除同一监护人的通知请求
 */
include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/service/user_damoclean_serv.php";
include_once(dirname(__FILE__) . "/common/MecManager.php");

$logger = Logger::getLogger(basename(__FILE__));

#region 获取参数
$params = array(array("ss", true),array("uid",true),array("flag",false));
$params = Filter::paramCheckAndRetRes($_POST, $params);

if (!$params) {
    $logger->error(sprintf("params error. params is %s", v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
}

$session = trim($params["ss"]);
$uid = trim($params["uid"]);
$flag = trim($params["flag"])?1:0;
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

$loginid = (int)$sessionArr['user_id'];


/** 如果有被动监控关系并点的确认 则删除 并不发送通知 */
$sql = <<<T_ECHO
SELECT * FROM `user_supervise_info` WHERE TYPE=0 AND prey_id= AND prey_id="{$loginid}" AND hunter_id="{$uid}"
T_ECHO;
if ($flag == 1 && $db->getAll($sql)) $flag = -1;

#region 审批/驳回请求
if (repLinkManRequest($loginid,$uid,$flag)) {
    /** 如果接受请求 发送通知 */
    if($flag==1){
            $msgObj = array(
                "type" => "FAH",
                "ot" => "3",
                "src" => $loginid,
                "srcm" => getTel($loginid),
                "time" => time(),
                "longitude"=>'',
                "latitude"=>'',
                "pav"=> $databaseManager->getUserPav($loginid)
            );
            $mecManager = new MecManager($loginid,$msgObj,$databaseManager->getUserInfoByUid($uid));
            $mecManager->sendMessage();
    }
    /** 清理其他请求 */
    clearLinkManRequest($loginid,$uid);
    ErrCode::echoJson(1, "请求成功", array('flag' => abs($flag), 'uid' => $uid));
} else {
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}

#endregion