<?php

/**
 * 获取所有文章列表
 */
require  'lnk.php';
if(isset($_GET["page"])){
    $page = $_GET["page"]-1;
}else{
    $page = 0;
}
$num=0;//总页数


if(isset($_GET['sort'])){
    if(!$_GET['sort']){
        die();
    }
    $sort = $_GET['sort'];
    $sql = "select title from onethink_category WHERE id = '$sort'";
    $sname = $database->query($sql)->fetch();
    $sql = "select doc.id
            from onethink_document AS doc
            LEFT JOIN onethink_picture as pic
            ON doc.cover_id = pic.id
            WHERE doc.category_id = '$sort' AND doc.status=1
            ";
    $datas = $database->query($sql)->fetchAll();
    $num = count($datas);
    $num = ceil($num/$page_lenth);


    $sql = "select doc.id,doc.title,doc.description,doc.cover_id,pic.path,doc.update_time
            from onethink_document AS doc
            LEFT JOIN onethink_picture as pic
            ON doc.cover_id = pic.id
            WHERE doc.category_id = '$sort' AND doc.status=1
            ORDER BY doc.update_time DESC
            limit $page,$page_lenth
            ";
    $datas = $database->query($sql)->fetchAll();

}elseif(isset($_GET['vl'])){
    $vl= $_GET['vl'];

    $sql = "select doc.id,doc.title,doc.description,doc.cover_id,pic.path,doc.update_time
            from onethink_document AS doc
            LEFT JOIN onethink_picture as pic
            ON doc.cover_id = pic.id
            WHERE  doc.status=1 AND title LIKE '%".$vl."%'";

    $datas = $database->query($sql)->fetchAll();
    $num = count($datas);
    $num = ceil($num/$page_lenth);

    $sql = "select doc.id,doc.title,doc.description,doc.cover_id,pic.path,doc.update_time
            from onethink_document AS doc
            LEFT JOIN onethink_picture as pic
            ON doc.cover_id = pic.id
            WHERE  doc.status=1 AND title LIKE '%".$vl."%'
            ORDER BY doc.update_time DESC
            limit $page,$page_lenth";
    $datas = $database->query($sql)->fetchAll();
}else{
    die();


}
?>