<?php
define("SYSTEM_CONFIG_PATH",dirname(__FILE__) . "/../config/sysConfig.ini");

class Config {  
    private $config;  
    private $logger;
    /**
     * Enter description 取得配置文件中的配置信息
     *
     * @param String $key
     * @return String
     */
	public function getConfig($key){
		$this->Config = parse_ini_file(SYSTEM_CONFIG_PATH);//parse_ini_file() 函数解析一个配置文件，并以数组的形式返回其中的设置。
		$keyFlag = isset($this->Config[$key]);
		if(!$keyFlag){
			return "Not Exists Key!";
		} else {
			return $this->Config[$key];
		}
	}
    
    
    /**
    * 构造函数
    */
    public function __construct(){
        $this->config = parse_ini_file(SYSTEM_CONFIG_PATH);
        $this->logger = Logger::getLogger(basename(__FILE__));
    }    
    
     public function getLocalConfig($key){
        return isset($this->config[$key])?$this->config[$key]:null;
     }
}

?>
