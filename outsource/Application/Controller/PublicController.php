<?php
class  PublicController extends CommonController
{   
	/**
	 * 会员详情
	 */
	public  function  userDetail(){
		$logger = Logger::getLogger(basename(__FILE__));
		$params = array(array("ss",true),array('uid',true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		check_role($this->user,2);
		$id=$params['uid'];
		$data=M('Member')->one($id);
		ErrCode::echoJson(1,'获取成功',$data);
	}
	
	/**
	 * 获取会员列表如果有value值则为搜索
	 */
	public function getMemberList(){
	    $logger = Logger::getLogger(basename(__FILE__));
		$params = array(array("ss",true),array('value',false),array('page',false),array('sid',false));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		//check_role($this->user,2);
		$user=$this->user;
		$data=M('Member')->getList($params['page'],$params['value'],$params['sid'],$user['member_id']);
		ErrCode::echoJson(1,'获取成功',$data);
	}
	
	/**
	 * 获取员工的详情
	 */
	public  function  getWorkerDetail(){
		$logger = Logger::getLogger(basename(__FILE__));
		$params = array(array("ss",true),array('uid',true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		check_role($this->user,4);
		$data=M('Worker')->getWokerDetail($params['uid']);
		if(!$data){
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		ErrCode::echoJson(1,'获取成功',$data);
	}
	
	/**
	 * 获取店铺的详情
	 */
	public  function  getShopDetail(){
		$logger = Logger::getLogger(basename(__FILE__));
		$params = array(array("ss",true),array('sid',true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		check_role($this->user,4);
		$data=M('Shop')->shopDetail($params['sid']);
		if(!$data){
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		ErrCode::echoJson(1,'获取成功',$data);
	}
	
	/**
	 * 根据id返回对应用父亲的头像和名字
	 */
	public  function  getPrent(){
		$logger = Logger::getLogger(basename(__FILE__));
		$params = array(array("ss",true),array('member_id',true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		$sql="select 
		a.member_truename,
		a.member_name as mobile,
	    a.member_id,					
		b.thumbnail_image_url
		from mall_member as a,
		mall_user_version_info as b
		where
		b.user_id=a.member_id
		and a.member_id=".$user['parent_id']	
		;
		$conn=M()->conn;
		
		ErrCode::echoJson(1,'获取成功',$conn->getRow($sql));
	}
}