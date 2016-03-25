<?php

/**
 * 远程调用接口
 * @param $link   提交地址
 * @param array $param 需要提交的参数
 * @return array
 */
 function poster($link,$param){

    $url=C('API_PATH').$link;
    //$url='localhost/tp/fitpay/index.php/Home/'.$controller.'/'.$method;
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_URL,$url.'?XDEBUG_SESSION_START=ECLIPSE_DBGP');
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    //curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=1');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec ($ch);
    curl_close ($ch);
    $resp=json_decode($server_output,true);
   // $this->logger->info("poster's param is: url: ".$url."post data is".var_export($param,true)."decode return is".var_export($resp,true));
    return $resp;
}

/**
 * 返会整日
 */
function getDay($s){
    $a=strtotime($s);
    if(!$a){
        echo '未设置';
    }else {
        echo date('Y-m-d', $a);
    }
}

/**
 * 如果为空的话显示未设置
 */
function if_empty($a){

    if(!$a){
        echo C('IF_EMPTY');
    }else{
        echo $a;
    }

}

/**
 * @param $msg
 * 处理错误
 */
function opError($msg){
   if(IS_AJAX){
        echo 0;
   }
}
/**
 * 头像格式化
 */

function getImg($img){
    if($img==''){
        $img=__ROOT__.'/Public/Home/images/MOO/data/default.jpg';
    }else{
        $img='http://'.C('IMG_HOST').str_replace('group1/','',$img);
    }
    return $img;

}

/**
 * 根据状态返回订单状态
 */
function get_order_staus($code){
    if($code==20){
        return '已付款';
    }else if($code==40){
        return '已提货';
    }else if($code==0){
        return '已取消';
    }
}