<?php
//接口设置控制器
namespace Home\Controller;
use Think\Controller;
class InterfaceManageController extends TemplateController{
	public function index(){
		
	}	
	public function interfaceManageList(){
		$dbc=M('com_conf_interface','',$this->getDbLink(1));
		$result=$dbc->field('resume,domain_name,ip,port,lan_ip,lan_port,position,status,id')
					->order('position asc')->select();
		$this->tplListItem(session('SESS_optModuleID'),$result);
	}
	public function interfaceManageAdd($id){
		$dbc=M('com_conf_interface','',$this->getDbLink(1));
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display('add');
	}
	public function interfaceManageCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('com_conf_interface','',$this->getDbLink(1));				
			$dbc->where('position>'.$pi['position'])->setInc('position');			
				$maxID = $dbc->max('id');				
				$pi['id']=$maxID+1;
				$pi['position']=$pi['position']+1;
				if($dbc->add($pi)>0){
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
	public function interfaceManageEditItemOpt(){
		return 'edit';
	}
	public function interfaceManageStatusItemOpt($optData){
		$result=$optData['status']>0?'true':'false';
		return $result;
	}
	public function interfaceManageDelItemOpt($optData){
		return 'del';
	}
	public function interfaceManageEdit($id,$data){
		$dbc=M('com_conf_interface','',$this->getDbLink(1));
		$d=$dbc->where('id='.$data)->find();
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->assign('data',$d);
		$this->display('edit');
	}
	public function interfaceManageModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('com_conf_interface','',$this->getDbLink(1));
			$dbc->startTrans();
				$t=$dbc->where('id='.$pi['id'])->find();
				$opt=D('radioedit');
				$pi['position']=$opt->singlePosition('com_conf_interface',$t['position'],$pi['position'],1);
				$result=$dbc->where('id='.$pi['id'])->save($pi);
				if($result>0){
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
	public function interfaceManageStatus($data){		
		$dbc=M('com_conf_interface','',$this->getDbLink(1));
		$t=$dbc->where('id='.$data)->find();
		$status=$t['status']>0?0:1;
		$dbc->where('id='.$data)->setField('status',$status);
		//echo $dbc->getLastSql();exit;
		return true;
	}	
	public function interfaceManageDel($data){
		$dbc=M('com_conf_interface','',$this->getDbLink(1));
		$t=$dbc->where('id='.$data)->find();
		$dbc->startTrans();
		$success=false;
		$result=$dbc->where('id='.$data)->delete();
		if($result>0){
			$result=$dbc->where('position>'.$t['position'])->setDec('position');
			if($result!==false){
				$dbc->commit();
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
}