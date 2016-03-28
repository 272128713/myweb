<?php
class Model{
	/**
	 * 返回数据库连接
	 * @param string $name
	 */
	protected function db(){
		return  new DatabaseManager();
	}
	
	//数据库连接
	public  $conn;
	
	//日志
	public  $logger;
	/**
	 * 检查用户session
	 */
	public  function __construct(){
		if(!is_object($this->conn)){
			$this->conn=$this->db()->getConn();
		}
		if(!is_object($this->logger)){
			$this->logger=Logger::getLogger(basename(__FILE__));;
		}
		
	}

    /**
     * 获取群聊数据库链接
     */
    public  function  getGroupConn(){
        return $this->db()->getGroupConn();
    }
    
    /**
     * 获取医信据库链接
     */
    public  function  getYixinConn(){
    	return $this->db()->getYinxinConn();
    }



	
}