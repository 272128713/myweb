<?php
include dirname(dirname(__DIR__)).'/common/php/common.php';
include dirname(dirname(__DIR__)).'/common/php/lnk.php';
$totalnum = 0;
if(isset($_GET['ss'])){
    $post = array();
    $post['ss']=$_GET['ss'];
}else{
    die();
}
    $code=httpRequest($URLHOST.'/firstaid/1.0/get_postulant_nums.php',$post,'post');

    $arr = json_decode($code);
    $code = $arr->code;


	if($code==1){
    //请求成功
        $list = $arr->result;
    }else{
        echo $arr->msg;
    }


