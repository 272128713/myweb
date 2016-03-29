<?php

class MqttStatus{
    private $logger;
    public function __construct(){
       $this->logger = Logger::getLogger(basename(__FILE__));
    }
    public function getMqttOnline($ip, $port, $mid){
        $midLen = strlen($mid);
        $request = pack("CICSa$midLen",0xFE,80,1,$midLen,$mid);
        $o_conn = new TcpConnection($ip,$port);
        if($o_conn->isConnected()){
            $result = $o_conn->tcpSend($request);
            $o_conn->close();
            //如果请求发送失败直接返回1
            if(!$result) return 1;
            
            $FOMAT_USERINFO = "Ccmd_type/Iseq_no/Csub_type/cret_code/ndata_len/Cstate/nidlen/nnamelen/npasslen/naddlen";
            $ret = $this->unPackBinbuf($FOMAT_USERINFO,$result);
            //print_r($ret);
            if(!$ret) {
                $this->logger->error(sprintf("get status error. ip=%s,port=%s,mid=%s,result=%s",$ip, $port, $mid,$result));
                return 0;
            }
            if($this->logger->isDebugEnabled()){
                $this->logger->debug(sprintf("ip=%s,port=%s,mid=%s,result=%s",$ip, $port, $mid,$result));
            }
            if($ret['ret_code'] == -1 || $ret['state'] != 1)
            {
                return 0;
            } else {
                return 1;
            }
            
            $FOMAT_USERINFO = "Ccmd_type/Iseq_no/Csub_type/cret_code/ndata_len/Cstate/nidlen/nnamelen/npasslen/naddlen";
			$ret = unPackBinbuf($FOMAT_USERINFO,$result);
			//print_r($ret);
			if($ret['ret_code'] == -1)
			{
			        return 0;
			}
			$FOMAT_USERINFO1 = "Ccmd_type/Iseq_no/Csub_type/cret_code/ndata_len/Cstate/nidlen/nnamelen/npasslen/naddlen/a".$ret['idlen']."mid/a".$ret['namelen']."name/a".$ret['passlen']."pass/a".$ret['addlen']."address";
			$ret = unPackBinbuf($FOMAT_USERINFO1,$result);
			if($ret['ret_code'] == -1)
			{
			        return 0;
			}
			else
			        return 1;

        }else{
        	//如果请mqtt server连接失败直接返回 1
            $this->logger->error(sprintf("mqtt connect error ip=%s, port=%s",$ip, $port));
            return 1;
        }
    }
 
    //return array 
    private function unPackBinbuf($fmt,$inbuf){
        if(!$inbuf) return null;
    	$ret = unpack($fmt,$inbuf);
    	if(!is_array($ret) || count($ret)<=0 || is_null($ret) ){
            	return null;
    	}
    	foreach($ret as &$value){
            	if(is_string($value)){
                    	$value = strtok($value,"\0");
            	}
    	}
    	return $ret;
    }
}



?>
