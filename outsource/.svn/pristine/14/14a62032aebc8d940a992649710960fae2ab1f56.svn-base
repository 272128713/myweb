<?php
class UserController extends  Controller{
	
	
	/**
	 * 登录接口
	 */
	public function login(){
		$logger = Logger::getLogger(basename(__FILE__));
		$params = array(array("un",false),array("im",true));
		//print_r($_POST);
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params error. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$im=$params["im"];
		$databaseManager = new DatabaseManager();
		$database = $databaseManager->getConn();
		//数据库链接失败
		if(!$database){
			$logger->error(sprintf("Database connect fail."));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		if(isset($params["un"])){
			$username = $params["un"];
			$num_exp = "/^1[034578][0-9]{9}$/";
			
			if(!preg_match($num_exp, $username)){   //判断用户名是否为手机号码
				$logger->error(sprintf("the username format is error.username is %s",$username));
				ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
			}
			$user = $databaseManager->checkUsernameInvalid($username,'member_name');
			if(!$user){
				$logger->error(sprintf("username is error. username is %s",$username));
				ErrCode::echoErr(ErrCode::API_ERR_PHONE_NO_EXSIT,1);
			
			}else{
				if($user['im']!=''){
					ErrCode::echoErr(ErrCode::IM_HAS_BIND,1);
				}
				$user = $databaseManager->checkUsernameInvalid($im,'im');
				if($user){
					ErrCode::echoErr(ErrCode::IM_HAS_BIND,1);
				}
				if(!M('User')->updateIm($username,$im)){
					ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
				}
			}
		}
					
		//根据im查询用户
		$user = $databaseManager->checkUsernameInvalid($im,'im');
		if(!$user){
			$logger->error(sprintf("username is error. username is %s",$username));
			ErrCode::echoErr(ErrCode::API_ERR_PHONE_NO_EXSIT,1);
		
		}else{
			//检查是否存在角色
			$user_id = $user['member_id'];
			$mid=$user_id;
			$session = $databaseManager->createSession($user_id);   //创建session
			$push_service=2;
			//获取分配服务器信息
			$serverInfo = $databaseManager->dispatchServerAndGetInfo($user_id);
			if(!$serverInfo){
				$logger->error(sprintf("get server info is fail. username is %s",$username));
				ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
			}
			if($user['member_state']==0){
				ErrCode::echoErr(ErrCode::API_ERR_USERNAME_CHECKING,1);
			}
			//更新session相关信息
			$sessionArr = array("client_type" =>1,
					"session" =>$session,
					"mid" =>$mid,
					"push_service_type" =>$push_service,
					"mec_ip" =>$serverInfo['mec_ip'],
					"mec_port" =>$serverInfo['mec_port'],
					"lps_ip" =>$serverInfo['lps_ip'],
					"lps_port" =>$serverInfo['lps_port']);
		
		
				
			$addSession = $databaseManager->addSession($sessionArr,$user_id);
			if(!$addSession){
				$logger->error(sprintf("addSession is fail. username is %s",$username));
				ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
			}
			//获取个人版本号
			$verInfo = $databaseManager->getInfoVersion($user_id);
			if(!$verInfo){  //获取个人信息版本失败，不返回错误。
				$logger->error(sprintf("get info version is fail. username is %s",$username));
			}
		}
		
		//是否已经是空中医院用户
		$conn=M()->getYixinConn();
		
		$mobile=$user['member_name'];
		
		if(!$conn->getOne("select id from com_dic_sso_info where mobile='$mobile'")){
// 				un: 用户名（必填），格式为13/15/14/18开头的11位手机号码
// 				pw: 密码（必填），字符串格式，长度为6-20位
// 				role:角色（必填），1代表普通用户，2代表医生用户
// 				ct：客户端类型（必填），1代表android，2代表ios，3代表winphone
// 				sgn:系统获取到的手机号码，格式为13/15/14/18开头的11位手机号码（非必填）
// 				dv: 设备ID号(可以为空)
// 				sim: sim卡号（可以为空）
// 				rn:  用户的真实姓名(必填)
// 				spreader ：推广码（非必填）
              $config=new Config();
              $pwd=create_password($mobile);
              $data=array(
              	'un'=>$mobile,
              	'pw'=>$pwd,	
              	'role'=>1,
              	'ct'=>1,
              	'sgn'=>$mobile,
              	'rs'=>4,	
              	'rn'=>$config->getConfig("init_name")			
              );
              poster('register.php',$data);
              send_msg_sms($mobile,$pwd);
		}
		$echo_arr = array(
				"uid"=>$user_id,         		 					//用户uid
				"role"=>$user["user_type_id"],        				//用户角色
                "userMoblie"=>$user["member_name"],                 //用户手机号
				"ss"=>$session,                  		 			//用户session
				"push_service_type"=>$push_service, 				//服务器支持的push类型
				"piv"=>$verInfo["base_ver"],						//个人信息版本号
				"pav"=>$verInfo["image_ver"],						//头像版本号
				"lps_server"=>$serverInfo['lps_ip'],    			//长链接服务器地址
				"lps_server_port"=>$serverInfo['lps_port'],    	 	//长链接服务器端口
				"mall_api_server"=>$serverInfo['api_ip'],    	 	//医信API服务器地址
				"mall_api_server_port"=>$serverInfo['api_port'], 	//长链接服务器端口
				"mall_news_server"=>$serverInfo['news_ip'],		//news服务器地址
				"mall_news_server_port"=>$serverInfo['news_port'], //news服务器端口
				"file_server"=>$serverInfo['file_ip'],				//头像上传/下载服务器的地址
				"file_server_port"=>$serverInfo['file_port']);		//文件服务器http下载的端口号
		ErrCode::echoJson(1,'登录成功',$echo_arr);
	}


	/*************************************xu***************************************************/
	
	
	/**
	 * 修改个人信息
	 */
	
	public  function  update()
	{
		$logger = Logger::getLogger(basename(__FILE__));
		$params = array(array("member_name", true),           //session
				array("member_truename", false),            //真实姓名
				array("member_sex", false),            //性别
				array("card_id", false),            //血型，格式为编码
				array("member_birthday", false),            //出生时间，格式为年月日时
				array("member_address", false),            //出生地详细地址
				array("d_address", false),            //出生地详细地址
				array("p_address", false),            //出生地详细地址
				array("member_provinceid", false),        //省份ID
				array("member_cityid", false),            //城市ID
				array("member_areaid", false),            //地区ID
		);
		//print_r($_POST);
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if (!$params) {
			$logger->error(sprintf("params error. params is %s", v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
		}
		$member_name = $params["member_name"];
		//处理参数和表字段的对应关系
		$newparams = array(
				"member_truename" => "member_truename",
				"member_sex" => "member_sex",
				"card_id" => "card_id",
				"member_birthday" => "member_birthday",
				"member_address" => "member_address",
				"member_provinceid" => "member_provinceid",
				"member_cityid" => "member_cityid",
				"member_areaid" => "member_areaid",
				"d_address" => "d_address",
				"p_address" => "p_address",
	
		);
	
		$sqlValue = array();
		$sqlString = "";
		foreach ($_POST as $key => $value) {
			 if($key == "member_name") continue;
			 if (isset($newparams[$key]) && $value!='' && !is_null($value)) {
                array_push($sqlValue, $newparams[$key] . "='" . $value . "'");
            }
				
			
		}
		
		$model = M('User');
		$sql_m="select member_id as user_id,user_type_id as role from mall_member where member_name='$member_name' ";
		$res=M()->conn->getRow($sql_m);
		$user_id=$res['user_id'];
		$updateSql = "update mall_member set " . implode(",", $sqlValue) . " where member_id = '$user_id'";  //拼装的sql语句
		$updateInfo = $model->updatePersonalInfo($updateSql, $user_id);
		if (!$updateInfo) {
			$logger->error(sprintf("update personal info failure.user_id is %s,sql=%s ", $user_id, $updateSql));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
		} else {
			$version = $model->getVersion($user_id);  //获取版本
			if (!$version) {
				$logger->error(sprintf("get info version failure.user_id is %s", $user_id));
				ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
			} else {
				$echo_arr = array("piv" => $version["piv"]);
				ErrCode::echoJson(1,'修改成功',$echo_arr);
			}
		}
	}
	
	
	
	/**
	 * 修改个人头像
	 */
	public  function  changePic(){
		header("content-type:text/html; charset=utf-8");
		include (ROOT_PATH . "/common/MMSFileManager.php");
		$config = new Config();
		$logger = Logger::getLogger(basename(__FILE__));
		$challengeStep = false;
		$params = array(array("member_name",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params error. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$member_name = $params["member_name"];
	    $sql_m="select member_id as user_id,user_type_id as role from mall_member where member_name='$member_name' ";
	    $res=M()->conn->getRow($sql_m);
		$databaseManager=M('User');;
		$user_id=$res['user_id'];
		$role=$res['role'];
		$old_img_urls = $databaseManager->GetAvatarImgUrl($user_id);
		if(is_array($old_img_urls)){
			$fileManagerObj = new MMSFileManager();
			$fileManagerObj->deleteFileByFastDFS($old_img_urls["source_image_url"]);
			$fileManagerObj->deleteFileByFastDFS($old_img_urls["thumbnail_image_url"]);
		}
		$logger->error(sprintf("FILE  %s",v($_FILES)));
		$fileName = $_FILES['file']['name'];
		$fileTempName = $_FILES['file']['tmp_name'];
		$fileSize = $_FILES['file']['size'];
	
		$config = new Config();
		$max_file_size = $config->getLocalConfig('max_file_size');
	
		$file_params = array();
		$file_params["id"] = "$user_id";
		$file_params["mime"] = "avatar";
		//upload file
		if(isset($_FILES['file'])){
			if($_FILES['file']['error'] == 0){
				if($_FILES['file']['size'] <= $max_file_size){
					$fileManager = new MMSFileManager();
					$saveFileResult = $fileManager->uploadFile($fileTempName, $fileName, $file_params);
					if(!$saveFileResult){
						$logger->error(sprintf("uploadFile():Upload file failed. filename=%s, filesize=%s",$fileName,$fileSize));
						ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
					}
					$logger->info("uploadFile():Upload file success. filename:$fileName, filesize:$fileSize");
					ErrCode::echoJson("1",'更换成功',array("pav"=>$saveFileResult));
				}else{
					$logger->error(sprintf("File size exceeds the limit. max_file_size=%s",$max_file_size));
					ErrCode::echoErr(ErrCode::FILE_TOO_LARGE,1);
				}
			}else{
				$logger->error(sprintf("no file params error."));
				ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
			}
		}else{
			$logger->error(sprintf("no file params error."));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
	}
	
	
	/**
	 * 获取软件更新
	 */
	public  function  checkUpdate(){
	
		$logger = Logger::getLogger(basename(__FILE__));
		$challengeStep = false;
		$params = array(array("lg",true),array("os",true),array("cv",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params error. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$os_language = $params["lg"];     //OS语言
		$os_type = $params["os"];         //OS类型，如android, iphone
		$client_version = $params["cv"];  //客户端软件版本号
		$sql = "select * from updateinfo
		where os_language = '$os_language' and os_type = '$os_type' order by upload_date desc";
		$result=M()->conn->getAll($sql);
	    if($result){
	    	foreach ($result as $key=>$row){
	    		if(version_compare($row['update_version'],$client_version) > 0){
	    			$echo_arr = array("download_url"=>$row['download_url'],"update_version"=>$row['update_version'],"update_description"=>$row['update_description'],"file_name"=>$row['file_name'],"file_size"=>$row['file_size'],"upload_date"=>$row['upload_date'],"min_version"=>$row['minimal_version']);
	    			break;
	    		}
	    	}
	    	
	    	if(count($echo_arr) > 0)
	    		ErrCode::echoJson(1,"有更新",$echo_arr);
	    	else
	    		ErrCode::echoErr(ErrCode::API_ERR_NO_UPDATE,1);
	    }else{
	    	ErrCode::echoErr(ErrCode::API_ERR_NO_UPDATE,1);
	    }
	}
	
	public  function  test(){
		$conn=M()->conn;
		$sql="SELECT max(id) as id,member_id,points,create_date,time_date  from  (SELECT id,member_id,points,create_date,time_date,CONCAT(member_id,time_date) as onedate from mall_points ) 
             as a GROUP  BY onedate";
		$arr=$conn->getAll($sql);
		$str='insert into mall_points  values';
		foreach ($arr as $key=>$v){
			$member_id=$v['member_id'];
			$points=$v['points'];
			$create_date=$v['create_date'];
			$time_date=$v['time_date'];
			$str.="(null,'$member_id','$points','$create_date','$time_date'),\n";
		}
		
		echo $str;
	}
	
	
}