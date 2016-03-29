<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-26
 * Time: 下午1:48
 */

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