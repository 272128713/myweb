<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-26
 * Time: 下午4:44
 */

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";
include_once dirname(__FILE__) . "/common/RKMongo.php";
include_once dirname(__FILE__) . "/service/repos_find_near_friends.php";

//获取参数
$params = array(array("ss",true),array("type",true));

$params = Filter::paramCheckAndRetRes($_POST, $params);

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



//session处理
$sessionArr = $databaseManager->checkSession(trim($params["ss"]));

//var_dump($sessionArr);

if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",trim($params["ss"])));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}
//获取登录者的用户ID
$userId = $sessionArr['user_id'];
//var_dump($userId);
//获取登录者的地理位置信息
$myLbsArr = $rkMongo->getMyLbs(trim($params['type']),(int)$userId);
//var_dump($myLbsArr);
if(!$myLbsArr){
    ErrCode::echoJson(1,'No access to my location information');
}

//获取好友的地理位置信息
//todo: mongoDb中调用固有getLbs函数有问题,自己的UserId无法过滤掉
$friendsGpsArr = $rkMongo->getLbs($params['type'],$userId,$myLbsArr,array($userId));
//var_dump($friendsGpsArr['results']);
if(!$friendsGpsArr){
    ErrCode::echoJson(1,'Not match the location information to the nearby friends');
}
$nearbyUserList = array();
foreach ($friendsGpsArr['results'] as $key => $friend) {

    $uid = $friend['obj']['_id'];
    if($userId == $uid) continue;
    $nearbyUserList[$uid] = round($friend['dis'],2);
}

//$res = getFriendListAndInfoByUserId($nearbyUserList);

if(!getFriendListAndInfoByUserId($nearbyUserList)){
    ErrCode::echoJson('1','success',array());
}
ErrCode::echoJson('1','success',getFriendListAndInfoByUserId($nearbyUserList));



function logger()
{
    return Logger::getLogger(basename(__FILE__));
}