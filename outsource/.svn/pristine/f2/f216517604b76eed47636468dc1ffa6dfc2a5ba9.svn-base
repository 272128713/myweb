<?php
class MemcacheManager{
	private $memcache;
	private $serverAddr;
	private $port;
	public function __construct($serverAddr, $port){
		$this->memcache = new Memcache();
		$this->serverAddr = $serverAddr;
		$this->port = $port;
	}
	public function getMemcache(){
		for($i = 0 ; $i < 3 ;$i++){
			$flag = $this->memcache->connect($this->serverAddr,$this->port);
			if($flag) return $this->memcache;
		}
		return null;
	}
}
?>