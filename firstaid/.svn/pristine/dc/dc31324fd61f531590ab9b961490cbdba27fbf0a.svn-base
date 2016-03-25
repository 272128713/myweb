<?php
/**
 * Func: 获取失联人的位置信息
 * User: 王秀泽
 * Date: 2015/10/29
 * Time: 11:20
 * Last: 2015-10-29 16:52:12
 */
include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/RKMongo.php";

$logger = Logger::getLogger(basename(__FILE__));

#region 获取参数
$params = array(array("ss", true),array("userid",true),array("page",false),array('pagesize',false));
$params = Filter::paramCheckAndRetRes($_POST, $params);

if (!$params) {
    $logger->error(sprintf("params error. params is %s", v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
}

$session = trim($params["ss"]);
$userid  = trim($params["userid"]);
$page = empty($params["page"]) ? 1 : trim($params["page"]);
$pagesize = empty($params['pagesize']) ? 20 : $params['pagesize'];

#endregion

#region 获取数据库连接
$config = new Config();
$databaseManager = new DatabaseManager();
$rkMongo = new RKMongo();

if (!$db = $databaseManager->getConn()) {
    $logger->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}
if(!$dbSso=$databaseManager->getSsoConn()){
    $logger->error(sprintf("Database sky_user_data_master connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
if (!$rkMongo->connect()) {
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

$uid = (int)$sessionArr['user_id'];

/** 失联人非法或者无效 */
$sql = <<<T_ECHO
SELECT user_id FROM sky_user_data_master.user_base_info WHERE user_id= "{$userid}";
T_ECHO;
if(!$dbSso->getOne($sql)){
    ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_LAWLESS, 1);
}
if($uid!=$userid){
    $sql=<<<T_ECHO
SELECT * FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$userid}" AND hunter_id="{$uid}" AND (TYPE=0 OR (TYPE=1 AND state=1))
T_ECHO;
    if(!$db->getOne($sql)){
        ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_LAWLESS, 1);
    }
}

#endregion

#region 获取当前个人GPS信息

if ($res=$rkMongo->getLinkmanLbsNow((int)$userid,null,$page,$pagesize)) {

    $arr=array()    ;
    foreach($res as $r){
        $arr[]=array('dt'=>$r['dt'],'gps'=>$r['gps']);
    }

    ErrCode::echoOkArr(1,'请求成功',$arr);
}else{
    ErrCode::echoOkArr(1,'请求成功',array());
}

#endregion