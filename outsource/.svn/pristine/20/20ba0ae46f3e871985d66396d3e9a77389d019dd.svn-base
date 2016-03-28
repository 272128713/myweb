<?php

header('content-type:text/html;charset=utf8');
$ch = curl_init();
//加@符号curl就会把它当成是文件上传处理
$data = array('img'=>'@'.$_FILES['img']['tmp_name']);
curl_setopt($ch,CURLOPT_URL,"http://117.34.72.251/sanlingyi/get_img.php");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
$result = curl_exec($ch);
curl_close($ch);

var_dump($result);
die();
echo json_decode($result);