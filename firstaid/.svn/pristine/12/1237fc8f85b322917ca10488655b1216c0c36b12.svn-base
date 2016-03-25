<?php

/**
 *  @desc 根据两点间的经纬度计算距离
 *  @param float $lat 纬度值
 *  @param float $lng 经度值
 */
function getDistance($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6367000; //approximate radius of earth in meters

    $lat1 = ($lat1 * pi() ) / 180;
    $lng1 = ($lng1 * pi() ) / 180;

    $lat2 = ($lat2 * pi() ) / 180;
    $lng2 = ($lng2 * pi() ) / 180;

    $calcLongitude = $lng2 - $lng1;
    $calcLatitude = $lat2 - $lat1;
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    $calculatedDistance = $earthRadius * $stepTwo;

    return round($calculatedDistance);
}


function getRescueList($flag,$userId,$myGps,$page = 1)
{
    $pageSize = 10;
//    $limit = ($page -1)*$pageSize;


    $data = array();
    /**
     * 0全部 1离我最近 2 我的现场 3 历史现场
     */

    switch ($flag) {
        case 1 :
            $sql = <<<T_ECHO
SELECT
    id AS rsid,
    user_id,
    title,
    state,
    longitude,
    latitude,
    content,
    address,
    active_state,
    createDate as update_time,
    ABS(TIMESTAMPDIFF(MINUTE,NOW(),createDate)) AS times
FROM
    rescue_scene_info
WHERE
    user_id != {$userId};

T_ECHO;
            break;
        case 2 :
            $sql = <<<T_ECHO
SELECT
    id AS rsid,
    user_id,
    title,
    state,
    longitude,
    latitude,
    content,
    address,
    active_state,
    createDate as update_time,
    ABS(TIMESTAMPDIFF(MINUTE,NOW(),createDate)) AS times
FROM
    rescue_scene_info
WHERE
    user_id = {$userId} AND state = 0
ORDER BY
    times ASC;

T_ECHO;
            break;
        case 3 :
            $sql = <<<T_ECHO
SELECT
    id AS rsid,
    user_id,
    title,
    state,
    longitude,
    latitude,
    content,
    address,
    active_state,
    createDate as update_time,
    ABS(TIMESTAMPDIFF(MINUTE,NOW(),createDate)) AS times
FROM
    rescue_scene_info
WHERE
    user_id = {$userId} AND
    state = 1
ORDER BY
    times ASC;

T_ECHO;
            break;
        default :
            $sql = <<<T_ECHO
SELECT
    id AS rsid,
    user_id,
    title,
    state,
    longitude,
    latitude,
    content,
    address,
    active_state,
    createDate as update_time,
    ABS(TIMESTAMPDIFF(MINUTE,NOW(),createDate)) AS times
FROM
    rescue_scene_info
WHERE
    state != 1
ORDER BY
    rsid DESC;

T_ECHO;

    }

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
//echo $sql;
    if($data = $db->getAll($sql)){

        foreach($data as $key => $val){
            if ($userId != $val['user_id']) {
                $active_state = '0';
            }else{
                $active_state = $val['active_state'];
            }

            $data[$key]['active_state'] = $active_state;
            $distance = getDistance($myGps[1],$myGps[0],$val['latitude'],$val['longitude']);
            $data[$key]['distance'] = is_nan($distance) ? 0 : $distance;
            $data[$key]['imgs'] = getRescuePhotosById($val['rsid']);
            $data[$key]['comment_total'] = getRescueTopCommentsTotal($val['rsid']);
        }

    }

    if($flag == 1){
        $distance = array();
        foreach($data as $key => $val){
//            echo "Key: ".$key."\n";
//            echo "Distance: ".$val['distance']."\n";

            $id[$key] = $val['rsid'];
            $distance[$key] = is_nan($val['distance']) ? 0 : $val['distance'];
            //$data[$key]['comment_total'] = getRescueTopCommentsTotal($val['rsid']);
        }
        //var_dump($distance);
        array_multisort($distance,SORT_ASC,SORT_NUMERIC,$data);
    }

    //var_dump($data);

    $data = array_chunk($data,$pageSize);

    return $data[$page-1] ? $data[$page-1] : array();

}

function getUserBySession($session)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    if (!$session) {
        logger()->error(sprintf("Session check is fail. Error session is [%s]",$session));
        Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
        return false;
    }
    $querySql = <<< T_ECHO
    SELECT * FROM sky_first_aid.user_session_info WHERE session= "{$session}";
T_ECHO;

    $result = $db->getRow($querySql);
    return ($result)? $result : array();

}

function getRescuePhotosById($id)
{
    $data = array();
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
    $sql = <<< T_ECHO
    SELECT img_url FROM rescue_scene_img_info WHERE scene_id = $id;
T_ECHO;
    if($db->getAll($sql)){
        foreach($db->getAll($sql) as $val){
            if($val['img_url']){
                $data[] = $val['img_url'];
            }
        }
    }
    return $data;

}


function getRescueTopCommentsTotal($rsid)
{
    $select = <<<T_ECHO
SELECT
	count(*)
FROM
	rescue_scene_comment_info
WHERE
	rs_id = $rsid AND high_rs_id = 0
T_ECHO;

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $data = $db->getCol($select);

    return $data[0];

}

