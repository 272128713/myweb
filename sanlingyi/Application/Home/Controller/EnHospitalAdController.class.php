<?php
/**
 * 空中诊所广告管理
 * 
 */

namespace Home\Controller;
use Think\Controller;
class EnHospitalAdController extends TemplateController{
	public function index(){
		
	}	
	
	public function EnHospitalAdList($id=0){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];

		$dbc=M('sys_ad_info','',$this->getDbLink(4));

		$result=$dbc->field('name,createDate,id')			
					->select();			
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}	
	public function EnHospitalAdEditItemOpt(){
		$result='EnHospitalAdEdit';
		return $result;
	}	
	public function EnHospitalAdAddItemOpt(){
		$result='EnHospitalAdAdd';
		return $result;
	}	
	public function EnHospitalAdDelItemOpt(){
		$result='EnHospitalAdDel';
		return $result;
	}	
	//空中诊所编辑
	public function EnHospitalAdEdit($id,$data){
		
		$model=M('sys_ad_info','',$this->getDbLink(4));
		$data=$model->where('id='.$data)->find();
		$this->assign('data',$data);
		
		$this->getPlaceModule($id,0);
				
		$this->display('edit');
	}
	public function EnHospitalAdModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('sys_ad_info','',$this->getDbLink(4));
			$data=array(
					'name'=>$pi['name'],					
					'content'=>$_POST['content'],
					'logo_url'=>$pi['logo_url'],
					'real_url'=>$pi['real_url'],					
			);			
			$dbc->startTrans();			
			$result=$dbc->where('id='.$pi['id'])->save($data);
			
			
			if($data['real_url']==''){
				$url='123';
				$result=$dbc->where(array('id'=>$pi['id']))->save(array('real_url'=>$url));
			}
			
			if($result!==false){
				$dbc->commit();
				$this->ajaxReturn('0');
			}else{
				$dbc->rollback();
				$this->ajaxReturn('1');
			}
		}else{
			$this->loginError('3');
		}
	}	
	//添加
	public function EnHospitalAdAdd($id,$data){
	
		$dbc=M('sys_ad_info','',$this->getDbLink(4));
	
	
		$this->getPlaceModule($id,0);
	
	
		$this->display('edit');
	}	
	public function EnHospitalAdCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('sys_ad_info','',$this->getDbLink(4));
			$data=array(
					'name'=>$pi['name'],					
					'content'=>$_POST['content'],
					'logo_url'=>$pi['logo_url'],
					'real_url'=>$pi['real_url'],
					'createDate'=>date('Y-m-d H:i:m',time()),
					'type'=>0
			);	

			$dbc->startTrans();			
			$result=$dbc->add($data);
			if($data['real_url']==''){
				$url='123';
				$result=$dbc->where(array('id'=>$result))->save(array('real_url'=>$url));
			}
			if($result!==false){

				$dbc->commit();
				$this->ajaxReturn('0');
			}else{
				$dbc->rollback();
				$this->ajaxReturn('1');
			}
		}else{
			$this->loginError('3');
		}
	}
	public function EnHospitalAdDel($data){
		$dbc=M('sys_ad_info','',$this->getDbLink(4));
	
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
	
		if($result!==false){
			$dbc->commit();
			$success=true;
		}
		else{
			$dbc->rollback();
		}

		return $success;
	}		
	
	
}