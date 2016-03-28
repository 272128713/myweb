<?php
/**
* 错误代码定义
*/ 
class ErrCode{	

	const API_ERR_USERNAME_FORMAT		  = "1001";   //用户名格式不对
	const API_ERR_PHONE_NO_EXSIT		  = "1014";   //账户未注册或者未绑定手机号
	const API_ERR_USERNAME_EXIST		  = "1002";   //用户名已经存在
	const API_ERR_INVALID_SESSION         = "1011";   //无效的session
	const API_NOT_SUFFICIENT_FUNDS        = "1102";   //余额不足
	const API_ERR_NO_UPDATE               = "1012";   //没有发现更新
	const API_NOT_HAVE_BANK       		  = "1501";   //未设置银行卡号
	const API_YOU_HAVE_SIGN       		  = "1502";   //你已经签到
    const API_ENVELOPE_EMPTY      		  = "1503";   //红包已经抢完了
    const API_ENVELOPE_HAVAD      		  = "1504";   //红包已经抢过了
    const UPPER_LIMIT                     ='1505';     //已经到达上限
    const IM_HAS_BIND                     ='1506';     //此手机已经绑定
    const CODE_NOT_EXIST                  ='1507';    //提货码无效
    const NOT_SHOP_MEMBER                 ='1508';    //不是本店会员
    const LIMITED_AUTHORITY				  ='1509';    //权限不够
    const API_ERR_USERNAME_CHECKING		  ='1510';    //此账户正在审核 	
    const API_ERR_NOT_SHOP_WORKER		  ='1511';    //不是本地员工
    const API_ERR_NOT_IPDATE_SHOP         ='1512';     //店铺没有改变
    const API_ERR_RETURNISDOING           ='1513';     //正处理退货
    const API_ERR_NOT_HAVE_PEOPLE         ='1514';     //符合领取的人不够
    const API_ERR_BONUS_IS_LOW            ='1515';    //每个人红包不能少于1个积分
    const HAVE_NO_LOSE                    ='1516';     //没有开启失连
    const API_ERR_POINTS_IS_LOW           ='1517';     //积分不够
    const ACTIVITY_NOT_START               ='1518';     //活动没开始
    const ACTIVITY_ISFULL_PEOPLE           ='1519';     //人数已经满了
    const ACTIVITY_IS_ENDED                 ='1520';     //活动已经结束
    const YOU_HAVE_JOIN_THIS              ='1521';        //已经参加过了 
	const SYSTEM_ERR                      = "9998";   //系统错误
	const API_ERR_MISSED_PARAMATER        = "9999";   //参数错误
    const API_ERR_MESSAGE_SEND_ERROR	  = "2002";		//消息发送失败
    const API_ERR_FILE_TOO_LARGE          = "2001";     //文件大小超过限制
    const API_ERR_MESSAGE_GET_ERROR	      = "2003";		//获取消息失败
    const API_ERR_MMS_FILE_NO_EXSIT       = "2006";		//文件不存在
    const API_ERR_MESSAGE_ACK_ERROR       = "2004";     //ack响应失败
    const API_ERR_ACK_HAVE_MESSAGE        = "2005";     //ack响应队列中还有消息，需要用户再取一次
    const API_HAS_TOO_MANY_URGENCY        = "1038";		//紧急联系人已经达到上限
    const FILE_SEND_ERR					  = "1026";   //图片上传失败
    const GROUP_NUMBER_IS_NOT_EXISTS   	  = "3004";		//健康圈不存在
    const IS_NOT_GROUP_MEMBER       	  = "3006";		//不是本健康圈人员
          
    	
	private static  $hashmap=array(
		'1001'=>'用户名格式不对',
		'1014'=>'账户未注册或者未绑定手机号',
		'1002'=>'用户名已经存在',
		'1102'=>'余额不足',	
		'1011'=>'无效的session',
		'1012'=>'没有发现更新',	
        '2002'=>'消息发送失败',
		'2003'=>'获取消息失败',
		'3004'=>'健康圈不存在',
		'3006'=>'不是本健康圈人员',			
		'9998'=>'系统错误',
		'9999'=>'参数错误',
        '2001'=>'文件大小超过限制',
		'2006'=>'文件不存在',
		'2004'=>'ack响应失败',
		'2005'=>'ack响应队列中还有消息，需要用户再取一次',
		'1038'=>'已经达到上限',
		'1026'=>'图片上传失败',
		'1501'=>'未设置银行卡号',
		'1502'=>'你今天已经签到',
        '1503'=>'红包已经抢完了',
        '1504'=>'红包已经抢过了',
		'1505'=>'会员人数已经到达上限',
		'1506'=>'此手机已经绑定',
		'1507'=>'提货码无效',
		'1508'=>'不是本店会员',
		'1509'=>'权限不够',
		'1510'=>'此账户正在审核',
		'1511'=>'不是本地员工',
		'1512'=>'店铺没有改变',
		'1513'=>'正在处理退货',
		'1514'=>'领取红包的人数不够',
		'1515'=>'每个红包不能少于2个积分',
		'1516'=>'没有开启失连',
		'1517'=>'你的积分不够',
		'1518'=>'活动没开始',
		'1519'=>'人数已满',
		'1520'=>"活动已经结束",
		'1521'=>'已经参加过了'																
				
	);
	public static function getJson($errCode){
		$ret['code']=$errCode;
		$ret['msg']=self::$hashmap[$errCode];
		return  json_encode($ret);
	}
	
	public static function echoJson($code,$msg="",$result=""){
		$retarr=array();
		$retarr['code']=$code;
		$retarr['msg']=$msg;
		if($result!=''){
			$retarr['result']=$result;
		}
		echo  json_encode($retarr)."\n";
		exit;
	}
	/*
	 * $type 1为json格式
	 */
	public static function echoErr($errCode,$type=0){
		if ($type==1){ 
			echo ErrCode::getJson($errCode) . "\n";
		}else{
			echo "oper_result=" . $errCode . "\n";
		}
		exit;
	}
	/*
	 * $type 1为json格式
	*/
	public static function echoOk($result,$type=0){

		if ($type==1){
			ErrCode::echoJson("ok","请求成功");
		}else{
			echo "oper_result=0";
		}
		
		exit;
	}
    public static function echoOkArr($result,$resultArr){
        echo "oper_result=0\n";
//        echo "oper_descr=" . $result . "\n";
        if(gettype($resultArr) == "array"){
            foreach($resultArr as $key=>$value){
                echo "$key=" . $value . "\n";
            }
        } else {
            echo $resultArr;
        }
		exit;
	}
    public static function echoErrArr($errCode,$resultArr){
		echo "oper_result=" . $errCode . "\n";
//		echo "oper_descr=OPER_ERR\n";
		if(gettype($resultArr) == "array"){
            foreach($resultArr as $key=>$value){
                echo "$key=" . $value . "\n";
            }
        } else {
            echo $resultArr;
        }
		exit;
	}
}
?>