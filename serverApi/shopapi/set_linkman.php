<?php
/**
 * Func: 用户选择或者删除自己的救命稻草
 * User: 王秀泽
 * Date: 2015/10/29
 * Time: 11:20
 * Last: 2015-10-30 11:14:54 更新 增加推送消息
 * Last: 2015-10-30 14:57:26 更新 设置被动失联联系人为数组 提交使用‘,’分隔
 * Last: 2015-11-2 11:44:25 更新 设置被动联系人时 filterLinkMan过滤数据库中已存在的数据 如果全部存在 抛出错误4404
 * Last: 2015-11-2 12:55:43 更新 允许参数linkman为空 后做验证 【删除被动监控列表】
 * Last: 2015-12-21 14:50:05 更新 允许重复调用设置主动监控人接口 后调用的只发送通知
 * Last: 2015-12-22 11:37:10 更新 允许多人主动监控
 */
include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/service/user_damoclean_serv.php";
include_once(dirname(__FILE__) . "/common/MecManager.php");

$logger = Logger::getLogger(basename(__FILE__));

#region 获取参数
$params = array(array("ss", true), array("linkman",false), array("type",false), array("flag",false));
$params = Filter::paramCheckAndRetRes($_POST, $params);

if (!$params) {
    $logger->error(sprintf("params error. params is %s", v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
}

$session = trim($params["ss"]);
$type=trim($params["type"]);//1主动  0被动        【允许为空 默认为0 新增】
$flag = trim($params["flag"]);//1 删除 0 新增      【允许为空  默认为0 新增】
$linkman=trim($params["linkman"]);//联系人 单条    【在删除时允许为空】
if(!$type) $linkman=explode(',',$linkman); //如果为新增被动  则分割数组

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

/** 删除被动联系人*/
if($type==0&&$flag==0){
    $res = delMyHunter($uid);
}
#region 验证失联人有效性

/** 新增时失联人信息格式不符 */
if (!$flag && !is_numeric($linkman) && !is_array($linkman)) {
    ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_FORMAT, 1);
}

/** 失联人为自己 */
if ($linkman == $uid || in_array($uid,$linkman)) {
    ErrCode::echoErr(ErrCode::API_ERR_LINKMAN, 1);
}

/** 设置主动监护人时 失联人非法或者无效 (不存在用户)*/
if ($type && !isExistsUser($linkman)) ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_LAWLESS, 1);

/** 设置主动监护人时 失联人非法或者无效 (不存在好友关系)*/
if (!$flag && $type && !isExistsFriend($uid,$linkman)) ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_LAWLESS, 1);

///** 不为删除 && 设置主动监护人时 查询是否已设置主动监护人 */
//if (!$flag && $type && hasPosLinkMan($linkman)) ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_EXISTS, 1);

/** 不为删除 && 设置主动监护人时 查询是否已发送过请求 */
$isSent=false;
if (!$flag && $type && hasSendRequest($uid,$linkman)) $isSent=true;//ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_IS_REQUEST, 1);

//设置主动监控人时 检测是否已经被动监控此人
if(!$flag && $type && isLink($uid,$linkman)) ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_IS_LINK, 1);

/** 设置被动监护人时 检测失联人是否无效 【过滤已存在的条目防止重复插入】 */
if(!$flag && !$type){
    $linkman=filterLinkMan($uid,$linkman);
}
#endregion

#region 设置或删除联系人

if($isSent){
    $msgObj = array(
        "type" => "FAH",
        "ot" => "2",
        "src" => $uid,
        "srcm" => getTel($uid),
        "time" => time(),
        "longitude"=>'',
        "latitude"=>'',
        "pav"=> $databaseManager->getUserPav($uid)
    );
    $mecManager = new MecManager($uid,$msgObj,$databaseManager->getUserInfoByUid($linkman));
    $mecManager->sendMessage();

    ErrCode::echoOk('成功', 1);
}

if (setLinkMan($uid, $linkman, $type, $flag)) {
    /** 如果为设置被动联系人  则发送通知 */
    if (!$type && !$flag) {
        $msgObj = array(
            "type" => "FAH",
            "ot" => "1",
            "src" => $uid,
            "srcm" => getTel($uid),
            "time" => time(),
            "longitude"=>'',
            "latitude"=>'',
            "pav"=> $databaseManager->getUserPav($uid)
        );
        $mecManager = new MecManager($uid,$msgObj,$databaseManager->getUserInfoByUid($linkman));
        $mecManager->sendMessage();
    }

    /** 如果设置主动监控请求  则发送通知 */
    if($type && !$flag){
        $msgObj = array(
            "type" => "FAH",
            "ot" => "2",
            "src" => $uid,
            "srcm" => getTel($uid),
            "time" => time(),
            "longitude"=>'',
            "latitude"=>'',
            "pav"=> $databaseManager->getUserPav($uid)
        );
        $mecManager = new MecManager($uid,$msgObj,$databaseManager->getUserInfoByUid($linkman));
        $mecManager->sendMessage();
    }

    ErrCode::echoOk('成功', 1);
} else {
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}

#endregion
