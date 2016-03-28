<?php
/**
 * 项目函数，该函数作用是实例化一个类
 */
function M($name=null){
	if(is_null($name)){
		return  new Model();
	}else{
		$model=$name.'Model';
		return  new  $model;
	}
}

/**
 * 检查权限
 */
function check_role($user,$rule){
	$arr=array(1,2,4,3);
	$role=$user['user_type_id'];
	if($arr[$role]<$role){
		//ErrCode::echoErr(ErrCode::LIMITED_AUTHORITY,1);
	}
}

/**
 * 生成写入sql
 */
function insert_sql($table,$arr){
	$keys=array_keys($arr);
	$values=array_values($arr);
	foreach ($values as $k=>$v){
		$values[$k]="\"".$v."\"";
	}
	$sql="insert into $table (".implode(',',$keys).")  values ( ".implode(',',$values)." )";
	
	return  $sql;
}

/**
 * 发送通知消息
 * $type 消息类型
 * $uid 用户id
 * $num 动态变量
 * $content 消息内容网页
 * $mec内容为数组
 * $cid 字类id
 */
function send_msg($mec=null){
    include_once(ROOT_PATH . "/common/MecManager.php");
    $msgObj = array("type" => $mec['type'],
        "src"  => $mec['user_id'],
        "srcm" => $mec['mobile'],
        "content"=>urlencode($mec['content']),
        "time" =>time(),
        "msg_id"=>$mec['msg_id'],
        );
    $mecManager = new MecManager($mec['user_id'],$msgObj,$mec['accepters']);
    $staus=$mecManager->sendMessage();
    if(!$staus){
        $logger = Logger::getLogger(basename(__FILE__));
        $logger->info('mec send fail');
    }


}

function  C($name){
	$config = new Config();
	return  $config->getConfig($name);
}

/**
 * 生成永远唯一的激活码
 * @return string
 */
function create_guid($namespace = null) {
    static $guid = '';
    $uid = uniqid ( "", true );

    $data = $namespace;
    $data .= $_SERVER ['REQUEST_TIME'];     // 请求那一刻的时间戳
    $data .= $_SERVER ['HTTP_USER_AGENT'];  // 获取访问者在用什么操作系统
    $data .= $_SERVER ['SERVER_ADDR'];      // 服务器IP
    $data .= $_SERVER ['SERVER_PORT'];      // 端口号
    $data .= $_SERVER ['REMOTE_ADDR'];      // 远程IP
    $data .= $_SERVER ['REMOTE_PORT'];      // 端口信息

    $hash = strtoupper ( hash ( 'ripemd128', $uid . $guid . md5 ( $data ) ) );
    $guid =  substr ( $hash, 0, 8 ).substr ( $hash, 8, 4 );

    return $guid;



}

/**
 * 生成密码
 * @return string
 */
function create_password($namespace = null) {
	static $guid = '';
	$uid = uniqid ( "", true );

	$data = $namespace;
	$data .= $_SERVER ['REQUEST_TIME'];     // 请求那一刻的时间戳
	$data .= $_SERVER ['HTTP_USER_AGENT'];  // 获取访问者在用什么操作系统
	$data .= $_SERVER ['SERVER_ADDR'];      // 服务器IP
	$data .= $_SERVER ['SERVER_PORT'];      // 端口号
	$data .= $_SERVER ['REMOTE_ADDR'];      // 远程IP
	$data .= $_SERVER ['REMOTE_PORT'];      // 端口信息

	$hash = strtoupper ( hash ( 'ripemd128', $uid . $guid . md5 ( $data ) ) );
	$guid =  substr ( $hash, 0, 4 ).substr ( $hash, 8, 2 );

	return $guid;



}

/**
 * @param $url                                      
 * @param int $size
 */
function formatImg($url,$size=360){


    return str_replace('.','_'.$size.'.',$url);

}

/**
 * @desc 根据生日获取年龄
 * @param     string $birthday
 * @return    integer
 */
function getAge($birthday) {
	$birthday=getDate(strtotime($birthday));
	$now=getDate();
	$month=0;
	if($now['month']>$birthday['month'])
		$month=1;
	if($now['month']==$birthday['month'])
		if($now['mday']>=$birthday['mday'])
			$month=1;
		return $now['year']-$birthday['year']+$month;
}


/**
 * 远程调用接口
 * @param $link   提交地址
 * @param array $param 需要提交的参数
 * @return array
 */
function poster($link,$param){
	$config = new Config();
	$api=$config->getConfig('yixin_api_path');
	$url=$api.$link;
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
 * 远程调用接口有图片
 * @param $link   提交地址
 * @param array $param 需要提交的参数
 * @return array
 */
function posters($link,$param){
	$config = new Config();
	$api=$config->getConfig('yixin_api_path');
	$url=$api.$link;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_POST,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$param);
	$result = curl_exec($ch);
	curl_close($ch);
	return json_decode($result,true);;
}
/*
 *send_sms 发送短信
*参数：$mobile 发送目标号码
* $send_type  发送类型（1：发送注册pincode  2：忘记密码发送pincode 3：邀请加入专家组pincode 4：邀请成为健康代表pincode ）
* $replace_content  模板需要替换的内容
*返回值：发送成功返回1
*/
function send_msg_sms($receiver,$replace_content)
{
	$logger = Logger::getLogger(basename(__FILE__));
	$config = new Config();
	$SMSApiPath = $config->getConfig("smsapipath");
	$SMSApiUser = $config->getConfig("smsapiuser");
	$SMSApiPwd = $config->getConfig("smsapipwd");

	$pincodeMessage=C('reg_message');
	$message = str_replace("[XXX]",$replace_content,$pincodeMessage);
	$params = array(
			"cdkey" => $SMSApiUser,
			"password" => $SMSApiPwd,
			"phone" => $receiver,
			"message" => $message,
	);
	$logger->debug("sms request params=" . var_export($params,true));
	$httpResult = httpRequest($SMSApiPath,$params,$method = "post");
	$logger->debug("sms response=" . var_export($httpResult,true));
	if(null == $httpResult){
		return 0;
	}
	$str = "/<error>(.*)<\/error>/";
	$array = array();
	if(preg_match($str,$httpResult,$array)){
		$error_code = $array[1];
	}
	if($error_code == "0")
		return 1;
	else
		return 0;
}

/**
 * 增加积分记录
 */
function add_points_log($uid,$points,$text,$type,$extend_id=0){
	 
	$sql="insert into mall_points_record (member_id,points,pdr_payment_name,create_date,type,extend_id)
	VALUES ($uid,$points,'$text',now(),$type,$extend_id)";
	return  $sql;
}


/**
 * 分页处理
 * 
 */
function page_oprate($page=null){
	if(is_null($page)){
	    return 0;		 
	}else{
		if(!is_numeric($page)){
			return 0;
		}else{
			$page=($page-1)*C('page_num');
			return $page;
		}
	}
}

/**
 * 获取数据库配置文件
 */
function  conf($name){
	$sql="select value from mall_shop_config where name='$name'";
	return  M()->conn->getOne($sql);
}

/**
 * 红包计算
 */
function  randmoney($total,$num,$min){
	for ($i=1;$i<$num;$i++)  
	{  
	  $safe_total=($total-($num-$i)*$min)/($num-$i);//随机安全上限  
	  $money=mt_rand($min*10,$safe_total*10)/10;  
	  $total=$total-$money; 
	  //echo '第'.$i.'个红包：'.$money.' 元，余额：'.$total.' 元
	  $arr[$i]=$money;	
	}   
	$arr[$num]=$total;
	return $arr;
}
