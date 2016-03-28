<?php
//K服务等级管理控制器
namespace Home\Controller;
use Think\Controller;
class ServiceInfoController extends TemplateController{
	public function index(){
		
	}	
	public function ServiceInfoList(){
		$dbc=M('com_sic_service_info','',$this->getDbLink(1));
		$result=$dbc->field('name,money,deposit,cycle,rate,sale_num,resume,position,status,id')
					->order('position asc')->select();
		$this->tplListItem(session('SESS_optModuleID'),$result);
	}
	public function ServiceInfoAdd($id){
		$dbc=M('com_sic_service_info','',$this->getDbLink(1));
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->assign('time',date('Y-m-d H:i:s',time()));
		$this->display('add');
	}
	public function ServiceInfoCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('com_sic_service_info','',$this->getDbLink(1));				
			$dbc->where('position>'.$pi['position'])->setInc('position');			
				$maxID = $dbc->max('id');				
				$pi['id']=$maxID+1;
				$pi['position']=$pi['position']+1;
				if($dbc->add($pi)>0){					
					$this->upVersion();					
					$this->ajaxReturn('0');
				}
				else{
					$this->ajaxReturn('2');
				}
		}
		else{
			$this->loginError('3');
		}
	}
	public function ServiceInfoEditItemOpt(){
		return 'edit';
	}
	public function ServiceInfoStatusItemOpt($optData){
		$result=$optData['status']>0?'true':'false';
		return $result;
	}
	public function ServiceInfoDelItemOpt($optData){
		return 'del';
	}
	public function ServiceInfoEdit($id,$data){
		$dbc=M('com_sic_service_info','',$this->getDbLink(1));
		$d=$dbc->where('id='.$data)->find();
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->assign('data',$d);
		$this->display('edit');
	}
	public function ServiceInfoModify(){
		if(IS_POST){
			$pi=I('post.');
			//dump($pi);exit;
			$dbc=M('com_sic_service_info','',$this->getDbLink(1));
			$dbc->startTrans();
				$t=$dbc->where('id='.$pi['id'])->find();
				$opt=D('radioedit');
				$pi['position']=$opt->singlePosition('com_sic_service_info',$t['position'],$pi['position'],1);
				$result=$dbc->where('id='.$pi['id'])->save($pi);
				if($result>0){
					$dbc->commit();
					$this->upVersion();
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
	public function ServiceInfoStatus($data){		
		$dbc=M('com_sic_service_info','',$this->getDbLink(1));
		$t=$dbc->where('id='.$data)->find();
		$status=$t['status']>0?0:1;
		$dbc->where('id='.$data)->setField('status',$status);
		$this->upVersion();
		//echo $dbc->getLastSql();exit;
		return true;
	}	
	public function ServiceInfoDel($data){
		$dbc=M('com_sic_service_info','',$this->getDbLink(1));
		$t=$dbc->where('id='.$data)->find();
		$dbc->startTrans();
		$success=false;
		$result=$dbc->where('id='.$data)->delete();
		if($result>0){
			$result=$dbc->where('position>'.$t['position'])->setDec('position');
			if($result!==false){
				$dbc->commit();
				$this->upVersion();
				$success=true;
			}
			else{
				$dbc->rollback();
			}
		}
		else{
			$dbc->rollback();
		}
		return $success;
	}
	//在做任何修改K服务信息时更新版本号
	public function upVersion(){
		M('com_dic_version_info','',$this->getDbLink(1))->where("tb_name='com_sic_service_info'")->setInc('last_version');
	}		
}