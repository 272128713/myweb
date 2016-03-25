<?php
 /**
  * 
  * Enter description here ...
  * @author lion
  *
  */
	include(dirname(__FILE__) . "/Thumb.php");
	include(dirname(__FILE__) . "/FastDFSClient.php");
   class MMSFileManager{
       private $isEnableDFS;
       private $config;
       private $logger;
       public function __construct(){
           $this->logger = Logger::getLogger(basename(__FILE__));
           $this->config = new Config();
       }
       /*添加圈子内容，包括 主题内容、图片
       	 本接口单独为上传圈子内容开发
         整个DB过程为事务处理
         */
		public function AddCircleContent($circle_type,$user_id,$cc,$file_params){
			if($circle_type > 2)
				return false;
            $params["id"] = "$user_id";
            $params["mime"] = "image";
            $CircleTypeMessageArr = array("","user_space_message","doctor_fans_message","user_space_message");
            $CircleTypeImgArr = array("","user_space_image","doctor_fans_image","user_space_image");
            $update_file_result = array();
            $all_file_url_arr = array();
            $title_id = -1;

			if(isset($file_params['file_name'])){
	            $image_file_arr = $file_params['file_name'];
	            $count = count($image_file_arr);
            }

            if($count > 0){//有图片，必须上传成功。
                foreach($image_file_arr as $file_name){
                        $real_file = dirname(__FILE__) . "/../" . $file_params['file_path'] ."/". $file_name;
                        $path = $this->createFile($real_file,$file_name,$params);
                        $update_file_result = $this->uploadFileByFastDFS($path,$file_name);
                        if(!$update_file_result) return false;
                        array_push($all_file_url_arr,$update_file_result);
                }

                //继续写 cc to DB ,get Title_id;
                //上传成功，在写imgage to DB
                $databaseManager = new DatabaseManager();
                $circleConn = $databaseManager->getCircleConn();
                $circleConn->startTrans();

                $CCTable = $CircleTypeMessageArr[$circle_type];
                $AddSql = "insert into $CCTable(user_id,content,add_date)values('$user_id','$cc',NOW())";

                $ret = $circleConn->execute($AddSql);
                $title_id = $circleConn->getOne("SELECT LAST_INSERT_ID()");

                if($circleConn->hasFailedTrans()){
                        $circleConn->completeTrans(false);
                        $this->logger->error(sprintf("AddCircleContent add cc to DB err. "));
                        return false;
                } else {
                        //$circleConn->completeTrans(true);
                        //return true;
                        //get $title_id success ,go on ;
                }
                //image 原图，缩略图 信息写入DB
                foreach($all_file_url_arr as $per_file_urls){

                        $ImgTable = $CircleTypeImgArr[$circle_type];
                        $source_image_url =$per_file_urls [0];
                        $thumbnail_image_url = $per_file_urls[1];
                        $image_id= $per_file_urls[2];
						if($circle_type == 2)
	                        $AddSql = "insert into $ImgTable(title_id,image_id,source_image_url,thumbnail_image_url)values($title_id,'$image_id','$source_image_url','$thumbnail_image_url')";
	                    else
	                    	$AddSql = "insert into $ImgTable(title_id,user_id,image_id,source_image_url,thumbnail_image_url)values($title_id,'$user_id','$image_id','$source_image_url','$thumbnail_image_url')";
                        $ret = $circleConn->execute($AddSql);
                }

                if($circleConn->hasFailedTrans()){
                        $circleConn->completeTrans(false);
                        $this->logger->error(sprintf("AddCircleContent image info to DB err. source_image_url=%s, thumbnail_image_url=%s",$source_image_url,$thumbnail_image_url));
                        return false;
                }
                $circleConn->completeTrans(true);
                $circleConn->disConnect();
            }
            else{
                $databaseManager = new DatabaseManager();
                $circleConn = $databaseManager->getCircleConn();
                $circleConn->startTrans();

                $CCTable = $CircleTypeMessageArr[$circle_type];
                $AddSql = "insert into $CCTable(user_id,content,add_date)values('$user_id','$cc',NOW())";
                $ret = $circleConn->execute($AddSql);
                $title_id = $circleConn->getOne("SELECT LAST_INSERT_ID()");
                if($circleConn->hasFailedTrans()){
                        $circleConn->completeTrans(false);
                        $this->logger->error(sprintf("AddCircleContent add cc to DB err. "));
                        return false;
                } else {
                        $circleConn->completeTrans(true);
                        $circleConn->disConnect();
                }
            }
            return array("title_id"=>"$title_id");
		}
		

		public function AddDidiContent($user_id,$cc,$file_params,$result_id,$mime){
			$params["id"] = "$user_id";
			$params["mime"] = $mime;
// 			$CircleTypeMessageArr = array("","user_space_message","doctor_fans_message","user_space_message");
// 			$CircleTypeImgArr = array("","user_space_image","doctor_fans_image","user_space_image");
			$update_file_result = array();
			$all_file_url_arr = array();
			$title_id = -1;
			
			if(isset($file_params['file_name'])){
				if($mime =="image"){
					$image_file_arr = $file_params['file_name'];
					$count = count($image_file_arr);
				}else{
					$image_file_arr = (array)$file_params['audiu_name'];
					$count = count($image_file_arr);
				}
			}
			if($count > 0){//有图片，必须上传成功。
				foreach($image_file_arr as $file_name){
					$real_file = dirname(__FILE__) . "/../" . $file_params['file_path'] ."/". $file_name;
					$path = $this->createFile($real_file,$file_name,$params);
					
					$update_file_result = $this->uploadFileByFastDFS($path,$file_name);
					
// 					var_dump($update_file_result);
// 					die();
					if(!$update_file_result) return false;
					array_push($all_file_url_arr,$update_file_result);
				}
				//继续写 cc to DB ,get Title_id;
				//上传成功，在写imgage to DB
				$databaseManager = new DatabaseManager();
				$db = $databaseManager->getConn();
				
				//image 原图，缩略图 信息写入DB
				foreach($all_file_url_arr as $per_file_urls){
		
					$source_image_url =$per_file_urls [0];
					$group = explode('/', $source_image_url);
					$thumbnail_image_url = $per_file_urls[1];
					$image_id= $per_file_urls[2];
					$image_id_thumb = $image_id.'-thumb';
		
						$AddSql = "insert into user_question_info_files (uqid,filetype,fileid,createDate)values('$result_id','$mime','$image_id',now())";
						$Addquestionfile = $db->execute($AddSql);
					
						$AdddfsSql = "insert into dfs_id_mapping (dfsid,`group`,fileid,sendtime)values('$source_image_url','$group[1]','$image_id',now())";
						$Adddfs = $db->execute($AdddfsSql);
						$AdddfsSqlthumb = "insert into dfs_id_mapping (dfsid,`group`,fileid,sendtime)values('$thumbnail_image_url','$group[1]','$image_id_thumb',now())";
						$Adddfsthumb = $db->execute($AdddfsSqlthumb);
				}
		
				$db->disConnect();
				return true;
			}
		}
		
		
		
		
		
		public function uploadFileByFastDFS($path,$file_name){
            $url_arr = array();
            $source_image_url = "";
            $thumbnail_image_url = "";
            $fastDFDClient = new FastDFSClient();
            $fastDFDClient->initServer();
            if(!$fastDFDClient->fastDFSIsConnected()){
               $this->logger->error(sprintf("fastDFS is not connected."));
               return false;
            }

            $source_file_path = $path['src_file'];
            $thum_file_path = $path['thumb_file'];
            $uploadResult = $fastDFDClient->uploadFile($source_file_path);
//             var_dump($uploadResult);
            if(!$uploadResult){
               $this->logger->error(sprintf("upload file err. filepath=%s",$source_file_path));
               return false;
            } else {
                    $source_image_url = "/" . $uploadResult["group_name"] . "/" . $uploadResult["filename"];
                    $url_arr[0] = $source_image_url;
            }
            $uploadResult = $fastDFDClient->uploadFile($thum_file_path);
            if(!$uploadResult){
            }
            else{
                    $thumbnail_image_url = "/" . $uploadResult["group_name"] ."/" . $uploadResult["filename"];
                    $url_arr[1] = $thumbnail_image_url;
            }
            $strarr = explode(".",$file_name);
            $image_id = $strarr[0];

            $url_arr[2] = $image_id;
            $fastDFDClient->disConnect();
            return $url_arr;
		}
		/*上传文件接口
		  参数：file:文件内容，fileId,文件名称，$params:包括文件类型、user_id
		  */
		public function uploadFile($file, $fileId, $params){
			$path = $this->createFile($file,$fileId,$params);
			return $this->uploadFileByFastDFS1($path, $fileId, $params);
		}
		/*下载文件接口
		  参数：dfs_file_info,文件名称，$params:包括文件类型、user_id （目前没用到）
		  返回：文件内容
		  */
		public function downLoadFile($dfs_file_info,$params){
			$filecontent = $this->downloadFileByFastDFS($dfs_file_info);
			if(!$filecontent){
				return false;
			}
			return $filecontent;
		}  
	//如果是图片 则生成缩略图
	//如果是非图片(TXT,DOC,autio,video)，仅返回 源文件内容
	public function createFile($file, $fileId,$params) {
		$Path = array();
		
		if($params["mime"]=="image" || $params["mime"]=="avatar"){
			if($params["mime"]=="avatar"){
				$thumbWidth = $this->config->getLocalConfig('avatar_thumbwidth');
				$thumbHeight = $this->config->getLocalConfig('avatar_thumbheight');
			}else{
				$thumbWidth = $this->config->getLocalConfig('thumbwidth');
				$thumbHeight = $this->config->getLocalConfig('thumbheight');
			}
				
			$thumb = new Thumb();
			$flag = $thumb->load(realpath($file));
			if(!$flag){
				$this->logger->error(sprintf("file parse error."));
				ErrCode::echoErr(ErrCode::PARAM_ERR);
			}
			$imgWidth = $thumb->getWidth();
			$imgHeight = $thumb->getHeight();
				
			if($imgWidth >= $imgHeight){
				if($imgWidth > $thumbWidth){
					$thumb->resizeToWidth($thumbWidth);
				}else{
					$thumb->resizeToWidth($imgWidth);
				}
			}else{
				if($imgHeight > $thumbHeight){
					$thumb->resizeToHeight($thumbHeight);
				}else{
					$thumb->resizeToHeight($imgHeight);
				}
			}
			$thumbFileName = $fileId."-thumb";
			$thumbFilePath = dirname(__FILE__) . "/../temp/" . $thumbFileName;
			$savaFlag = $thumb->save($thumbFilePath);
			if(! $savaFlag){
				$this->logger->error(sprintf("Thumb file save error."));
				ErrCode::echoErr(ErrCode::FILE_SEND_ERR);
			}
			$Path["thumb_file"] = $thumbFilePath;
		}
		$Path["src_file"] = realpath($file);
		return $Path;
	}
	public function uploadFileByFastDFS1($path, $fileId, $params){

		$user_id = $params['id'];
		$source_image_url = "";
		$thumbnail_image_url = "";
		$fastDFDClient = new FastDFSClient();

		$fastDFDClient->initServer();
		if(!$fastDFDClient->fastDFSIsConnected()){

		   $this->logger->error(sprintf("fastDFS is not connected."));
		   return false;
		}
		
		$src_file_group = "";
		$src_file_name = "";
		$thumb_file_group = "";
		$thumb_file_name = "";
		$source_file_path = $path['src_file'];


		//上传原文件
		$uploadResult = $fastDFDClient->uploadFile($source_file_path);
		if(!$uploadResult){
		   $this->logger->error(sprintf("upload file err. filepath=%s",$source_file_path));
		   $fastDFDClient->disConnect();
		   return false;
		} else {
		        if($params["mime"]=="avatar" || $params["mime"]=="exam_image" || $params["mime"]== "doctor_auth"||$params["mime"]== "ast_auth"||$params["mime"]== "user_consult_doctor")
					$source_image_url = "/" . $uploadResult["group_name"] . "/" . $uploadResult["filename"];
		        else{//除了avatar之外的 image,audio,file,....都属于MMS，存储方式：filename and group 分开。
		        	$src_file_group = $uploadResult["group_name"];
		        	$src_file_name = $uploadResult["filename"];
		        }
		}
		//如果上传的是 头像：avatar 或者 多媒体图片(image)，则继续上传 缩略图，并返回对应的URL
		if($params["mime"]=="image" || $params["mime"]=="avatar"){
		//if(strpos($params["mime"],"avatar") !== false || (strpos($params["mime"],"image") !== false)){
			$thum_file_path = $path['thumb_file'];
		        $uploadResult = $fastDFDClient->uploadFile($thum_file_path);
		        if($uploadResult){
					$thumbnail_image_url = "/" . $uploadResult["group_name"] ."/" . $uploadResult["filename"];
					if(strpos($params["mime"],"image") !== false){
						$thumb_file_group = $uploadResult["group_name"];
						$thumb_file_name = $uploadResult["filename"];
		        	}
		        }

		}
		
		//将上传信息写入DB中
		//case 1: avatar 的信息写入 user_version_info表
		if($params["mime"]=="avatar"){
			$saveResult = $this->saveAvatarIdToDB($source_image_url,$thumbnail_image_url,$user_id);
			if(!$saveResult){
			   $this->logger->error(sprintf("save avatar id to DB err. source_image_url=%s, thumbnail_image_url=%s",$source_image_url,$thumbnail_image_url));
			   return false;
			}
			else{
				$fastDFDClient->disConnect();
				return $saveResult;//base version
			}
		}
		//case 2:mms多媒体信息写入fds_id_mapping表
		//如果是image,需要写入 src image 和 thumb image
		else if($params["mime"]=="image"){
			$saveResult = $this->saveMMSImg($src_file_group,$src_file_name,$thumb_file_group,$thumb_file_name,$fileId);
			if(!$saveResult){
			   $this->logger->error(sprintf("save MMS image info to DB err. fileId:%s",$fileId));
			   $fastDFDClient->disConnect();
			   return false;
			}
		}
		// exam_image, 检查图片
		else if($params["mime"]=="exam_image"){
			return $source_image_url;
		}
		else if($params["mime"]=="doctor_auth"){
			return $source_image_url;
		}		
		else if($params["mime"]=="ast_auth"){
			return $source_image_url;
		}else if($params["mime"]=="user_consult_doctor"){
			return $source_image_url;
		}
		else{//other mms files ，没有缩略图
			$saveResult = $this->saveMmsOther($src_file_group,$src_file_name,$fileId);
			if(!$saveResult){
			   $this->logger->error(sprintf("save MMS other file info to DB err. fileId=%s",$fileId));
			   $fastDFDClient->disConnect();
			   return false;
			}
		}

		$this->logger->info("FastDFS::uploadFile():Upload File Success.file_id:$fileId");
		$fastDFDClient->disConnect();
		return true;
	}
	//保存MMS图片信息 表：dfs_id_mapping
	private function saveMMSImg($src_file_group,$src_file_name,$thumb_file_group,$thumb_file_name,$fileId){
		$database = new DataBase_Mysql();
		$databaseConfigArr = array(
			"connmethod" => "",
			"driver" => "mysql",
			"host" => $this->config->getLocalConfig("MASTER_DB_HOST"),
			"username" => $this->config->getLocalConfig("MASTER_DB_USER"),
			"password" => $this->config->getLocalConfig("MASTER_DB_PASS"),
			"dbname" => $this->config->getLocalConfig("MASTER_DB_NAME"),
			"dbcharacter" => $this->config->getLocalConfig("MASTER_DB_CHARSET")
		);	
		$isConnected = $database->connect($databaseConfigArr);
		if(!$isConnected){
		    $this->logger->error(sprintf("inset dfs info to db error. src_file_name:%s,thumb_file_name=%s, fileid=%s",$src_file_name,$thumb_file_name, $fileId));  
			return false;
		}

		$sql = "INSERT INTO dfs_id_mapping (dfsid,`group`,fileid,sendtime) VALUES('$src_file_name','$src_file_group','$fileId',NOW()),('$thumb_file_name','$thumb_file_group','$fileId',NOW())";
		$result = $database->execute($sql);
		$database->disConnect();
		if(!$result){
				return false;
		}
		return true;
	}
	//保存其他信息
	private function saveMmsOther($src_file_group,$src_file_name,$fileId)
	{
		$database = new DataBase_Mysql();
		$databaseConfigArr = array(
			"connmethod" => "",
			"driver" => "mysql",
			"host" => $this->config->getLocalConfig("MASTER_DB_HOST"),
			"username" => $this->config->getLocalConfig("MASTER_DB_USER"),
			"password" => $this->config->getLocalConfig("MASTER_DB_PASS"),
			"dbname" => $this->config->getLocalConfig("MASTER_DB_NAME"),
			"dbcharacter" => $this->config->getLocalConfig("MASTER_DB_CHARSET")
		);	
		$isConnected = $database->connect($databaseConfigArr);
		if(!$isConnected){
		    $this->logger->error(sprintf("inset dfs info to db error. src_file_name:%s, fileid=%s",$src_file_name, $fileId));  
			return false;
		}

		$sql = "INSERT INTO dfs_id_mapping (dfsid,`group`,fileid,sendtime) VALUES('$src_file_name','$src_file_group','$fileId',NOW())";
		$result = $database->execute($sql);
		$database->disConnect();
		if(!$result){
				return false;
		}
		return true;
	}

	//保存头像数据到DB
	//参数：图像原图URL，缩略图URL，用户ID
	private function saveAvatarIdToDB($source_image_url,$thumbnail_image_url,$user_id){
		$database = new DataBase_Mysql();
		$databaseConfigArr = array(
			"connmethod" => "",
			"driver" => "mysql",
			"host" => $this->config->getLocalConfig("MASTER_DB_HOST"),
			"username" => $this->config->getLocalConfig("MASTER_DB_USER"),
			"password" => $this->config->getLocalConfig("MASTER_DB_PASS"),
			"dbname" => $this->config->getLocalConfig("MASTER_DB_NAME"),
			"dbcharacter" => $this->config->getLocalConfig("MASTER_DB_CHARSET")
		);	
		$isConnected = $database->connect($databaseConfigArr);
		if(!$isConnected){
		    $this->logger->error(sprintf("inset id to db error. dfsid=%s, fileid=%s",$dfsId, $fileId));  
			return false;
		}

		$sql = "update user_version_info SET base_ver = base_ver+1,
										     image_ver = image_ver +1,
											 last_update_date = now(),
		  									 source_image_url = '$source_image_url',
											 thumbnail_image_url = '$thumbnail_image_url'where user_id = '$user_id'";
		$result = $database->execute($sql);
		if($result){
			//获得当前base_ver的版本号
			$sql = "select base_ver from user_version_info where user_id = '$user_id'";
			$base_ver = $database->getOne($sql);
			$database->disConnect();
			if($base_ver)
				return $base_ver;
			else
				return false;
		}
		$database->disConnect();
		return $result;
	}
    
    /*从fastdfs下载文件*/
       public function downloadFileByFastDFS($dfs_file_info){
           $fastDFDClient = new FastDFSClient();
           $fastDFDClient->initServer();
           if(!$fastDFDClient->fastDFSIsConnected()){
               $this->logger->error(sprintf("fastDFS is not connected."));  
               return false; 
           }
           $downloadResult = $fastDFDClient->downloadFile($dfs_file_info["dfsid"],$dfs_file_info["group"]);
           $fastDFDClient->disConnect();
           if(!$downloadResult){
               $this->logger->error(sprintf("download file err."));  
               return false;
           }
           return $downloadResult;
       }
       /*根据信息(group,fileid)删除存储在fastdfs 的文件*/
       public function deleteFileByFastDFS($Url){
       	   $dfs_file_info = $this->parseUrlToArr($Url);
       	   $fastDFDClient = new FastDFSClient();
           $fastDFDClient->initServer();
           if(!$fastDFDClient->fastDFSIsConnected()){
               $this->logger->error(sprintf("fastDFS is not connected."));  
               return false; 
           }
           $deleteResult = $fastDFDClient->deleteFile($dfs_file_info["group"],$dfs_file_info["dfsid"]);
           $fastDFDClient->disConnect();
           if(!$deleteResult){
               $this->logger->error(sprintf("download file err."));  
               return false;
           }
           return $deleteResult;
       }
       public function parseUrlToArr($Url){
			$fastdfs_parms_arr = array();
			$tp = substr($Url,1);
			$x = strstr($tp,"/");
			$dfsid = substr($tp,strlen($tp)-strlen($x)+1);
			$urlarr = explode("/",$tp);
			$group = $urlarr[0];
			$fastdfs_parms_arr = array("dfsid"=>$dfsid,"group"=>$group);
			return $fastdfs_parms_arr;
       }
}
?>