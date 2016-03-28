<?php
/**
 * 
 * Enter description here ...
 * @author lion
 *
 */
    class FastDFSClient{
        private $fastDFS;
        private $fastConnected = false;
        private $logger;
        public function __construct(){
            $this->fastDFS = new FastDFS();
            $this->logger = Logger::getLogger(basename(__FILE__));
        }
        
        public function initServer(){
           if(!$this->fastDFS->tracker_make_all_connections()){
               //echo $this->fastDFS->get_last_error_info();
               $this->logger->error(sprintf("FastDFS server init fail. "));
               return false;
           }
           
           $fastConnection = $this->fastDFS->tracker_get_connection();
          // print_r($fastConnection);
           if(!$fastConnection){
               $this->logger->error(sprintf("FastDFS get connection fail. "));
               return false;
           }
           $this->fastConnected = true;
           return true;
        }
        
        public function fastDFSIsConnected(){
            return $this->fastConnected;
        }
        
        public function uploadFile($filePath){
            if(!$this->fastConnected) {
                $this->logger->error(sprintf("uploadFile fail. no fastConnection "));
                return false;
            }
            $uploadResult = $this->fastDFS->storage_upload_by_filename($filePath);
            if(!$uploadResult){
                $this->logger->error(sprintf("uploadFile fail. file path=%s",$filePath));
                return false;
            } else {
                $this->logger->info(sprintf("uploadFile info=%s ", v($uploadResult)));
                return $uploadResult;
            }
        }
        
        public function downloadFile($fileName, $group){
            if(!$this->fastConnected) {
                $this->logger->error(sprintf("downloadFile fail. no fastConnection "));
                return false;
            }
            $downloadResult = $this->fastDFS->storage_download_file_to_buff($group, $fileName);
            if(!$downloadResult){
                $this->logger->error(sprintf("downloadFile fail. error=%s", $this->fastDFS->get_last_error_info()));
                return false;
            } else {
                $this->logger->info(sprintf("downloadFile success. filename=%s, group=%s", $fileName, $group));
                return $downloadResult;
            }
        }
        
        public function deleteFile($group, $fileName){
        	if(!$this->fastConnected) {
        		$this->logger->error(sprintf("deleteFile fail. no fastConnection "));
        		return false;
        	}
        	$deleteResult = $this->fastDFS->storage_delete_file($group, $fileName);
        	if(!$deleteResult){
        		$this->logger->error(sprintf("deleteFile fail. error=%s", $this->fastDFS->get_last_error_info()));
        		return false;
        	} else {
        		$this->logger->info(sprintf("deleteFile success. filename=%s, group=%s", $fileName, $group));
        		return $deleteResult;
        	}
        }
        
        public function disConnect(){
            $this->fastDFS->tracker_close_all_connections();
        }
    }
?>