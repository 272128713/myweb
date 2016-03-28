<?php
namespace Home\Controller;
use Think\Controller;
class LoopController extends CommonController{
	public function remind($data){
		$d=unserialize($data);
		$msg='';
		foreach ($d as $k=>$v){
			$controller=$v['controller'];
			$result=$this->$controller();
			if($result['remind']){
				$msg.=$result['msg'].'/';
			}
		}
		if(strlen($msg)>0){
			$result=array(
				'remind'=>'1',
				'msg'=>$msg,
			);
		}
		$this->ajaxReturn($result);
	}
	//新医生申请K服务提醒
	public function newDoctor(){
		$dbc=M('k_requisition','',$this->getDbLink(1));
		$num=$dbc->where('state = 0')->count();
		if($num > 0){
			$result=array(
					'remind'=>true,
					'msg'	=>'有'.$num.'位医生申请K服务',
			);			
		}else{
			$result=array(
					'remind'=>false,
					'msg'	=>'无',
			);			
		}
		return $result;
	}
	//新提现提醒
	public function newRefund(){
		$dbc=M('fit_refund_apply','',$this->getDbLink(1));
		$num=$dbc->where('status = 0')->count();
		if($num > 0){
			$result=array(
					'remind'=>true,
					'msg'	=>'有'.$num.'个新提现申请',
			);
		}else{
			$result=array(
					'remind'=>false,
					'msg'	=>'无',
			);
		}
		return $result;
	}
	//新医生申请认证提醒
	public function newDoctorAuth(){
		$dbc=M('user_base_info','',$this->getDbLink(1));
		$num=$dbc->where('authentication = 0')->count();
		if($num > 0){
			$result=array(
					'remind'=>true,
					'msg'	=>'有'.$num.'位医生申请认证',
			);
		}else{
			$result=array(
					'remind'=>false,
					'msg'	=>'无',
			);
		}
		return $result;
	}		
}
?>