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
        '0' => '稻草童子',
        '5' => '稻草才子',
        '20' => '稻草天子',
        '50' => '稻草圣人',
        '100' => '稻草仙君',
        '150' => '稻草天尊'
    );

    $levelArr = array_keys($levelList);

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