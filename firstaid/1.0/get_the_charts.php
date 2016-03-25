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

//logger()->info(printf("this session is %s",$session));

//var_dump($params);exit;

if (!$sessionArr) {
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]", $session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION, 1);
}

$uid = $sessionArr['user_id'];

$chatList = getChatListById($uid);

if ($chatList) {
    foreach ($chatList as $index => $chat) {
        $chatList[$index]['level'] = getLevelByNum($chat['nums']);
    }
}

ErrCode::echoJson('1', 'success', $chatList);

//函数部分
function logger()
{
    return Logger::getLogger(basename(__FILE__));
}


function getLevelByNum($num)
{

    /**
     * 0-19没有称号     获得下级称号还需20- （0~19）根稻草
    20-49稻草才子   获得下级称号还需50- （20~49）根稻草
    50-99稻草天子   获得下级称号还需100- （50~99）根稻草
    100-149稻草圣人   获得下级称号还需150- （100~149）根稻草
    150-199稻草仙人   获得下级称号还需200- （150~199）根稻草
    200-299稻草真君   获得下级称号还需300- （200~299）根稻草
    300-499稻草大帝   获得下级称号还需500- （300~499）根稻草
    500-999稻草天尊   获得下级称号还需1000- （500~999）根稻草
    1000以上稻草超神   你已超神，世间罕有
     */
    //$chatName = ['稻草童子', '稻草才子', '稻草天子', '稻草圣人', '稻草仙君', '稻草天尊'];

    if ($num > 1000) {
        return '稻草超神';
    } elseif ($num > 500) {
        return '稻草天尊';
    } elseif ($num > 300) {
        return '稻草大帝';
    } elseif ($num > 200) {
        return '稻草真君';
    } elseif ($num > 150) {
        return '稻草仙人';
    } elseif ($num > 100) {
        return '稻草圣人';
    } elseif ($num > 50) {
        return '稻草天子';
    }elseif ($num > 20) {
        return '稻草才子';
    } else {
        return '暂无称号';
    }

}

function getChatListById($uid)
{
    $sql = <<<T_ECHO
SELECT
	a.*, COUNT(user_friend_list.friend_id) nums
FROM
	(
		SELECT
			b.*
		FROM
			(
				SELECT
					friend_id
				FROM
					user_friend_list
				WHERE
					is_friend = 1
				AND user_friend_list.user_id = $uid
				UNION
					SELECT $uid AS friend_id
			) AS f
		LEFT JOIN (
			SELECT
				base.user_id uid,
				base.user_name name,
				base.mobile,
				ver.base_ver piv,
				ver.image_ver pav,
				base.privilege_id
			FROM
				sky_user_data_master.user_base_info AS base
			JOIN
			    sky_user_data_master.user_version_info AS ver
			ON
			    base.user_id = ver.user_id
		) AS b ON f.friend_id = b.uid
	) AS a
LEFT JOIN user_friend_list ON user_friend_list.user_id = a.uid
WHERE
	uid IS NOT NULL AND is_friend = 1
GROUP BY
	user_friend_list.user_id
ORDER BY
	nums DESC;
T_ECHO;

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    try {

        return $db->getAll($sql);

    } catch (ErrorException $e) {
        echo json_encode(array(
            'code' => $e->getCode(),
            'msg' => $e->getMessage(),
            'result' => array()
        ));
    }

}
