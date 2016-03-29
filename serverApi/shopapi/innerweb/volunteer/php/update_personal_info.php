<?php

include dirname(dirname(__DIR__)).'/common/php/common.php';
$totalnum = 0;
    $post = $_POST;

$code=httpRequest($URLHOST.'/firstaid/1.0/update_personal_info.php',$post,'post');
echo  $code;


