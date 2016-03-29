<?php
include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/RKMongo.php";
include_once dirname(__FILE__) . "/service/user_damoclean_serv.php";

$logger = Logger::getLogger(basename(__FILE__));

#region 获取参数
$params = array(array("ss", true), array("uid", true));
$params = Filter::paramCheckAndRetRes($_POST, $params);

if (!$params) {
    $logger->error(sprintf("params error. params is %s", v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
}

$session = trim($params["ss"]);
$uid  = trim($params["uid"]);

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

$userid = (int)$sessionArr['user_id'];

#region 检查是否有主动监护关系并生效
if (!$s_id=isAuthorized($userid,$uid)) {
    die(ErrCode::getJson(ErrCode::API_ERR_LINKMAN_LAWLESS, 1));
}
#endregion

#region 获取围栏信息
if ($res=getUserActivity_range($s_id)) {
    $retarr=array();
    $retarr['code']=1;
    $retarr['msg']='请求成功';
    $retarr['result']=$res;
    echo json_encode($retarr);
}else{
//    ErrCode::echoOkArr(ErrCode::SYSTEM_ERR,'failed',"");
    ErrCode::echoJson(1,'请求成功','');
}

#endregion