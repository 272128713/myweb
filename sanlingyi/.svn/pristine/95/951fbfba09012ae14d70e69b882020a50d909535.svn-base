<?php
//保险产品分类管理控制器
namespace Home\Controller;
use Think\Controller;
class InsuranceClassManageController extends TemplateController{
	public function index(){
		
	}	
	public function InsuranceClassManageList(){
		$dbc=M('entity_insurance_class','',$this->getDbLink(1));
		$result=$dbc->field('class_name,sort,state,id')
					->order('sort asc')->select();		
		$this->tplListItem(session('SESS_optModuleID'),$result);
	}
	public function InsuranceClassManageAdd($id){
		$dbc=M('entity_insurance_class','',$this->getDbLink(1));
		$poList=$dbc->order('sort')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display('edit');
	}
	public function InsuranceClassManageCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('entity_insurance_class','',$this->getDbLink(1));
				if($dbc->where('class_name="'.$pi['class_name'].'"')->find()){
					$this->ajaxReturn('1');
				}				
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
	public function InsuranceClassManageEditItemOpt(){
		return 'edit';
	}
	public function InsuranceClassManageStatusItemOpt($optData){
		$result=$optData['state']>0?'true':'false';
		return $result;
	}
	public function InsuranceClassManageDelItemOpt($optData){
		return 'del';
	}
	public function InsuranceClassManageEdit($id,$data){
		$dbc=M('entity_insurance_class','',$this->getDbLink(1));
		$d=$dbc->where('id='.$data)->find();
		$poList=$dbc->order('sort')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->assign('data',$d);
		$this->display('edit');
	}
	public function InsuranceClassManageModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('entity_insurance_class','',$this->getDbLink(1));	
// 				if($dbc->where('class_name="'.$pi['class_name'].'"')->find()){
// 					$this->ajaxReturn('1');
// 				}			
				$result=$dbc->where('id='.$pi['id'])->save($pi);
				if($result>0){
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
	public function InsuranceClassManageStatus($data){		
		$dbc=M('entity_insurance_class','',$this->getDbLink(1));
		$t=$dbc->where('id='.$data)->find();
		$status=$t['state']>0?0:1;
		$dbc->where('id='.$data)->setField('state',$status);
		return true;
	}	
	public function InsuranceClassManageDel($data){
		$dbc=M('entity_insurance_class','',$this->getDbLink(1));
		$t=$dbc->where('id='.$data)->find();

		$result=$dbc->where('id='.$data)->delete();
		return $result;
	}	
}