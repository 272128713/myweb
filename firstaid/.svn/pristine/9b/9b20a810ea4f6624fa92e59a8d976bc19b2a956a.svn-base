<?php
 /*
*send_sms 发送短信
*参数：$mobile 发送目标号码
* $send_type  发送类型（1：发送注册pincode  2：忘记密码发送pincode 3：邀请加入专家组pincode 4：邀请成为健康代表pincode ）
* $replace_content  模板需要替换的内容
*返回值：发送成功返回1
*/
function send_sms($receiver,$send_type,$replace_content)
{
	$logger = Logger::getLogger(basename(__FILE__));
 	$config = new Config();
 	$SMSApiPath = $config->getConfig("smsapipath");
 	$SMSApiUser = $config->getConfig("smsapiuser");
 	$SMSApiPwd = $config->getConfig("smsapipwd");
 			
 	//替换消息内容
 	if($send_type == 1)
		$pincodeMessage = $config->getConfig("regPincodeMessage");
	elseif($send_type == 2) 
		$pincodeMessage = $config->getConfig("forgetPwMessage");
	elseif($send_type == 3)
		$pincodeMessage = $config->getConfig("inviteJoinGroupMessage");//邀请加入专家组	
	elseif($send_type == 4)
		$pincodeMessage = $config->getConfig("inviteHealthAgencyMessage");//邀请成为健康代表		
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

/*
 *send_sms 发送短信
*参数：$mobile 发送目标号码
*      $send_type  发送类型（1：发送注册pincode  2：忘记密码发送pincode）
*      $replace_content  模板需要替换的内容
*      $biz_type  业务id，健康宝为1 商城为2 电子健康档案为3
*返回值：发送成功返回1
*/
function send_sms_ex($receiver,$send_type,$replace_content,$biz_type)
{
	$logger = Logger::getLogger(basename(__FILE__));
	$config = new Config();
	$SMSApiPath = $config->getConfig("smsapipath");
	$SMSApiUser = $config->getConfig("smsapiuser");
	$SMSApiPwd = $config->getConfig("smsapipwd");

	//替换消息内容
	if($send_type == 1)
		$pincodeMessage = $config->getConfig("regPincodeMessage"."$biz_type");
	else
		$pincodeMessage = $config->getConfig("forgetPwMessage"."$biz_type");

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
?>
