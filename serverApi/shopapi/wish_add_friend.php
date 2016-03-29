<?php

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/MecManager.php";
include_once dirname(__FILE__) . "/service/repos_wish_add_friend.php";
include_once dirname(__FILE__) . "/service/repos_confirm_add_friend.php";

$params = array(array("ss",true),array("friend_type",true),
    array('uid',true),array('content',false));


$params = Filter::paramCheckAndRetRes($_POST, $params);

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

//session处理
$sessionArr = $databaseManager->checkSession(trim($params["ss"]));

//var_dump($sessionArr);

if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",trim($params["ss"])));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}
//获取登录者的用户ID
$userId = (int)$sessionArr['user_id'];


//flag = 1 用户同意添加到好友列表 默认值
//flag = 0 用户不同意添加到黑名单

// 先查询好友列表中是否有相关数据两条
if(!isFriend($userId,$params['uid'])){
    if (isset($params['flag']) && 0 == ($params['flag'])) {
        $result = addUserToBlackList($userId,$params['uid']);
        if(!$result){
            ErrCode::echoJson('0','黑名单添加失败。');
        }
        ErrCode::echoJson('1','黑名单添加成功。',array(
            'flag' => $params['flag'],
            'uid' => $params['uid']
        ));
    }
}


$result = addUserToFriendList($userId,$params['uid']);

// 添加用户信息
userAddFriendsInfo($userId,$params['uid']);


if(!($result)){
    ErrCode::echoJson('0','好友添加失败。');
}

try {

    $acc= $databaseManager->getUserInfoByUid(trim($params['uid']));
    $mecManager = new MecManager($userId,
        array("type" => "CAF",
            "ft"   => "1",
            "src"  => "$userId",
            "srcm" => $sessionArr['mobile'],
            "role" => $sessionArr['role'],
            "time" =>time()),$acc);
    if (!$mecManager->sendMessage()) {

        ErrCode::echoJson('0','发送消息失败。');

    }

    ErrCode::echoJson('1','发送消息成功。',array(
        'flag' => $params['flag'],
        'uid' => $params['uid']
    ));

} catch (ErrorException $e) {

    ErrCode::echoJson($e->getCode(),$e->getMessage());
}

function logger()
{
    return Logger::getLogger(basename(__FILE__));
}