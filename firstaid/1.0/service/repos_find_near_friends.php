<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-27
 * Time: 上午10:14
 */

/**
 * @param $nearbyUserIdListArray
 * @return array
 * @throws Exception
 */
function getFriendListAndInfoByUserId($nearbyUserIdListArray)
{
    $data = array();
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    try {
        $friendList = implode(',',array_keys($nearbyUserIdListArray));
        $querySql =<<<T_ECHO
SELECT
    b.user_id uid,b.user_name name,b.mobile,b.privilege_id,
    v.base_ver piv,v.image_ver pav
FROM
    sky_user_data_master.user_base_info AS b,
    sky_user_data_master.user_version_info AS v
WHERE
    b.user_id = v.user_id
AND
    b.user_id in ($friendList);
T_ECHO;

        if($res= $db->getAll($querySql)){
            foreach($res as $key => $user){
                $data[$key]['uid'] =$user['uid'];
                $data[$key]['name'] =$user['name'];
                $data[$key]['mobile'] =$user['mobile'];
                $data[$key]['privilege_id'] =$user['privilege_id'];
                $data[$key]['piv'] =$user['piv'];
                $data[$key]['pav'] =$user['pav'];
                $data[$key]['dis'] = $nearbyUserIdListArray[$user['uid']];
            }
            return $data;
        }

    } catch (ErrorException $e) {
        echo json_encode(array(
            'code' => $e->getCode(),
            'msg' => $e->getMessage(),
            'result' =>array()
        ));
    }

}