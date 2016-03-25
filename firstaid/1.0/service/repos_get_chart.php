<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-12-28
 * Time: 下午4:43
 */

function getLevelNameByNum($num)
{
    $levelList = array(
        '0' => '暂无称号',
        '20' => '稻草才子',
        '50' => '稻草天子',
        '100' => '稻草圣人',
        '150' => '稻草仙人',
        '200' => '稻草真君',
        '300' => '稻草大帝',
        '500' => '稻草天尊',
        '1000' => '稻草超神'
    );

    $levelArr = array_keys($levelList);
    if($num < 21){
        return array(
            'levelName' => '暂无称号',
            'nextLevelNeed' => 21 - $num
        );
    }
    if($num > 1000){
        return array(
            'levelName' => '稻草超神',
            'nextLevelNeed' => 0
        );
    }
    foreach ($levelArr as  $index => $level) {
        if($level >= $num){
            $currentLevel = $levelArr[$index-1];

            if(0 == $level- $num){
                $l = 1;
            }else{
                $l = $level+1 - $num;
            }

            return array(
                'levelName' => $levelList[$currentLevel],
                'nextLevelNeed' => $l
            );
        }
    }

}

function getChatById($uid)
{
    $sql = <<<T_ECHO
SELECT nums FROM (
	SELECT
		base.user_id uid,base.user_name name, base.mobile,
		ver.base_ver piv,ver.image_ver pav,base.privilege_id
	FROM
		sky_user_data_master.user_base_info AS base
	JOIN
		sky_user_data_master.user_version_info AS ver
	ON
		base.user_id = ver.user_id) AS a
JOIN
(
	SELECT
		user_id uid,COUNT(user_id) nums
	FROM
		user_friend_list
	WHERE
	    user_id = $uid
	AND
	    is_friend = 1
	GROUP BY
		user_id


) AS b

ON
	a.uid = b.uid

T_ECHO;

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    try {

        return $db->getRow($sql);

    } catch (ErrorException $e) {
        echo json_encode(array(
            'code' => $e->getCode(),
            'msg' => $e->getMessage(),
            'result' => array()
        ));
    }


}