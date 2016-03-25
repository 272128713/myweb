<?php


function addUserToFriendList($userId,$fid)
{
    $sql = <<<T_ECHO
 INSERT INTO user_friend_list(user_id,friend_id,friend_type_id,auth_flag,is_friend,add_date)
    VALUES($userId,$fid,1,1,1,NOW()),($fid,$userId,1,1,1,NOW())
    ON DUPLICATE KEY UPDATE add_date=NOW();
T_ECHO;

    try {

        $dbObj = new DatabaseManager();
        $db = $dbObj->getConn();

        return $db->execute($sql);

    } catch (ErrorException $e) {

        ErrCode::echoJson($e->getCode(),$e->getMessage());

    }

}

function addUserToBlackList($userId,$bid)
{
    $sql = <<<T_ECHO
INSERT INTO
    user_friend_list(user_id,friend_id,friend_type_id,auth_flag,is_friend,add_date)
VALUES
    ($userId,$bid,1,1,0,NOW()),($bid,$userId,1,1,0,NOW());
T_ECHO;

    try {

        $dbObj = new DatabaseManager();
        $db = $dbObj->getConn();

        return $db->execute($sql);

    } catch (ErrorException $e) {

        ErrCode::echoJson($e->getCode(),$e->getMessage());

    }

}


function userIsExist($uid)
{
    $sql = <<<T_ECHO
SELECT
	*
FROM
	sky_user_data_master.user_base_info
WHERE
	user_id = $uid;
T_ECHO;

    try {

        $dbObj = new DatabaseManager();
        $db = $dbObj->getConn();

        return $db->getRow($sql);

    } catch (ErrorException $e) {

        ErrCode::echoJson($e->getCode(),$e->getMessage());

    }
}