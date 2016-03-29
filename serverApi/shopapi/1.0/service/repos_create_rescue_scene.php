<?php

function createRescueScene($param)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
    $sql = <<<T_ECHO
INSERT INTO rescue_scene_info (user_id,title,content,longitude,latitude,address,createDate)
VALUES ("{$param['user_id']}","{$param['title']}","{$param['ct']}","{$param['longitude']}","{$param['latitude']}","{$param['address']}",now());
T_ECHO;

    if($db->execute($sql)){
        return mysql_insert_id();
    }

}

function saveRescueSceneImage($rsid,$url)
{
    $obj = new DatabaseManager();
    $db = $obj->getConn();
    $sql = <<<T_ECHO
INSERT INTO rescue_scene_img_info (scene_id, img_url,createDate)
VALUES ("{$rsid}","{$url}",now());
T_ECHO;

    return  $db->execute($sql);

}


function  delRescueScene($id)
{
    $obj = new DatabaseManager();
    $db = $obj->getConn();
    $sql = <<<T_ECHO
DELETE FROM sky_first_aid.rescue_scene_info WHERE id = $id
T_ECHO;
    return  $db->execute($sql);
}