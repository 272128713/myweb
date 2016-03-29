<?php
include dirname(dirname(__DIR__)).'/common/php/common.php';


$result=httpRequest($URLHOST.'/firstaid/1.0/set_org_apply.php',$_POST,'post');

echo $result;