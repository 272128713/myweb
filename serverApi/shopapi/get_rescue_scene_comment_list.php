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
include_once dirname(__FILE__) . "/service/repos_get_rescue_scene_info_by_rsid.php";
//获取参数
$params = array(array("ss",true),array("rsid",true),array("comment_id",false),array("page",false));
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

$userId = (int)$sessionArr['user_id'];

//获取子评论
if (isset($params['comment_id']) && !empty($params['comment_id'])) {

    $ids = getSonCommentIds($params['rsid'],$params['comment_id']);

    $author = getCommentById($params['comment_id']);

    if ($ids) {
        //rsort($ids,SORT_NUMERIC);
        $idsStr = implode(',',$ids);
//        echo $idsStr;
        $res = getRescueComments($params['rsid'],$idsStr);

        foreach ($res as $key => $row) {
            $sort[$key] = $row['id'];
            $res[$key]['receiver'] = get_receiver($row['high_rs_id']);
        }
        array_multisort($sort,SORT_DESC,$res);
    }

    $data = $res ? $res : array();
    //$data['author'] = $author;
    echo json_encode(array(
         'code' => '1',
         'msg' => '获取现场子评论成功。',
         'author' => $author,
         'result' => $data
    )); exit;
    //ErrCode::echoJson('1','获取现场子评论成功。',$data);
}


//获取顶级评论
$res = getRescueTopComments($params['rsid']);
$rescue = getRescueSceneInfoById($params['rsid']);

if(!empty($rescue) && ($rescue['uid'] == $userId)){
    $vv = changeRescueActiveState($params['rsid']);
}

ErrCode::echoJson('1','获取现场顶级评论成功。',$res);



function logger()
{
    return Logger::getLogger(basename(__FILE__));
}

function getRescueTopComments($rsid)
{
    $select = <<<T_ECHO
SELECT
	c.id,rs_id,b.user_name,c.user_id,c.`comment`,c.createDate,v.base_ver piv,v.image_ver pav,c.high_rs_id
FROM
	rescue_scene_comment_info AS c
LEFT JOIN sky_user_data_master.user_base_info AS b ON c.user_id = b.user_id
LEFT JOIN sky_user_data_master.user_version_info AS v ON c.user_id = v.user_id
WHERE
	rs_id = $rsid AND high_rs_id = 0
ORDER BY createDate DESC;
T_ECHO;

//echo $select;
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $data = $db->getAll($select);
    //添加子评论数
    if ($data) {
        foreach ($data as $index => $row) {
            $data[$index]['comment_number'] = (string) count(getSonCommentIds($rsid,$row['id']));
        }
    }
    return $data;

}

function getRescueComments($rsid,$ids)
{

     $sql = <<<T_ECHO
SELECT
	c.id,rs_id,b.user_name,c.user_id,c.`comment`,c.createDate,v.base_ver piv,v.image_ver pav,c.high_rs_id
FROM
	rescue_scene_comment_info AS c
LEFT JOIN sky_user_data_master.user_base_info AS b ON c.user_id = b.user_id
LEFT JOIN sky_user_data_master.user_version_info AS v ON c.user_id = v.user_id
WHERE
    rs_id = $rsid AND id in($ids);
T_ECHO;

        //echo $sql;
        $dbObj = new DatabaseManager();
        $db = $dbObj->getConn();

        return $db->getAll($sql);

}

function get_receiver($Comment_id)
{

    $select = <<<T_ECHO
SELECT
	b.user_name as receiver
FROM
	rescue_scene_comment_info AS c
LEFT JOIN sky_user_data_master.user_base_info AS b ON c.user_id = b.user_id
LEFT JOIN sky_user_data_master.user_version_info AS v ON c.user_id = v.user_id
WHERE
	id = $Comment_id
ORDER BY createDate DESC;
T_ECHO;

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
    $data = $db->getCol($select);

    return $data[0];
}

function getSonCommentIds($rid,$pid,&$ids=array())
{

    $select = <<<T_ECHO
SELECT
	id
FROM
	rescue_scene_comment_info
WHERE
	high_rs_id = $pid AND rs_id = $rid
T_ECHO;

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    foreach ($db->getAll($select) as $comment_id) {
        $ids[] = $comment_id['id'];
        getSonCommentIds($rid,$comment_id['id'],$ids);
    }
//var_dump($ids);
    return $ids;

}

function changeRescueActiveState($rsid)
{

    $sql = <<<T_ECHO
UPDATE
    rescue_scene_info
SET
    active_state = '0'
WHERE
    id = $rsid;
T_ECHO;

    $dbObj = new DatabaseManager();

    $db = $dbObj->getConn();

    return $db->execute($sql);


}

function getCommentById($comment_id)
{
    $select = <<<T_ECHO
SELECT
	c.id,rs_id,b.user_name,c.user_id,c.`comment`,c.createDate,v.base_ver piv,v.image_ver pav,c.high_rs_id
FROM
	rescue_scene_comment_info AS c
LEFT JOIN sky_user_data_master.user_base_info AS b ON c.user_id = b.user_id
LEFT JOIN sky_user_data_master.user_version_info AS v ON c.user_id = v.user_id
WHERE
	c.id = $comment_id;
T_ECHO;
//echo $select;
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $data = $db->getRow($select);

    if ($data) {
        $data['receiver'] = get_receiver($data['high_rs_id']);
    }

    return $data;

}