<?php

/**
 * 获取文章分类
 */
require  'lnk.php';

$sid = $_POST['sid'];
$sql = "select doc.id,doc.title,doc.description,doc.cover_id,pic.path,doc.update_time
        from onethink_document as doc
        LEFT JOIN onethink_picture as pic
        ON doc.cover_id = pic.id
        WHERE doc.category_id = '$sid' AND doc.status = 1
        ORDER BY doc.update_time DESC limit 0,2";
$datas = $database->query($sql)->fetchAll();
$result=array();
if(!$datas){

    $result['code']="1001";//无文章
    $result['result']="";
}elseif($datas[0]['cover_id']==0){
    $result['code']="001";
    $result["result"]=$datas;
}elseif($datas[0]["cover_id"]!=0){
    $result['code']="000";
    $result["result"]=$datas[0];
}
    $result = json_encode($result);
    echo $result;
?>