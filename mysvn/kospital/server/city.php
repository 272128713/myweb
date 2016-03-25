<?php

/**
 * 获取文章
 */
require  'lnk.php';

$fid = $_POST['fid'];
$sql = "select city,cityID
        from hat_city
        WHERE father = '$fid'
        ";

$city = $database->query($sql)->fetchAll();

echo json_encode($city);


?>