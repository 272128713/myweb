<?php
    class PushServiceManager{
        private $config;
        private $logger;
        private $psServer;
        private $psSession;        
       // public static 
        /**
         * 
         * 构造函数
         */
        public function __construct($freepp,$databaseManager){
            $this->logger = Logger::getLogger(basename(__FILE__));
            $this->config = new Config();
            $this->psServer = $databaseManager->getPushApiServerByFreePP($freepp);
            $this->logger->debug(sprintf("push message by freepp:%s" , $freepp));
           // $this->log->write("psServer=" . $this->psServer);
            $this->logger->debug(sprintf("push message by remote server:%s" , $this->psServer));
           // $this->psServer = $this->config->getConfig("psservicepath");
            $this->psSession = $this->config->getConfig("psservicesession");
        }
        /**
         * 
         * 发起PushService请求
         * @param String $api
         * @param String $params
         */
        public function sendMessage($params){
            if(!$this->psServer) return false;
            $apiPath = "http://" . $this->psServer . $this->config->getConfig("psservicepath") . "push_message_from_server.php";            
            $this->logger->debug(sprintf("push url=%s" , $apiPath));
            /*
             $params = array(array("session",true),array("freepp",true),"freeppdst","mobiledst",array("srcappname",true),
	                array("dstappname",true),array("msg",true),array("type",true),array("qos",true));
             */
            //构造PUSHMESSAGE要求的参数
            //$this->log->write($apiPath,1,"SEND_MESSAGE");
            $psParams = array(
                "session" => $this->psSession,
                "freepp" => $params["freepp"],
                "freeppdst" => $params["freeppdst"],
                "mobiledst" => $params["mobiledst"],
                "srcappname" => "ipphone",
                "dstappname" => "ipphone",
                "msg" => $params["msg"],
                "type" => $params["type"],//;"MMS",
                "qos" => 1
            );
            
            if($params["type"] == "NCL"){
            	$psParams["nclper"] = $params["nclper"];
            }
            $result = httpRequest($apiPath,$psParams,"post");
            $this->logger->debug(sprintf("push result=%s" , $result));
            //$this->log->write("aaaa" . $result,1,"SEND_MESSAGE");
            return $result;
        }
    }
?>