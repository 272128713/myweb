<?php


function getFriendsList($uid)
{
    $sql = <<<ECHO
 SELECT
     base.mobile
 FROM
     sky_user_data_master.user_version_info AS info,sky_user_data_master.user_base_info AS base
 WHERE
     info.user_id in (
        SELECT
            friend_list.friend_id
        FROM
             sky_first_aid.user_friend_list AS friend_list
         WHERE
             friend_list.user_id = $uid
         WHERE
            friend_list.is_friend = 1
 )
 AND info.user_id = base.user_id;
ECHO;

    try {

        $dbObj = new DatabaseManager();
        $db = $dbObj->getConn();
        return $db->getAll($sql);

    } catch (ErrorException $e) {
        ErrCode::echoJson($e->getCode(),$e->getMessage());
    }

}


function getSuperList($uid)
{
    $sql = <<<ECHO
SELECT
    b.mobile

FROM
    sky_user_data_master.user_base_info AS b,
    sky_user_data_master.user_version_info as v
WHERE b.user_id = v.user_id
	AND mobile in (
	SELECT m.contact_number mobile
		FROM sky_first_aid.mobile_contact m
		WHERE m.user_id = $uid
		AND m.contact_number NOT in (
		    SELECT
		        mobile
		    FROM
		        sky_user_data_master.user_base_info
		    WHERE
		        user_id = $uid
		)
 	)
	AND b.user_id NOT in (
 		SELECT f.friend_id FROM sky_first_aid.user_friend_list as f WHERE f.user_id = $uid and f.is_friend = 1
 )
 ;
ECHO;

    try {

        $dbObj = new DatabaseManager();
        $db = $dbObj->getConn();
        return $db->getAll($sql);

    } catch (ErrorException $e) {
        ErrCode::echoJson($e->getCode(),$e->getMessage());
    }
}