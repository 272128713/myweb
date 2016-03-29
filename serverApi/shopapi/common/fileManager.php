<?php
// trunk add
 /**
  * 
  * Enter description here ...
  * @author lion ,jerry
  *
  文件包括：图片类，其他文件
  图片：需要生成缩略图。
  其他文件：直接上传
  
  */
//branch add
class fileManager {
	private $config;
	private $logger;
	public function __construct() {
		$this->logger = Logger::getLogger(basename(__FILE__));
		$this->config = new Config();
	}
	public function uploadFile($file, $fileTempName, $user_id, $serverConfig) {
		if($serverConfig == 'fastdfs'){
			$Path = $this->createFile($file, $fileTempName);
			return $this->uploadFileByFastDFS($fileTempName, $Path, $user_id);
		}else if($serverConfig == 's3'){
		}else{
			$this->logger->error(sprintf("server config error. serverConfig=%s", $serverConfig));
			return false;
		}
	}
	public function uploadFileByFastDFS($fileTempName, $Path, $user_id) {
		$fastDFDClient = new FastDFSClient();
		$fastDFDClient->initServer();
		if(! $fastDFDClient->fastDFSIsConnected()){
			$this->logger->error(sprintf("fastDFS is not connected."));
			return false;
		}
		$path_array = array();
		$orgFileName = $mobile;
		// 按照规则定义，生成缩略图文件名
		$LargeThumbLen = $this->config->getLocalConfig('LargeHeadImgSize');
		
		$LargeThumbSize = $LargeThumbLen . 'x' . $LargeThumbLen;
		$LargeThumbFileName = $prefix . '_' . $mobileNoPlus[1] . '_' . $LargeThumbSize;
		$path_array[] = $orgFileName;
		$path_array[] = $LargeThumbFileName;
		
		//check and delele exist 1rst
		//头像不需要查看是否DB中存在，所以要区分头像。
		//非头像的数据image_id是唯一的。所以需要在 fastdfs文件名保证唯一，则不必考虑 是否存在。
		//fastdfs数据删除，依赖DB中的 图片，主题的存在时间期限
		/*
		foreach ($path_array as $filename){
			$fastDFSFileId = $this->getFastDFSFIleIdByFileId($filename);
			if($fastDFSFileId){
				//删除fastdfs中的文件
				$fastDFDClient->deleteFile($fastDFSFileId["group"], $fastDFSFileId["dfsid"]);
				//删除DB中的文件
				$this->deleteFileIdMappingToDB($filename);
			}
		}*/
		//then upload file to fastdfs
		$source_image_url = "";
		$thumbnail_image_url = "";
		foreach($Path as $filename=>$filepath){
			$uploadResult = $fastDFDClient->uploadFile(realpath($filepath[0]));
			$dfsid = $uploadResult['filename'];
			if(! $uploadResult){
				$this->logger->error(sprintf("upload file err. filepath=%s", realpath($filepath[0])));
				return false;
			}else {
				if($filepath[1] == 1)
					$source_image_url = "/" . $uploadResult["group_name"] . $dfsid;
				else
					$thumbnail_image_url = "/" . $uploadResult["group_name"] . $dfsid;

           }
			unlink($filepath[0]);
		}
		$saveResult = $this->saveFileIdMappingToDB($source_image_url,$thumbnail_image_url,$user_id);
		if(!$saveResult){
		   $this->logger->error(sprintf("save file id mapping err. dfsid=%s, filename=%s",$dfsid,$filename));  
		   return false;
		}
		$this->logger->info("FastDFS::uploadFile():Upload File Success.DfsId: $dfsid, FileName: $filename");
		$fastDFDClient->disConnect();
		return true;
	}
	
	private function saveFileIdMappingToDB($source_image_url,$thumbnail_image_url,$user_id){
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
    											 last_update_date = time(),
    		  									 source_image_url = '$source_image_url',
    											 thumbnail_image_url = '$source_image_url'where user_id = $user_id";
 			$result = $database->execute($sql);
            $database->disConnect();
    		return $result;
    }
    
    private function deleteFileIdMappingToDB($fileId){
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
    		$this->logger->error(sprintf("inset id to db error. fileid=%s",$fileId));
    		return false;
    	}
    	
    	//$sql = "DELETE FROM dfs_id_mapping WHERE fileid = '$fileId'";
    	//$result = $database->execute($sql);
    	$database->disConnect();
    	return $result;
    }

	public function createFile($file, $fileTempName) {
		$Path = array();
		$orgFileName = $fileTempName;
		// 按照规则定义，生成缩略图文件名
		$LargeThumbLen = $this->config->getLocalConfig('LargeHeadImgSize');
		
		$LargeThumbSize = $LargeThumbLen . 'x' . $LargeThumbLen;
		$LargeThumbFileName = $prefix . '_' . $mobileNoPlus[1] . '_' . $LargeThumbSize;
		
		// 生成缩略图的临时保存目录
		define("THUMB_PATH", dirname(__FILE__) . "/../temp/");
		$LargeThumbFilePath = THUMB_PATH . $LargeThumbFileName;
		//echo "Large Path:".$LargeThumbFilePath."\n";
		$thumb = new Thumb();
		$flag = $thumb->load(realpath($file));
		if(!$flag){
			$this->logger->error(sprintf("file parse error."));
			ErrCode::echoErr(ErrCode::PARAM_ERR);
		}
		$imgWidth = $thumb->getWidth();
		$imgHeight = $thumb->getHeight();
		//echo "Image Width:".$imgWidth."\n";
		//echo "Image Height:".$imgHeight."\n";
		if($imgWidth != $imgHeight){
			$Path[$orgFileName] = array(realpath($file),1);
		}else{
			// 处理原图的宽高为1:1的逻辑
			if($imgWidth >= $LargeThumbSize){
				// 如果大于等于256，生成两种类型的缩略图
				// just 生成256x256大小的缩略图
				$thumb->resizeToWidth($LargeThumbLen);
				$thumb->resizeToHeight($LargeThumbLen);
				$savaLargeThumbFlag = $thumb->save($LargeThumbFilePath);
				if(! $savaLargeThumbFlag){
					$this->logger->error(sprintf("Thumb file save error."));
					ErrCode::echoErr(ErrCode::FILE_SEND_ERR);
				}
				$Path[$orgFileName] = array(realpath($file),2);
				$Path[$LargeThumbFileName] = $LargeThumbFilePath;
			}else{
				$Path[$orgFileName] = array(realpath($file),2);
			}
		}
	
		return $Path;
	}
       //下载文件
       public function getFile($store_server,$file,$bucket){
       	   //if($store_server=="s3")
       	   	   //return $this->getFileFromS3($file,$bucket);
       	   if($store_server=="fastdfs")
       	   	   return $this->getFileFromFastDFS($file);
       }
       //从s3中下载文件
       public function getFileFromS3($filename,$bucket){
       	   $s3_server = $this->config->getLocalConfig('s3_server');
       	   $awsAccessKey = $this->config->getLocalConfig('awsAccessKey');
		   $awsSecretKey = $this->config->getLocalConfig('awsSecretKey');
		   $url = $this->config->getLocalConfig('url');
		   $s3 = new S3($awsAccessKey, $awsSecretKey, false, $url);
		   $fileinfo = $s3->getObjectInfo($bucket, $filename);
		   if(!$fileinfo){
		       $fileobj = null;
		   }else{
		   	   $fileobj = $s3->getObject($bucket, $filename);
		   }
		   
		   $this->logger->debug("filename:".$filename. " fileinfo:"); 
		   $this->logger->debug($fileinfo);  
		   if($fileobj){ 
		   	   $this->logger->debug("filename:".$filename. " filehash:".md5($fileobj->body)); 
		   	   return $fileobj->body;
		   }else{
		   	   return "";
		   }
       }
       
       //从fastdfs中下载文件
       public function getFileFromFastDFS($fileId){
           /*$fastDFSFileId = $this->getFastDFSFIleIdByFileId($fileId);
           if(!$fastDFSFileId){
               $this->logger->error(sprintf("getFastDFSFIleIdByFileId fail id=%s.",$fileId));  
               return false; 
           }*/
           $fastDFDClient = new FastDFSClient();
           $fastDFDClient->initServer();
           if(!$fastDFDClient->fastDFSIsConnected()){
               $this->logger->error(sprintf("fastDFS is not connected."));  
               return false; 
           }
           
           //$downloadResult = $fastDFDClient->downloadFile($fastDFSFileId["dfsid"],$fastDFSFileId["group"]);
           $downloadResult = $fastDFDClient->downloadFile($fileId["dfsid"],$fileId["group"]);
           if(!$downloadResult){
               $this->logger->error(sprintf("download file err."));  
               return false;
           }
           $fastDFDClient->disConnect();
           return $downloadResult;
       }
}
?>