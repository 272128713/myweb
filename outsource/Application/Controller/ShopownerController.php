<?php
/**
 * 店长控制器
 */
class ShopownerController extends  CommonController{
	
	/**
	 * 取得店铺基本信息
	 */
	public function getShopBaseInfo(){
		$logger = Logger::getLogger(basename(__FILE__));
		$model=M('Shopowner');
		$params = array(array("ss",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		//check_role($user,3);
		$res=$model->getShopBaseInfo($user['store_id']);
		//print_r($res);exit;
		if($res){
			ErrCode::echoJson(1,'成功',$res);
		}else{
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
	}
	
	/**
	 * 增加员工
	 */
	public  function  addWorker(){
		$logger = Logger::getLogger(basename(__FILE__));
		$model=M('Shopowner');
		$params = array(array("ss",true),array("mobile",true),array("user_name",true),array("sex",true),array("card_num",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$username = $params["mobile"];
		$num_exp = "/^1[034578][0-9]{9}$/";
	
		if(!preg_match($num_exp, $username)){   //判断用户名是否为手机号码
			$logger->error(sprintf("the username format is error.username is %s",$username));
			ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
		}
		if(!M('User')->userRegisterCheck($username)){
			$logger->error(sprintf("username is exist. username is %s",$username));
			ErrCode::echoErr(ErrCode::API_ERR_USERNAME_EXIST,1);
		}		
		$user=$this->user;
		$model->addWorker($user,$params);
		ErrCode::echoJson(1,'添加成功');
	
	
	}	
	/**
	 * 充值确认
	 */
	public  function  rechargeConfirm(){
		$logger = Logger::getLogger(basename(__FILE__));
		$model=M('Shopowner');
		$params = array(array("ss",true),array("oid",true),array("type",false));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		$res=$model->rechargeConfirm($user,$params);
		if($params['type']==1){
			ErrCode::echoJson(1,'确认成功');
		}else{
			ErrCode::echoJson(1,'请求成功',$res);
		}
	}
	/**
	 * 添加会员确认
	 */
	public  function  addMemberConfirm(){
		$logger = Logger::getLogger(basename(__FILE__));
		$model=M('Shopowner');
		$params = array(array("ss",true),array("mid",true),array("type",false));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		$res=$model->addMemberConfirm($user,$params);
		if($params['type']==1){
			ErrCode::echoJson(1,'确认成功');
		}else{
			ErrCode::echoJson(1,'请求成功',$res);
		}
	}
	/**
	 * 待处理业务列表
	 */	
	public function taskList(){
		$logger = Logger::getLogger(basename(__FILE__));
		$model=M('Shopowner');
		$params = array(array("ss",true),array("type",true),array("page",false),);
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;	
		$res=$model->getTaskList($user,$params);	
		
		ErrCode::echoJson(1,'请求成功',$res);
	}
	/**
	 * 提货确认
	 */	
	public function pickGoodsConfirm(){
		$logger = Logger::getLogger(basename(__FILE__));
		$model=M('Shopowner');
		$params = array(array("ss",true),array("oid",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		$res=$model->pickGoodsConfirm($user,$params);

		if($res){
			ErrCode::echoJson(1,'确认成功');
		}else{
			ErrCode::echoJson(0,'确认失败');
		}	
	}
	/**
	 * 退货确认
	 */
	public function returnGoodsConfirm(){
		$logger = Logger::getLogger(basename(__FILE__));
		$model=M('Shopowner');
		$params = array(array("ss",true),array("oid",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		$res=$model->returnGoodsConfirm($user,$params);
		if($res){
			ErrCode::echoJson(1,'确认成功');
		}else{
			ErrCode::echoJson(0,'确认失败');
		}
	}
/**
 * 订单详情接口
 */		
	public function orderDetial(){
		$logger = Logger::getLogger(basename(__FILE__));
		$model=M('Shopowner');
		$params = array(array("ss",true),array("oid",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;	
		$res=$model->getOrderDetial($params['oid'],$user);
		if($res){
			ErrCode::echoJson(1,'请求成功',$res);
		}else{
			ErrCode::echoJson(0,'请求失败');
		}		
	} 
	/**
	 * 退款确认
	 */
	public function refundConfirm(){
		$logger = Logger::getLogger(basename(__FILE__));
		$model=M('Shopowner');
		$params = array(array("ss",true),array("type",true),array("id",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		if($params['type']==1){
			$params_ext = array(array("money",true));
			$params_ext = Filter::paramCheckAndRetRes($_POST, $params_ext);
			if(!$params_ext){
				$logger->error(sprintf("params error. params is %s",v($_POST)));
				ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER);
			}
			$params=array_merge($params,$params_ext);
		}
		$user=$this->user;
		$res=$model->refundConfirm($user,$params);
		if($params['type']==1){
			if($res){
				ErrCode::echoJson(1,'确认成功');
			}else{
				ErrCode::echoJson(0,'确认失败');
			}
		}else{
			ErrCode::echoJson(1,'请求成功',$res);
		}

	}	
	
	
}