<?php
/**
 * 此函数执行一个模拟的HTTP请求，并返回HTTP请求的返回值
 *
 * @param String $url
 * @param Array $params
 * @param String $method
 * @return Mixed
 */
function httpRequest($url,$params = array(),$method = "get"){
    $ch = curl_init($url);
    //curl_setopt($ch, CURLOPT_URL, $url);

    if($params){
        $paramsArray = array();
        foreach ($params as $key=>$value){
            $paramsArray[] = $key . "=" . urlencode($value);
        }
        if($method){
            if(strtolower($method) == "post"){
                curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&",$paramsArray));
            } else {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, implode("&",$paramsArray));
            }
        }
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    $result = curl_exec($ch);
    $errMessage = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($errMessage != "" || $http_code >= 400){
        return false;
    } else {
        return $result;
    }
}
//科室
function dim_rec_code($code){

    $model=M('dim_recollection_code');
    $arr = $model -> where(array('recollection_id'=>$code))->select();

    return $arr[0]['name'];
}