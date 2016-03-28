<?php
//广告审批控制器
namespace Home\Controller;
use Think\Controller;
class AdvertController extends TemplateController{
	public function index(){
		
	}	
	public function AdvertList(){		
		//取得分页设置
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		$dbc=M('agent_doctor_advert','',$this->getDbLink(1));		
		$count=$dbc->count();		
		$result=$dbc->field('agent_doctor_advert.agent_id,com_dic_user_info.name,advert_money,agent_doctor_advert.create_time,agent_doctor_advert.status,agent_doctor_advert.id')					
        			->join('LEFT JOIN com_dic_user_info ON agent_doctor_advert.doctor_id = com_dic_user_info.id')					
					->limit($pageList['start'],$pageList['limit'])
					->order('agent_doctor_advert.id desc')
					->select();
		foreach ($result as &$val){
			$val['agent_id']=M('com_dic_authentication_info','',$this->getDbLink(1))->where('id='.$val['agent_id'])->getField('name');
			$statusArr=array(
				0=>'未审批',
				1=>'已通过',
				2=>'禁止',
			);
			$val['status']=$statusArr[$val['status']];
		}		
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);
	}
	//设置列表上的操作
	public function viewPicItemOpt($optData){
		return 'viewPic';
	}
	public function adAuthItemOpt($optData){
		return 'adAuth';
	}
	public function viewPic($id,$data){		
		$dbc=M('agent_doctor_advert','',$this->getDbLink(1));
		$img=$dbc->where('id='.$data)->getField('img');
		$this->assign('img',$img);
		$this->display();		
	}
	public function adAuth($id,$data){
		
		
		$dbc=M('agent_doctor_advert','',$this->getDbLink(1));
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		
		
		//$dbc=M('agent_doctor_advert','',$this->getDbLink(1));
		$data=$dbc->where('id='.$data)->find();
		
		$data['agent_name']=M('com_dic_authentication_info','',$this->getDbLink(1))->where('id='.$data['agent_id'])->getField('name');
		$data['doctor_name']=M('com_dic_user_info','',$this->getDbLink(1))->where('id='.$data['doctor_id'])->getField('name');

		$this->assign('data',$data);
		$this->display();
	
	}	
	//提交审批
	public function doAdAuth(){	
		if(IS_POST){
			actionLog(1,'广告审批');//操作日志记录
			$pi=I('post.');
			$dbc=M('agent_doctor_advert','',$this->getDbLink(1));
			$dbc->startTrans();
			$result=$dbc->where('id='.$pi['id'])->save(array('status'=>$pi['status']));
			if($result!==false){
				$dbc->commit();
				$this->ajaxReturn('0');
			}
			else{
				$dbc->rollback();
				$this->ajaxReturn('2');
			}
		}
		else{
			$this->loginError('3');
		}	
	}	

}