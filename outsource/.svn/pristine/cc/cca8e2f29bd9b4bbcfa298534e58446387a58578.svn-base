<?php
/**
 * 退货地址
 * @author Administrator
 *
 */
class GoodsController extends CommonController{
	public  function __construct()
	{
		parent::__construct();
		$this->conn=M()->conn;
		$this->Logger=Logger::getLogger(basename(__FILE__));
	
	}
	
	/**
	 * 退货
	 */
	public  function returnGoods(){
		$params = array(array("oid",true),array("ss",true));
		$params = Filter::paramCheckAndRetRes($_POST, $params);
		if(!$params){
			$this->Logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		$oid=$params['oid'];
		$uid=$user['member_id']; //用户id
		//查看是否未取消订单
		$order_id="select order_id from mall_order  where buyer_id=$uid and order_id=$oid
		          and (order_state =60 or order_state=0 or order_state=70)";
		
		if($this->conn->getOne($order_id)){
			//退货正在进行中
			ErrCode::echoErr(ErrCode::API_ERR_RETURNISDOING,1);
		}else{
			$sql="update  mall_order 
			      set order_state= 60,mreturn_time=now() where order_id=$oid and buyer_id=$uid";
			
			if($this->conn->execute($sql)){
				//给对应员工发信息
				$mec_str='用户'.$user['member_truename'].'申请退货';
				$mec_arr=array(
						'type'=>'RUG',
						'user_id'=>$user['member_id'],
						'mobile'=>$user['member_name'],
						'content'=>$mec_str,
						'accepters'=>M('User')->getUserInfoByUid($user['parent_id']),
						'msg_id'=>$oid
				);
				
				send_msg($mec_arr);
				ErrCode::echoJson(1,'成功');
			}else{
				ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
			}
		}
	}
	
	
}