<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-26
 * Time: 下午3:54
 */

function getFriendsInfoFromUserBase($uid)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
    $sql = <<<T_ECHO
SELECT b.user_id,b.user_name,b.mobile FROM sky_user_data_master.user_base_info AS b
WHERE b.user_id = {$uid};
T_ECHO;

    if(!$result = $db->getRow($sql)){
        return false;
    }

    return json_encode(array(
        'code' => 1,
        'msg' => '数据获取成功',
        'result' => $result
    ));
}

function getFriendsInfoFromUserPersonal($uid)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
    $sql = <<<T_ECHO
SELECT
    b.user_id,b.user_name,b.mobile,p.birthday,p.blood_id,v.image_ver pav,v.base_ver piv,
    p.sex_id,p.birth_place,p.live_province_id,p.live_place,p.lifeclock
FROM
    sky_user_data_master.user_base_info AS b,
    sky_user_data_master.user_version_info AS v,
	sky_user_data_master.sky_user_extend_info AS p
WHERE
    b.user_id=p.user_id
    AND b.user_id=v.user_id
	AND b.user_id ={$uid};
T_ECHO;
    if(!$result = $db->getRow($sql)){
        return false;
    }

    return json_encode(array(
        'code' => 1,
        'msg' => '数据获取成功',
        'result' => $result
    ));
}