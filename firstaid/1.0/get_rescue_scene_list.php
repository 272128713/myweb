<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-21
 * Time: 下午5:25
 */

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";
include_once dirname(__FILE__) . "/common/RKMongo.php";
include_once dirname(__FILE__) . "/service/repos_get_rescue_scene_list.php";

//获取参数
$params = array(array("ss",true),array("flag",true),array('page',false));

$params = Filter::paramCheckAndRetRes($_POST, $params);

//var_dump($params);exit;
if(!$params){
    logger()->error(sprintf("params error. params is %s",v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

$config = new Config();
$databaseManager = new DatabaseManager();
$rkMongo = new RKMongo();
$rkMongo->connect();

//数据库链接
if (!$db = $databaseManager->getConn()) {
    logger()->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
if(!$rkMongo->connect()){
    logger()->error("mongoDB connect error.");
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);

}

$session = trim($params["ss"]);
$flag = trim($params["flag"]);

//session处理
$sessionArr = $databaseManager->checkSession($session);

//echo 'SessionArr返回值为:'.$sessionArr;

if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",$session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}
$userId = (int)$sessionArr['user_id'];


//我的GPS
$myGps = $rkMongo->getMyLbs('1',$userId);

//var_dump($myGps);

if(!isset($params['page'])){
    ErrCode::echoJson('1','success',getRescueList($flag,$userId,$myGps));
}

ErrCode::echoJson('1','success',getRescueList($flag,$userId,$myGps,trim($params["page"])));


//函数部分
function logger()
{
    return Logger::getLogger(basename(__FILE__));
}




