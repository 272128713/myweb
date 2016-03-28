<?php
/**
 * 员工业务处理
 */
class OprateController extends  CommonController
{   
	public  function __construct()
	{
		parent::__construct();
		$this->conn=M()->conn;
		$this->Logger=Logger::getLogger(basename(__FILE__));
		
	}
	/**
	 * 待处理业务
	 */
	public function  Pending()
	{
		$params = array(array("ss",true),array("type",true),array('page',false));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$this->Logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
	
		$page=page_oprate($params['page']);
		$limit=C('page_num');
		$user=$this->user;
		$uid=$user['member_id'];
		$shop_id=$user['store_id'];
		if($params['type']==2){
			$sql="select member_id from mall_member where parent_id=$uid and user_type_id=1";
			$sql2="select * from worker_oprate where member_id in( ".$sql." ) order by oprate_time desc limit $page,$limit";
			
			ErrCode::echoJson(1,'执行成功',$this->conn->getAll($sql2));
					
		}else{
			
			$sql="select member_id from mall_member where (store_id=$shop_id and user_type_id=2) or member_id=$uid";
			$sql2="select * from manage_oprate where member_id in( ".$sql." ) order by oprate_time desc limit $page,$limit";
			
			ErrCode::echoJson(1,'执行成功',$this->conn->getAll($sql2));
			
			
		}
		
		
		
	}
	
	/**
	 * 用户申请退款
	 *
	 */
	public function returnMoney(){
		$params = array(array("ss",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$this->Logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		$uid=$user['member_id'];
		//是否已经申请退款了
		$sql="insert into mall_refund_apply set member_id=$uid,mcreate_time=now()";
		
		if($this->conn->execute($sql)){
			$mec_str='用户'.$user['member_truename'].'申请退款';
			$mec_arr=array(
					'type'=>'WOS',
					'user_id'=>$user['member_id'],
					'mobile'=>$user['member_name'],
					'content'=>$mec_str,
					'accepters'=>M('User')->getUserInfoByUid($user['parent_id']),
					'msg_id'=>0
			);
			
			send_msg($mec_arr);
			ErrCode::echoJson(1,'执行成功');
		}else{
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		
	}
	
	/**
	 * 员工同意退款
	 */
	public  function  sureReturnMoney(){
		$params = array(array("ss",true),array("extend_id",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$this->Logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		$uid=$user['member_id'];
		$sql="update mall_refund_apply set worker_id=$uid,create_time=now(),manage_state=1 where id=".$params['extend_id'];
		if($this->conn->execute($sql)){
			ErrCode::echoJson(1,'执行成功');
		}else{
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
				
	}
	
   /**
    * 获取充值详情
    */
	public  function  getChargeDetail(){
		$params = array(array("ss",true),array("pdr_id",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$this->Logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$pid=$params['pdr_id'];
		$sql="select * from mall_pd_recharge where pdr_id=$pid";
		$row=$this->conn->getRow($sql);
		$return=array();
		$return['member_id']=$row['pdr_member_id'];
		$return['pdr_amount']=$row['pdr_amount'];
		$return['member_id']=$row['pdr_member_id'];
		$return['pdr_payment_name']=$row['pdr_payment_name'];
		$return['pdr_add_time']=date('Y-m-d H:i:s',$row['pdr_add_time']);
		$return['giver_points']=$row['giver_money'];
		$return['pdr_type']=$row['pdr_type'];
		$return['manage_time']=$row['manage_time'];
		$return['worker_name']=$this->conn->getOne("select member_truename from mall_member
				                where member_id=".$row['worker_id']);
		$return['manage_name']=$this->conn->getOne("select member_truename from mall_member
				                where member_id=".$row['manage_member']);
		ErrCode::echoJson(1,'执行成功',$return);
	}
	
	
}