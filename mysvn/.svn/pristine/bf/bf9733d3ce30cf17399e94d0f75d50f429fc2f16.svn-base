<?php

/**
 * 获取文章
 */
require  'lnk.php';

if($_GET['aid']){

    $aid = $_GET['aid'];

}else{

    die();
}

$sql = "select art.content,doc.title,doc.description,doc.update_time
        from onethink_document_article as art
        LEFT JOIN onethink_document as doc
        ON doc.id = art.id
        where art.id = $aid";
$datas = $database->query($sql)->fetch();

if(!$datas){
    die();
}
?>