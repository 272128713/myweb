<?php
//消息推送
include(dirname(__FILE__) . "/TcpConnection.php");
class MecManager{
    private $logger;
    private $sender;
    private $accepter = array();
    private $TcpPool = array();
    public $msgObject;
    private $config;
    public function __construct($sender, $msgObject, $accepters){
        $this->logger = Logger::getLogger(basename(__FILE__));
        $this->sender = $sender;
		$msgObject['uuid']=$sender.time().rand(10000,99999);//uniqid(rand(10000,99999));
        $this->msgObject = $msgObject;
        $this->config = new Config();
        $this->initAccepter($accepters);
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $accepters
     */
    private function initAccepter($accepters){
        foreach ($accepters as $accepter){
            if(!isset($this->TcpPool[$accepter["mec_ip"] . $accepter["mec_port"]])){
	            $tcpConnection = new TcpConnection($accepter["mec_ip"], $accepter["mec_port"]);
	    	    if(!$tcpConnection->isConnected()){
	                $this->logger->error(sprintf("Mec server %s connect fail. port is %s",$accepter["mec_ip"],$accepter["mec_port"]));
	                continue;
	    	    }
	    	    $tcpConnection->setReadTimeout(5, 0);
	    	    $this->TcpPool[$accepter["mec_ip"] . $accepter["mec_port"]] = $tcpConnection;
	        }
	        if(!isset($this->accepter[$accepter["mec_ip"] . $accepter["mec_port"]])){
	            $this->accepter[$accepter["mec_ip"] . $accepter["mec_port"]] = array();
	        }
	        if(strcmp($accepter["mid"],"0000") === 0) {
	            if($this->logger->isDebugEnabled()){
	                $this->logger->debug(sprintf("mid is 0000 skip send. accepter info=%s", v($accepter)));
	            }
	            continue;
	        }
        	$this->accepter[$accepter["mec_ip"] . $accepter["mec_port"]][] = array(
	            "dstmid" => $accepter["mid"],
	            "push" => $accepter["push_service_type"],
	            "lpsserver" => $accepter["lps_ip"],
	            "lpsport" => $accepter["lps_port"]
	        );
        }
    }
    /**
     * 
     * Enter description here ...
     */
    public function sendMessage(){
        $sendFlag = false;        
/*        $ttl = $this->config->getConfig("ttl_" . $this->msgObject["type"]);
        if(!$ttl){            
    	    $ttl = $this->config->getConfig("ttl_default");
    	    $this->logger->error(sprintf("find ttl is null. ttl is %s,ttl_defalut=%s", ("ttl_" . $this->msgObject["type"]),$ttl));
    	}*/
	$ttl = 1296000;
        foreach ($this->TcpPool as $key => $tcpPool){
            $message = "SNE " . $this->sender . " " . json_encode($this->accepter[$key]) . " $ttl " . $this->msgObject["type"] . " " . json_encode($this->msgObject) . " \n";
            $this->logger->debug("$key send message:" . $message);
            $result = $tcpPool -> tcpSend($message);
            $this->logger->debug("$key send result:" . $result);
            if(!$result){
	            $this->logger->error(sprintf("Mec server %s send fail[1]. Tcp send error.", $key));
	        }else{
	            $resArr = explode("\n", $result);
	            $resCode = explode(" ", $resArr[0]);
	            if($resCode[0] != 0){
	                $this->logger->error(sprintf("Mec server %s send fail[2]. MEC error. result=%s", $key, $result));
	            }else{
	                $sendFlag = true;
	            }
	        }
	        $tcpPool->close();
        }
        return $sendFlag;
    }

}
?>
