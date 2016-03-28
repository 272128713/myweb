<?php
	include(dirname(__FILE__) . "/DataBase_Mysql.php");
	include(dirname(__FILE__) . "/../MemcacheManager.php");
class DatabaseManager{
	private $conn;
	private $groupConn;
	private $circleConn;
	private $contactConn;
	private $infoConn;	//数据库链接对象
	private $fitConn; //健康宝数据库链接对象
	private $prefix='fit_';  //定义前缀
	private $yinxconn;
	private $logger;
	public function __construct(){
		$this->logger = Logger::getLogger(basename(__FILE__));
	}
	
	//取得主业务数据库配置信息
	private function getMasterDbConfig(){
		$config = new Config();
		$databaseConfigArr = array(
			"connmethod" => "",
			"driver" => "mysql",
			"host" => $config->getConfig("MASTER_HOST"),
			"username" => $config->getConfig("MASTER_USER"),
			"password" => $config->getConfig("MASTER_PASS"),
			"dbname" => $config->getConfig("MASTER_NAME"),
			"dbcharacter" => $config->getConfig("MASTER_CHARSET")
		);	
		return 	$databaseConfigArr;
	}

	//获取群和多人会话数据库配置信息
	public function getGroupDbConfig(){
		$config = new Config();
		$databaseConfigArr = array(
			"connmethod" => "",
			"driver" => "mysql",
			"host" => $config->getConfig("GROUP_HOST"),
			"username" => $config->getConfig("GROUP_USER"),
			"password" => $config->getConfig("GROUP_PASS"),
			"dbname" => $config->getConfig("GROUP_NAME"),
			"dbcharacter" => $config->getConfig("GROUP_CHARSET")
		);	
		return 	$databaseConfigArr;
	}
	

	//获取医信数据库配置信息
	public function getYixinConfig(){
		$config = new Config();
		$databaseConfigArr = array(
				"connmethod" => "",
				"driver" => "mysql",
				"host" => $config->getConfig("YIXIN_HOST"),
				"username" => $config->getConfig("YIXIN_USER"),
				"password" => $config->getConfig("YIXIN_PASS"),
				"dbname" => $config->getConfig("YIXIN_NAME"),
				"dbcharacter" => $config->getConfig("YIXIN_CHARSET")
		);
		return 	$databaseConfigArr;
	}
	public function getCircleDBConfig(){
	    $config = new Config();
		$databaseConfigArr = array(
			"connmethod" => "",
			"driver" => "mysql",
			"host" => $config->getConfig("CIRCLE_HOST"),
			"username" => $config->getConfig("CIRCLE_USER"),
			"password" => $config->getConfig("CIRCLE_PASS"),
			"dbname" => $config->getConfig("CIRCLE_NAME"),
			"dbcharacter" => $config->getConfig("CIRCLE_CHARSET")
		);	
		return $databaseConfigArr;
	}

	public function getContactDBConfig(){
	    $config = new Config();
		$databaseConfigArr = array(
			"connmethod" => "",
			"driver" => "mysql",
			"host" => $config->getConfig("CONTACT_HOST"),
			"username" => $config->getConfig("CONTACT_USER"),
			"password" => $config->getConfig("CONTACT_PASS"),
			"dbname" => $config->getConfig("CONTACT_NAME"),
			"dbcharacter" => $config->getConfig("CONTACT_CHARSET")
		);	
		return $databaseConfigArr;
	}

	public function getInfoDBConfig(){
	    $config = new Config();
		$databaseConfigArr = array(
			"connmethod" => "",
			"driver" => "mysql",
			"host" => $config->getConfig("INFO_HOST"),
			"username" => $config->getConfig("INFO_USER"),
			"password" => $config->getConfig("INFO_PASS"),
			"dbname" => $config->getConfig("INFO_NAME"),
			"dbcharacter" => $config->getConfig("INFO_CHARSET")
		);	
		return $databaseConfigArr;
	}
	public function getFitpayDBConfig(){
		$config = new Config();
		$databaseConfigArr = array(
				"connmethod" => "",
				"driver" => "mysql",
				"host" => $config->getConfig("FITPAY_HOST"),
				"username" => $config->getConfig("FITPAY_USER"),
				"password" => $config->getConfig("FITPAY_PASS"),
				"dbname" => $config->getConfig("FITPAY_NAME"),
				"dbcharacter" => $config->getConfig("FITPAY_CHARSET")
		);
		return $databaseConfigArr;
	}
 
	//返回数据库链接
	public function getConn(){
		$database = new DataBase_Mysql();
		$isConnected = $database->connect($this->getMasterDbConfig());
		if($isConnected){
			$this->conn = $database;
			return $this->conn;
		} else {
			return null;
		}
	}
	
	//返回医信数据库链接
	public function getYinxinConn(){
		$database = new DataBase_Mysql();
		$isConnected = $database->connect($this->getYixinConfig());
		if($isConnected){
			
			$this->yinxconn = $database;
			return $this->yinxconn;
		} else {
			return null;
		}
	}
	public function destoryConn(){
		$this->conn->disConnect();
	}
	//建立群多人会话数据库连接
	public function getGroupConn(){
		$database = new DataBase_Mysql();
		$isConnected = $database->connect($this->getGroupDbConfig());
		if($isConnected){
			$this->groupConn = $database;
			return $this->groupConn;
		} else {
			return null;
		}
	}
	public function destoryGroupConn(){
		$this->groupConn->disConnect();
	}
	//建立圈子数据连接
	public function getCircleConn(){
		$database = new DataBase_Mysql();
		$isConnected = $database->connect($this->getCircleDbConfig());
		if($isConnected){
			$this->circleConn = $database;
			return $this->circleConn;
		} else {
			return null;
		}
	}
	public function destoryCircleConn(){
		$this->circleConn->disConnect();
	}
	//建立联系人数据库连接
	public function getContactConn(){
		$database = new DataBase_Mysql();
		$isConnected = $database->connect($this->getContactDBConfig());
		if($isConnected){
			$this->contactConn = $database;
			return $this->contactConn;
		} else {
			return null;
		}
	}
	public function destoryContactConn(){
		$this->contactConn->disConnect();
	}

	//建立健康检查疾病检查
	public function getInfoConn(){
		$database = new DataBase_Mysql();
		$isConnected = $database->connect($this->getInfoDBConfig());
		if($isConnected){
			$this->infoConn = $database;
			return $this->infoConn;
		} else {
			return null;
		}
	}
	public function destoryInfoConn(){
		$this->infoConn->disConnect();
	}
	//建立健康宝数据库链接
	public function getFitConn(){
		$database = new DataBase_Mysql();
		$isConnected = $database->connect($this->getFitpayDBConfig());
		if($isConnected){
			$this->fitConn = $database;
			return $this->fitConn;
		} else {
			return null;
		}
	}
	public function destoryFitConn(){
		$this->fitConn->disConnect();
	}
	
	
	/*---------------------------wind------------------------------------------*/
	/**
	 * 判断用户名是否存在
	 */
	public function checkUsernameInvalid($username,$doc){
		$sql = "SELECT * FROM mall_member where $doc='$username'";
		//echo $sql;
		$username = $this->conn->getRow($sql);
		return $username?$username:false;
	}
	
	/**
	 * 生成SESSION,如果有，则返回已经存在的
	 */
	public function createSession($user_id){
	
		$this->clearSession($user_id);  
		$session = getRandomID(32);
	
		return $session;
	}
	
	/**
	 * 根据user_id清除cache里面的session
	 */
	function clearSession($user_id){
		$session=$this->conn->getOne("select session from mall_user_session_info where user_id='$user_id'");
		$config = new Config();
		$server = $config->getLocalConfig("sessionmemcacheserver");
		$serverRow = explode(":", $server);
		$mem = new MemcacheManager($serverRow[0],$serverRow[1]);
		$mem = $mem->getMemcache();
		if($mem) {
			if($session != ""){
				$memResult = $mem->delete($session);
				if (!$memResult){
					$this->logger->error("delete fail. clear session from memcached fail. session is [$session]");
				}
			}
			$mem->close();
		}else{
			
			 $this->logger->error("memcached connect fail. clear session from memcached fail. session is [$session]");
		}
	}
	
	/**
	 *
	 * 更新SESSION信息
	 * @param Array $sessionArr
	 */
	public function addSession($sessionArr,$user_id){
		$this->conn->startTrans();
		$crc_session = abs(crc32($sessionArr["session"]));
		$updateSession = "update mall_user_session_info set client_type = '" . $sessionArr["client_type"] . "',
    												session = '" . $sessionArr["session"] . "',
    												mid = '" . $sessionArr["mid"] . "',
    												push_service_type = '" . $sessionArr["push_service_type"] . "',
    												mec_ip = '" . $sessionArr["mec_ip"] . "',
    												mec_port = '" . $sessionArr["mec_port"] . "',
    												lps_ip = '" . $sessionArr["lps_ip"] . "',
    												lps_port = '" . $sessionArr["lps_port"] . "',
	    												last_get_session_date = NOW(),
	    												session_hash = $crc_session where user_id = '$user_id'";
		$this->conn->execute($updateSession);
		if($this->conn->hasFailedTrans()){
			$this->conn->completeTrans(false);
			return false;
		} else {
			$this->conn->completeTrans(true);
			return true;
		}
	}
	/**
	 * 给用户分配服务器并返回
	 */
	public function dispatchServerAndGetInfo($user_id){
		//目前只是lps服务器需要动态分配，其他暂时不用，但算法可以随时改变
		$getMecSql = "select local_ip,local_port from mall_server_config where category = 1 and is_enable = 1"; //获取MEC地址
		$mecArr = $this->conn->getRow($getMecSql);
		if($mecArr){
			$mec_ip = $mecArr['local_ip'];
			$mec_port = $mecArr['local_port'];
		}else
			return false;
			
		$getLpsApiSql = "select ip,port from mall_server_config where category = 2 and is_enable = 1 and  (max_loading - curr_loading) > 0  ORDER BY max_loading - curr_loading desc"; //获取lps地址
		$lpsArr = $this->conn->getRow($getLpsApiSql);
		if($lpsArr){
			$lps_ip = $lpsArr['ip'];
			$lps_port = $lpsArr['port'];
		}else
			return false;
	
		$getHttpApiSql = "select ip,port from mall_server_config where category = 3 and is_enable = 1"; //获取http api地址
		$httpApiArr = $this->conn->getRow($getHttpApiSql);
		if($httpApiArr){
			$api_ip = $httpApiArr['ip'];
			$api_port = $httpApiArr['port'];
		}else
			return false;
			
		$getNewsApiSql = "select ip,port from mall_server_config where category = 4 and is_enable = 1"; //获取new api地址
		$newApiArr = $this->conn->getRow($getNewsApiSql);
		if($newApiArr){
			$news_ip = $newApiArr['ip'];
			$news_port = $newApiArr['port'];
		}else
			return false;
			
		$getFileApiSql = "select ip,port from mall_server_config where category = 5 and is_enable = 1"; //获取file server地址
		$fileApiArr = $this->conn->getRow($getFileApiSql);
		if($fileApiArr){
			$file_ip = $fileApiArr['ip'];
			$file_port = $fileApiArr['port'];
		}else
			return false;
		$serverArr = array(
				"mec_ip" => $mec_ip,
				"mec_port" => $mec_port,
				"lps_ip" => $lps_ip,
				"lps_port" => $lps_port,
				"api_ip" => $api_ip,
				"api_port" => $api_port,
				"news_ip" => $news_ip,
				"news_port" => $news_port,
				"file_ip" => $file_ip,
				"file_port" => $file_port);
		return $serverArr;
	}
	
	/**
	 * 获取信息版本
	 */
	public function getInfoVersion($user_id){
		$querySql = "select * from mall_user_version_info where user_id = '$user_id'";
		return $this->conn->getRow($querySql);
	}
}