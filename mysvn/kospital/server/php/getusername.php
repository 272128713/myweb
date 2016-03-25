<?php
include './common.php';




$code=httpRequest('http://210.14.72.56/yixin/1.0/get_personal_info.php',$_POST,'post');
$arr=explode("\n",trim($code));
echo json_encode($arr);





