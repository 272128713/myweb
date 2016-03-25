<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 16-1-19
 * Time: 上午11:22
 */

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/MecManager.php";

//获取参数
$params = array(array("ss",true),array("rsid",true),array("content",true),array("comment_id",false));
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


$userId = $sessionArr['user_id'];

//判断rsid是否存在
if(!is_exist_rescue($params['rsid'])){

    ErrCode::echoJson("0","救援现场不存在。");

}
if(isset($params['comment_id']) && !empty($params['comment_id'])){
    if(!is_exist_rescue_comment($params['comment_id'])){

        ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);

    }
}

$parent_id = isset($params['comment_id']) ? $params['comment_id'] : 0;



$res = add_rescue_comment(trim($params['rsid']),trim($params['content']),$sessionArr['user_id'],$parent_id);
//修改现场状态
if($parent_id == 0){
    activeRescueState(trim($params['rsid']));
}

if ($params['comment_id']) {
    $uid = getUserIdByCommentId($params['comment_id']);

    $acc = $databaseManager->getUserInfoByUid($uid);

    if($acc[0]['user_id'] != $userId){

        $mecManager = new MecManager($userId,
            array(
                "type" => "RSC",
                "src" => $userId,
                "srcm" => $sessionArr['mobile'],
                "time" => time(),
                "uuid" => uniqid(),
                "rsid" => $params['rsid'],
                "mcid" => getTopCommentId($params['rsid'],$params['comment_id']),
                "pav"  => getUserPav($acc[0]['user_id']),
                "name" => $sessionArr['rn'],
                "ct"  => mb_substr($params['content'],0,10,'UTF-8')
            ), $acc);

        if (!$mecManager->sendMessage()) {
            logger()->error("删除好友发送消息失败。");
        }
    }else{
        logger()->error("消息发送者和接收者不能是同一人。");
    }

}

if(!$res){
    ErrCode::echoJson("0","救援现场评论创建失败。");
}
ErrCode::echoJson("1","救援现场评论创建成功。");



function logger()
{
    return Logger::getLogger(basename(__FILE__));
}

function is_exist_rescue($rid)
{
    $sql = <<<T_ECHO
SELECT * FROM rescue_scene_info WHERE id = $rid;
T_ECHO;

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    return $db->getCol($sql) ? true : false;

}

function is_exist_rescue_comment($cid)
{
    $sql = <<<T_ECHO
SELECT * FROM rescue_scene_comment_info WHERE id = $cid;
T_ECHO;

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    return $db->getCol($sql) ? true : false;

}

function add_rescue_comment($rsid,$content,$uid,$pid)
{
    $sql = <<<T_ECHO
INSERT INTO
    rescue_scene_comment_info(rs_id,comment,createDate,user_id,high_rs_id)
VALUES
    ($rsid,"$content",NOW(),$uid,$pid);
T_ECHO;

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    return $db->execute($sql);

}


function activeRescueState($rsid)
{
    $sql = <<<T_ECHO
UPDATE
    rescue_scene_info
SET
    active_state = '1'
WHERE
    id = $rsid;
T_ECHO;

    $dbObj = new DatabaseManager();

    $db = $dbObj->getConn();

    return $db->execute($sql);

}


function getUserIdByCommentId($comment_id)
{
    $select = <<<T_ECHO
SELECT
	user_id
FROM
	rescue_scene_comment_info
WHERE
	id = $comment_id;
T_ECHO;
//echo $select;
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $data = $db->getCol($select);

    return $data[0];

}

function getUserPav($uid)
{
    $select = <<<T_ECHO
SELECT
	image_ver
FROM
	sky_user_data_master.user_version_info
WHERE
	user_id = $uid;
T_ECHO;
//echo $select;
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $data = $db->getCol($select);

    return $data[0];

}

function getTopCommentId($rid,$sid)
{

    $sql = <<<T_ECHO
SELECT
	id,high_rs_id
FROM
	rescue_scene_comment_info
WHERE
	id = $sid AND rs_id = $rid
T_ECHO;

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $res = $db->getRow($sql);
    $data['id'] = $res['id'];

    while($res['high_rs_id']){
        $id = $res['high_rs_id'];
        $sql = <<<T_ECHO
    SELECT
        id,high_rs_id
    FROM
        rescue_scene_comment_info
    WHERE
        id = $id AND rs_id = $rid
T_ECHO;
        $res = $db->getRow($sql);
    }

    return $res['id'];

}