<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-30
 * Time: 上午11:14
 */

/**
 * @param $uid
 * @return array
 * @throws Exception
 */
function getHaveSuperPowerList($uid){

    $sql = <<<T_ECHO
SELECT
	b.user_id uid,
	b.user_name name,
	b.mobile,
	v.base_ver piv,
	v.image_ver pav,
	b.privilege_id,
	v.thumbnail_image_url,
	v.source_image_url,
	CASE
WHEN f.is_friend = 0 THEN
	2
WHEN f.is_friend IS NULL THEN
	0
ELSE
	1
END AS is_friend
FROM
	sky_user_data_master.user_base_info AS b
LEFT JOIN sky_user_data_master.user_version_info AS v ON b.user_id = v.user_id
LEFT JOIN user_friend_list AS f ON (
	f.user_id = $uid
	AND f.friend_id = b.user_id
)
WHERE
	b.mobile IN (
		SELECT
			mobile_contact.contact_number
		FROM
			sky_first_aid.mobile_contact
		WHERE
			user_id = $uid
	)
AND b.user_id <> $uid
ORDER BY
	CONVERT (b.user_name USING gbk);
T_ECHO;

    try {

        $dbObj = new DatabaseManager();
        $db = $dbObj->getConn();
        return $db->getAll($sql);

    } catch (ErrorException $e) {
        ErrCode::echoJson($e->getCode(),$e->getMessage());
    }

}