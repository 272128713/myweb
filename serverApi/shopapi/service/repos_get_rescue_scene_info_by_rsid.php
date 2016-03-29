<?php
function getRescueSceneInfoById($id)
{
    $data = array();
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
    $sql = <<<T_ECHO
SELECT a.id,a.user_id uid,a.title,a.content ct,a.latitude lat,a.longitude lon,a.createDate update_time, b.id img_id,b.img_url
FROM rescue_scene_info AS a
LEFT JOIN rescue_scene_img_info AS b
	ON a.id = b.scene_id
WHERE
a.id={$id};
T_ECHO;

    //$user = (new DatabaseManager())->getUserBase($userId);

    $result = $db->getAll($sql);

    if($result){
        foreach($result as $key => $val){
            if($val['img_id']){
                $img[$key]['img_id'] = $val['img_id'];
                $img[$key]['img_url'] = $val['img_url'];
            }
        }
        $data['rsid'] = $result[0]['id'];
        $data['uid'] = $result[0]['uid'];
        $data['title'] = $result[0]['title'];
        $data['ct'] = $result[0]['ct'];
        $data['lat'] = $result[0]['lat'];
        $data['lon'] = $result[0]['lon'];
        $data['imgs'] = $img ? $img : array();
        $data['update'] = $result[0]['update_time'];
    }
    return $data;

}
