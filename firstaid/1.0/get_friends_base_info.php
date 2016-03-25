<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-26
 * Time: 下午3:13
 */

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";
include_once dirname(__FILE__) . "/common/RKMongo.php";
include_once dirname(__FILE__) . "/service/repos_get_friends_base_info.php";
include_once dirname(__FILE__) . "/service/repos_get_chart.php";

//获取参数
$params = array(
    array("ss",true),array("flag",true),array('uid',false),array('sign',false)
);

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

$userId = (int)$sessionArr['user_id'];

//var_dump($userId);

//获取黑名单人员的基本信息
if(isset($params['sign']) && 1 == $params['sign'] ){
    $data = array();
    $data = $params['uid'] ?
        getBaseInfoListFromBlack($userId,$params['uid']) :
        getBaseInfoListFromBlack($userId);
    if($data){
        foreach($data as $k => $v){
            $data[$k]['gps'] = $rkMongo->getMyLbs(1,$v['uid']) ?:array();
        }
    }

    ErrCode::echoJson('1','success',$data);

}
//获取人员的基本信息(白+黑)
if(isset($params['sign']) && 2 == $params['sign'] ){
    $data = array();
    $data = $params['uid'] ?
        getBaseInfoList($userId,$params['uid']) :
        getBaseInfoList($userId);
    if($data){
        foreach($data as $k => $v){
            $data[$k]['gps'] = $rkMongo->getMyLbs(1,$v['uid']) ?:array();
        }
    }

    ErrCode::echoJson('1','success',$data);

}


//获取白名单人员的基本信息
if(isset($params['uid'])){
    $data = getAllFriendListByFriendId($userId,$params['uid']);
    if(!$data){
        ErrCode::echoJson('1','success',array());
    }
    foreach($data as $k => $v){
        $data[$k]['gps'] = $rkMongo->getMyLbs(1,$v['uid']);
    }

    ErrCode::echoJson('1','success',$data);
}else{

    $data = getAllFriendList($userId);
    if(!$data){
        ErrCode::echoJson('1','success',array());
    }
    foreach($data as $k => $v){
        $chat = getChatById($v['uid']);
        if($chat){
            $levelName = getLevelNameByNum($chat['nums']);
            $data[$k]['level'] = $levelName['levelName'];
        }
        $data[$k]['gps'] = ($rkMongo->getMyLbs(1,$v['uid'])) ? $rkMongo->getMyLbs(1,$v['uid']) : array();

    }

    ErrCode::echoJson('1','success',$data);

}

function logger()
{
    return Logger::getLogger(basename(__FILE__));
}