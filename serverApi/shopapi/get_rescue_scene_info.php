<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-23
 * Time: 上午9:01
 */


include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";
include_once dirname(__FILE__) . "/service/repos_get_rescue_scene_info_by_rsid.php";

//获取参数
$params = array(array("ss",true),array("rsid",true));

$params = Filter::paramCheckAndRetRes($_POST, $params);

//var_dump($params);exit;
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

$session = trim($params["ss"]);
$rsid = trim($params["rsid"]);

//session处理
$sessionArr = $databaseManager->checkSession($session);

//echo 'SessionArr返回值为:'.$sessionArr;

if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",$session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}
$userId = (int)$sessionArr['user_id'];

//更改现场状态 状态置零
$rescue = getRescueSceneInfoById($rsid);


//if(!empty($rescue) && ($rescue['uid'] == $userId)){
//    changeRescueActiveState($rsid);
//}

echo json_encode(array(
    'code' => 1,
    'msg' => '',
    'result' =>$rescue
));


//函数部分
function logger()
{
    return Logger::getLogger(basename(__FILE__));
}


