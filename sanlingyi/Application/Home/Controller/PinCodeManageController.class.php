<?php
/**
 * 验证码管理控制器
 * 
 */

namespace Home\Controller;
use Think\Controller;
class PinCodeManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function PinCodeManage($id){
		$this->tplList($id);
		$this->display('Template/list');
	}
	
	public function PinCodeManageList($moduleID){
		$dbc=M('com_app_user_pincode_history','',$this->getDbLink(1));
		$pageList=session('SESS_ListItemData');		
		$pageList=$pageList[$moduleID];	
		if($pageList['condition'] != ''){
			$s['mobile']=$pageList['condition'];			
		}				
		$nowtime=date('y-m-d',strtotime("-1 days"));
		$s['pin_made_time']=array('gt',$nowtime);
		
		$count=$dbc->where($s)->count();
		$result=$dbc->field('mobile,pin_code,pin_made_time,pin_try_accmulation,is_succ,pin_accmulation_time,service_type')->where($s)->limit($pageList['start'],$pageList['limit'])->order('pin_made_time desc')->select();		
		foreach($result as &$v){
			$arr=array(
					1=>'注册服务',
					2=>'邮箱找回密码',
					3=>'手机找回',
			);
			$v['is_succ']=$v['is_succ']?'成功':'未成功';
			$v['service_type']=$arr[$v['service_type']];
			
		}
		$this->tplListItem($moduleID,$result,$count);		
	}
				
}