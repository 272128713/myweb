<?php
/*
 * 1.2.18. send_sosmsg.php（发送急救信息）
 */
header ( "content-type:text/html; charset=utf-8" );
include (dirname ( __FILE__ ) . "/common/RKMongo.php");
include (dirname ( __FILE__ ) . "/common/inc.php");
include (dirname ( __FILE__ ) . "/common/MecManager.php");
include (dirname ( __FILE__ ) . "/common/MMS_FileManager.php");
include(dirname(__FILE__) . "/service/send_sosmsg.php");
$logger = Logger::getLogger ( basename ( __FILE__ ) );
// 输入参数校验
$params = array (array ("ss",true),array ("id",true),array("flag",false),array("longitude",false),array("latitude",false));
$params = Filter::paramCheckAndRetRes($_POST,$params);
if (!$params){
	$logger->error(sprintf ( "params error. params is %s", v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}
// 接收参数
$session = $params["ss"];
if ($params ["flag"]){
	$flag = $params["flag"]; // 求救消息类型（0 向附近的人发1 向救命稻草发 2向紧急联系人发）
} else{
	$flag = 0;
}
//接收经纬度
if($params["latitude"]!=''){	
	$latitude = $params["latitude"];  //longitude：经度（必填）
}
if($params["longitude"] !=''){	
	$longitude = $params["longitude"];  //latitude：纬度（必填）
}
// session校验
$databaseManager = new DatabaseManager();
$database = $databaseManager->getConn();
if (!$database){
	$logger->error("database connect error.");
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
$sessionArr = $databaseManager->checkSession($session );
if ($sessionArr){
	$user_id = (int)$sessionArr["user_id"];
	$mobile = $sessionArr["mobile"];
	$myFriendList = "";
} else {
	$databaseManager->destoryConn();
	$logger->error(sprintf( "Session check is fail. Error session is [%s]", $session ) );
	Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION, 1 );
}
// 发给用户10人
$mongoDB = new RKMongo();
$mongo = $mongoDB->connect();
if (!$mongo){
	$logger->error("mongoDB connect error.");
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
//如果客户端不上传经纬度，那么就要从数据库中去查询
if($latitude =='' && $longitude == ''){
	// 根据userid获取其GPS位置信息
	$myGPS = $mongoDB->getMyLbs ( 1, $user_id );
	$longitude = $myGPS [0]; // 精度
	$latitude = $myGPS [1]; // 纬度
	if (!$myGPS){
		ErrCode::echoOkArr("60008","do not get my gpsInfo.");
	}	
}else{
	$myGPS=array();
	$myGPS[0]=(float)$longitude;
	$myGPS[1]=(float)$latitude;
}
//根据$flag判断是给谁发送急救消息
if ($flag == 0){//0 向附近的人发
	    $ot=0; 
		$mongoResult = $mongoDB->getLbs(1,$user_id,$myGPS,array($user_id));
		$mongoDB->close();
		$uid = "";
		if(!$mongoResult){
			ErrCode::echoOkArr("60007","do not matchFriendGpsInfo.");
		}else{
			// 进行数据抽取
			$mongoFriendList = array();
			$tmpFriendList = ""; // 拼接mysql查询字符串(123,456,789....)
			$numberCount = 0;
			foreach( $mongoResult['results'] as $line ) {
				$tmpFriend ['uid'] = $line['obj']['_id']; // 取出附近好友id
				$tmpFriend ['dis'] = round($line['dis'] ); // 取出好友的dis
				array_push($mongoFriendList,$tmpFriend ); // 然后把这两条数据压进数组$mongoFriendList中
			}
			$cou = count($mongoFriendList ); // 统计数组中数据个数
			if($cou < 1) { // 表示附近没有人
				$logger->error("no man error." );
				ErrCode::echoErr(ErrCode::API_ERR_NO_PEOPLE,1);
			}
				foreach($mongoFriendList as $mongoLine) {		
					$uid .= $mongoLine ['uid'].",";
				}
		}
	$uid = substr($uid, 0,-1);
	$tempParams = array_values(array_unique(explode(",",$uid))); // array_unique()作用是移除数组中重复的值
	$uidCount = count($tempParams);
	if($uidCount == 0){
		$logger->error(sprintf("params error. error. member is %s", $uid));
		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
	}
	if($uidCount>20){
		$a = (int)($uidCount / 20);
		for($i=0;$i<$a;$i++){
			$tempParams =  array_slice($tempParams,$i*20,20);
			$uid = implode(",",$tempParams); // 把数组按照 , 分割拆分成字符串
			send_smss($uid,$user_id,$mobile,$ot,$params,$longitude,$latitude,$sessionArr);
		}
	}else{
		$uid = implode(",",$tempParams); // 把数组按照 , 分割拆分成字符串
		send_smss($uid,$user_id,$mobile,$ot,$params,$longitude,$latitude,$sessionArr);
	} 
}else if($flag==1){ //1 向救命稻草发 
	
	$ot=1;
	//查询救命稻草的id
	$uid =  send_sosmsg($user_id,$mobile);
	if(empty($uid)){
		ErrCode::echoOkArr("60001","救命稻草不存在");
	}
	foreach ($uid as $key=>$value){ //把救命稻草的id拼接成字符串
		$users_ids .= $value['user_id'].",";
	}
	$uid = substr($users_ids,0,-1);
	
	
	$tempParams = array_values(array_unique(explode(",",$uid)));
	$uidCount = count($tempParams);
	if ($uidCount == 0){
		$logger->error(sprintf("params error. error. member is %s",$uid));
		ErrCode::echoOkArr("60001","救命稻草不存在");
	}
	if($uidCount>20){
		$a = (int)($uidCount / 20);
		for($i=0;$i<$a;$i++){
			$tempParams = array_slice($tempParams,$i*20,20);
			$uid = implode(",",$tempParams); // 把数组按照 , 分割拆分成字符串
			send_smss($uid,$user_id,$mobile,$ot,$params,$longitude,$latitude,$sessionArr);
		}	
	}else{
		$uid = implode(",", $tempParams); // 把数组按照 , 分割拆分成字符串
		send_smss($uid,$user_id,$mobile,$ot,$params,$longitude,$latitude,$sessionArr);
	} 	
}else if($flag == 2){//2向紧急联系人发
	$ot = 2;
	//查询紧急联系人的id
	$uid =  send_sosmsg_linkman($user_id,$mobile);
	if(empty($uid)){
			ErrCode::echoOkArr("60000","紧急联系人不存在");
	}
	foreach($uid as $key=>$value){ //把救命稻草的id拼接成字符串
		$users_idss .= $value['user_id'].",";
	}
	$uid = substr($users_idss,0,-1);
	$tempParams = array_values(array_unique(explode(",",$uid )));
	$uidCount = count ($tempParams );
	if ($uidCount == 0){
		$logger->error(sprintf ( "params error. error. member is %s", $uid ) );
		ErrCode::echoOkArr("60000","紧急联系人不存在");
	}
    if($uidCount>20){
	$a =(int)($uidCount / 20);
	for($i=0;$i<$a;$i++){
		$tempParams = array_slice($tempParams,$i*20,20);
		$uid = implode(",", $tempParams); // 把数组按照 , 分割拆分成字符串
		send_smss($uid,$user_id,$mobile,$ot,$params,$longitude,$latitude,$sessionArr);
	  }

     }else{
     	$uid = implode(",", $tempParams); // 把数组按照 , 分割拆分成字符串
     	send_smss($uid,$user_id,$mobile,$ot,$params,$longitude,$latitude,$sessionArr);
     } 
}
/*
 * 调用该函数，发送消息
 */
function send_smss($uid,$user_id,$mobile,$ot,$params,$longitude,$latitude,$sessionArr){
	
	$logger = Logger::getLogger (basename(__FILE__));
	$databaseManager = new DatabaseManager();
	$database = $databaseManager->getConn();	
 	$mime = "sos/nearby";
 	$gps_json = json_encode(array("longitude" =>$longitude,"latitude" => $latitude));
 	$textContent ="SOS附近的好心人：我是".$sessionArr ['rn']."，我身体突感不适，处于危险之中，急需您的帮助！请收到本求救信息的人迅速与我联系，我的手机号是".$sessionArr ['mobile'] . "，万分感谢您！" . "\n" . $gps_json;
 	//获取用户的图像版本号
 	$pav =  send_sosmsg_getpav($user_id);
 	//生成一个全局唯一的标识
 	$uuid = uniqid();
 	$msgObj = array (
 			"id" => $params["id"],
 			"type" => "SOM",
 			"mime" => $mime,
 			"srcm" => $mobile,
 			"src" => $user_id,
 			"longitude" => $longitude, // 精度
 			"latitude" => $latitude, // 纬度
 			"ot"=>$ot,
 			"uuid"=>$uuid,
 			'pav'=>$pav,
 			"text" => rawurlencode($textContent),
 			"time" => time()
 	);
 	$notifyMember = str_replace($user_id.",","",$uid ); // 过滤发送者自己
 	//把紧急联系人手机号码组合成数组
 	$sosusers = $databaseManager->getUserInfoByPhone($notifyMember);
 	$sosuser='';
 	foreach($sosusers as $key=>$value){ //把救命稻草的id拼接成字符串
 		$sosuser .= $value['mobile'].",";
 	}
 	$sosuser = substr( $sosuser,0,-1);
 	$sosuser = array_values(array_unique(explode(",",$sosuser)));
 	//当为紧急联系人的时候给客户端返回5个手机号
 	if(count($sosuser)>5){
 		$sosuser = array_chunk($sosuser,5);  //array_chunk()把大数组拆分成小数组
 		$sosuser = $sosuser[0];
 	}
 	// 给接收者发送notify
 	$accepters = $databaseManager->getUserInfoByUid($notifyMember);	
 	$databaseManager->destoryConn(); // unlink Master DB
 	if (!$accepters){
 		$logger->error(sprintf("accepter not exists ,uid %s",$notifyMember));
 		ErrCode::echoErr(ErrCode::API_ERR_MESSAGE_SEND_ERROR,1);
 	}
 	// 实现发送消息
 	$mecManagerSos = new MecManager($user_id,$msgObj,$accepters);
 	$mecManagerSos->sendMessage();
 	//当给紧急联系人发送求救消息的时候，返回5个手机号
 	if($ot==2){
 		ErrCode::echoOkArr("1","请求成功",$sosuser);
 	}
 	//当不是给紧急联系人发送急救消息的时候不需要返回任何数据，只需要返回成功的状态即可
 	ErrCode::echoOk ( "OK", 1);
 	
}
?>