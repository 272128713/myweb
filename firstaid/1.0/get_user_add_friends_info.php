<?php

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";

//获取参数
$params = array(array("ss", true));

$params = Filter::paramCheckAndRetRes($_POST, $params);


if (!$params) {
    logger()->error(sprintf("params error. params is %s", v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
}

$config = new Config();
$databaseManager = new DatabaseManager();

//数据库链接
if (!$db = $databaseManager->getConn()) {
    logger()->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
}

$session = trim($params["ss"]);
//session处理
$sessionArr = $databaseManager->checkSession($session);

if (!$sessionArr) {
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]", $session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION, 1);
}

$uid = $sessionArr['user_id'];


$data = getUserInfoByIds($uid);

ErrCode::echoJson('1','获取用户添加好友信息列表成功。',$data);



//函数部分
function logger()
{
    return Logger::getLogger(basename(__FILE__));
}

function getUserInfoByIds($uid)
{
    $sql = <<<T_ECHO
SELECT
	a.id info_id,
	a.user_id uid,
	b.user_name name,
	v.base_ver piv,
	v.image_ver pav,
	a.createDate add_time
FROM
	sky_first_aid.user_add_friends_info AS a
LEFT JOIN sky_user_data_master.user_base_info AS b ON a.user_id = b.user_id
LEFT JOIN sky_user_data_master.user_version_info AS v ON a.user_id = v.user_id
WHERE
	a.friend_id = $uid
ORDER BY
	a.createDate DESC
T_ECHO;
    $dbObj = new DatabaseManager();

    $db = $dbObj->getConn();

    return $db->getAll($sql);

}