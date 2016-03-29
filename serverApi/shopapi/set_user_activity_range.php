<?php
/**
 * Func: 设置电子围栏
 * User: 王秀泽
 * Date: 2015/10/30
 * Time: 10:12
 * Last: 2015-10-30 11:46:28 设置围栏发送信息
 */
include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/RKMongo.php";
include_once dirname(__FILE__) . "/service/user_damoclean_serv.php";
include_once(dirname(__FILE__) . "/common/MecManager.php");

$logger = Logger::getLogger(basename(__FILE__));

#region 获取参数
$params = array(array("ss", true), array("uid", true),array("ar_msg",true));
$params = Filter::paramCheckAndRetRes($_POST, $params);

if (!$params) {
    $logger->error(sprintf("params error. params is %s", v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
}

$session = trim($params["ss"]);
$uid  = trim($params["uid"]);
$ar_msg = trim($params["ar_msg"]);

#endregion

#region 获取数据库连接
$config = new Config();
$databaseManager = new DatabaseManager();
$rkMongo = new RKMongo();

if (!$db = $databaseManager->getConn()) {
    $logger->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}

if (!$rkMongo=$rkMongo->connect()) {
    logger()->error("mongoDB connect error.");
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

$userid = (int)$sessionArr['user_id'];


#region 检查是否有主动监护关系并生效
if (!$s_id=isAuthorized($userid,$uid)) {
    ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_LAWLESS, 1);
}
#endregion

#region 存储围栏信息
$sql = <<<T_ECHO
REPLACE INTO `sky_first_aid`.`user_activity_range`(usid,range_info) VALUES('{$s_id}','{$ar_msg}');
T_ECHO;

if ($db->execute($sql)) {
    /** 消息推送 */
    $msgObj = array(
        "type" => "FAH",
        "ot" => "4",
        "src" => $userid,
        "srcm" => getTel($userid),
        "time" => time(),
        "longitude"=>'',
        "latitude"=>'',
        "pav"=> $databaseManager->getUserPav($userid)
    );
    $mecManager = new MecManager($userid,$msgObj,$uid);
    $mecManager->sendMessage();

    ErrCode::echoOkArr(1,'请求成功');
}else{
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}

#endregion