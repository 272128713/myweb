<?php

/**
 * 获取文章
 */
require  'lnk.php';


$sql = "select id,title,description,update_time
        from onethink_document
        WHERE status = 1
        ORDER BY id DESC
        LIMIT 0,6
        ";

$datas = $database->query($sql)->fetchAll();

?>