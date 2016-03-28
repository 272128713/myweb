<?php 
/****************************************
 *log日志类
 *用于输出服务器端执行log和debug时的log.
 *@class name:SysLog
 *@package:common
 *@author:lion.wei
 *@verision:
 *@date:
 ****************************************/
class SysLog {
	private $config;
	/**
	 * 数据log信息
	 * @param string $message 输出的日志信息
	 * @param string $logLevel 日志的级别 默认为Debug
	 * @param string $className 调用日志的类名
	 */	
    function write($message , $logLevel=1 ,$function = "")
    {
		$debug = $this->config->getConfig("debuglog");
		$logPath = $this->config->getConfig("logpath");
    	if($logLevel == 1)
    	{
	 		if($debug == 1){
				$logFileName =  $logPath . "xe3101_debug_" . date("Ymd"). ".log" ;
				/*输出时间*/
				$outputTime = "[" . date("Ymd H:i:s") . "]:"; 
				//输出内容组装
				$fileContent = $outputTime . " " . $function."===" . $message . "\r\n";
				safe_file_put_contents($logFileName,$fileContent);
	 		}
    	} else if($logLevel == 2)
    	{
			$logFileName =  $logPath . "xe3101_message_" . date("Ymd"). ".log" ;
			/*输出时间*/
			$outputTime = "[" . date("Ymd H:i:s") . "]:"; 
			//输出内容组装
			$fileContent = $outputTime . " " . $function."===" . $message . "\r\n";
			safe_file_put_contents($logFileName,$fileContent);
    	} else if($logLevel == 3)
    	{
			$logFileName =  $logPath . "xe3101_system_" . date("Ymd"). ".log" ;
			/*输出时间*/
			$outputTime = "[" . date("Ymd H:i:s") . "]:"; 
			//输出内容组装
			$fileContent = $outputTime . " " . $function."===" . $message . "\r\n";
			safe_file_put_contents($logFileName,$fileContent);
    	}
	}  
	
	public function __construct(){
		$this->config = new Config();
	}
}

?>