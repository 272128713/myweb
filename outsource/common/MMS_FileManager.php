<?php
 /**
  * 
  * Enter description here .Old file process logic code.
  * @author Wing.Geng
  *
  */
   class MMS_FileManager{
       private $isEnableDFS;
       private $config;
       private $logger;
       public function __construct(){
           $this->logger = Logger::getLogger(basename(__FILE__));
           $this->config = new Config();
       }
       
	   public function uploadFile($file, $fileId, $params){
	   		return $this->uploadFileByFastDFS($file, $fileId, $params);
       }
       
       public function downLoadFile($fileId){
			$filecontent = $this->downloadFileByFastDFS($fileId);
			return $filecontent;
       }
       
       public function uploadFileByFastDFS($file, $fileId, $params){
           include(dirname(__FILE__) . "/FastDFSClient.php");	
           $fastDFDClient = new FastDFSClient();
           $fastDFDClient->initServer();
           if(!$fastDFDClient->fastDFSIsConnected()){
               $this->logger->error(sprintf("fastDFS is not connected."));  
               return false; 
           }
           
           $uploadResult = $fastDFDClient->uploadFile(realpath($file));
           if(!$uploadResult){
               $this->logger->error(sprintf("upload file err. filepath=%s",realpath($file)));  
               return false;
           } else {
               $saveResult = $this->saveFileIdMappingToDB($uploadResult["filename"],$fileId,$uploadResult["group_name"]);
               if(!$saveResult){
                   $this->logger->error(sprintf("save file id mapping err. dfsid=%s, fileid=%s",$uploadResult["filename"],$fileId));  
                   return false;
               }
           }
           
           if(strpos($params["mime"],"image") !== false){
                   // $startTime = time();
                    $thumbWidth = $this->config->getConfig("thumbwidth");
                    $thumbHeight = $this->config->getConfig("thumbheight");
                    include(dirname(__FILE__) . "/Thumb.php");
                    $thumb = new Thumb();
                    $thumb->load(realpath($file));
                    $imgWidth = $thumb->getWidth();
                    $imgHeight = $thumb->getHeight();

                    if($imgWidth >= $imgHeight){
                    	if($imgWidth > $thumbWidth){
                    		$thumb->resizeToWidth($thumbWidth);
                    	} else {
                    		$thumb->resizeToWidth($imgWidth);
                    	}
                    } else {
                    	if($imgHeight > $thumbHeight){
                    		$thumb->resizeToHeight($thumbHeight);
                    	} else {
                    		$thumb->resizeToHeight($imgHeight);
                    	}
                    }
                   // $endTime = time();
                    $thumbFileName = $params["id"] . "-thumb";
                    $thumbPath = dirname(__FILE__) . "/../temp/" . $thumbFileName;
                    $savaFlag =  $thumb->save($thumbPath);
                    $uploadResult = $fastDFDClient->uploadFile(realpath($thumbPath));
                    if($uploadResult){
                       $saveResult = $this->saveFileIdMappingToDB($uploadResult["filename"],$thumbFileName,$uploadResult["group_name"]);
                       if(!$saveResult){
                           $this->logger->error(sprintf("save file id mapping err. dfsid=%s, fileid=%s",dfsId,$fileId));  
                       }
                    }
                    unlink($thumbPath);
                }
           $fastDFDClient->disConnect();
           return true;
       }
       
       public function downloadFileByFastDFS($fileId){
           include(dirname(__FILE__) . "/FastDFSClient.php");
           $fastDFSFileId = $this->getFastDFSFIleIdByFileId($fileId);
           if(!$fastDFSFileId){
               $this->logger->error(sprintf("getFastDFSFIleIdByFileId fail id=%s.",$fileId));  
               return false; 
           }
           $fastDFDClient = new FastDFSClient();
           $fastDFDClient->initServer();
           if(!$fastDFDClient->fastDFSIsConnected()){
               $this->logger->error(sprintf("fastDFS is not connected."));  
               return false; 
           }
           
           $downloadResult = $fastDFDClient->downloadFile($fastDFSFileId["dfsid"],$fastDFSFileId["group"]);
           if(!$downloadResult){
               $this->logger->error(sprintf("download file err."));  
               return false;
           }
           $fastDFDClient->disConnect();
           return $downloadResult;
       }
       
       private function saveFileIdMappingToDB($dfsId, $fileId, $group){
           $database = new DataBase_Mysql();
           $databaseConfigArr = array(
    			"connmethod" => "",
    			"driver" => "mysql",
    			"host" => $this->config->getConfig("MASTER_DB_HOST"),
    			"username" => $this->config->getConfig("MASTER_DB_USER"),
    			"password" => $this->config->getConfig("MASTER_DB_PASS"),
    			"dbname" => $this->config->getConfig("MASTER_DB_NAME"),
    			"dbcharacter" => $this->config->getConfig("MASTER_DB_CHARSET")
    		);	
    		$isConnected = $database->connect($databaseConfigArr);
    		if(!$isConnected){
    		    $this->logger->error(sprintf("inset id to db error. dfsid=%s, fileid=%s",$dfsId, $fileId));  
    			return false;
    		}
    		
    		$sql = "replace INTO dfs_id_mapping(dfsid,fileid,sendtime,`group`) VALUES ( '$dfsId','$fileId',NOW(),'$group')";
            $result = $database->execute($sql);
            $database->disConnect();
    		return $result;
       }
       
       private function getFastDFSFIleIdByFileId($fileId){
           $database = new DataBase_Mysql();
           $databaseConfigArr = array(
    			"connmethod" => "",
    			"driver" => "mysql",
    			"host" => $this->config->getConfig("MASTER_DB_HOST"),
    			"username" => $this->config->getConfig("MASTER_DB_USER"),
    			"password" => $this->config->getConfig("MASTER_DB_PASS"),
    			"dbname" => $this->config->getConfig("MASTER_DB_NAME"),
    			"dbcharacter" => $this->config->getConfig("MASTER_DB_CHARSET")
    		);	
    		$isConnected = $database->connect($databaseConfigArr);
    		if(!$isConnected){
    		    $this->logger->error(sprintf("inset id to db error. dfsid=%s, fileid=%s",$dfsId, $fileId));  
    			return false;
    		}
    		
    		$sql = "SELECT dfsid,`group` FROM dfs_id_mapping where fileid='$fileId'";
            $result = $database->getRow($sql);
            $database->disConnect();
    		return $result;
       }
}
?>