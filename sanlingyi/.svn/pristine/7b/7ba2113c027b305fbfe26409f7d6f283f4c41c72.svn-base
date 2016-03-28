<?php
namespace Home\Controller;
use Think\Controller;
class RemindController extends TemplateController{
	public function index(){
		
	}	
	public function remindList(){
		$dbc=M('remind_info');
		$result=$dbc->field('position,name,resume,controller,id,status')
					->order('position')->select();
		$this->tplListItem(session('SESS_optModuleID'),$result);
	}
	public function remindAdd($id){
		$dbc=M('remind_info');
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display('add');
	}
	public function remindCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('remind_info');
			if($dbc->where("name='".$pi['name']."' or controller='".$pi['controller']."'")->count()<1){
				$dbc->where('position>'.$pi['position'])->setInc('position');
				$pi['id']=$this->createNewID();
				$pi['position']=$pi['position']+1;
				if($dbc->add($pi)>0){
					$this->ajaxReturn('0');
				}
				else{
					$this->ajaxReturn('2');
				}
			}
			else{
				$this->ajaxReturn('1');
			}
		}
		else{
			$this->loginError('3');
		}
	}
	public function remindEditItemOpt(){
		return 'edit';
	}
	public function remindStatusItemOpt($optData){
		$result=$optData['status']>0?'true':'false';
		return $result;
	}
	public function remindDelItemOpt($optData){
		return 'del';
	}
	public function remindEdit($id,$data){
		$dbc=M('remind_info');
		$d=$dbc->find($data);
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->assign('data',$d);
		$this->display('edit');
	}
	public function remindModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('remind_info');
			$dbc->startTrans();
			if($dbc->where('name="'.$pi['name'].'" and id<>'.$pi['id'])->count()<1){
				$t=$dbc->find($pi['id']);
				$opt=D('radioedit');
				$pi['position']=$opt->singlePosition('remind_info',$t['position'],$pi['position']);
				$result=$dbc->save($pi);
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
				$this->ajaxReturn('1');
			}
		}
		else{
			$this->loginError('3');
		}
	}
	public function remindStatus($data){
		$dbc=M('remind_info');
		$t=$dbc->find($data);
		$status=$t['status']>0?0:1;
		$dbc->where('id='.$data)->setField('status',$status);
		return true;
	}	
	public function remindDel($data){
		$dbc=M('remind_info');
		$t=$dbc->find($data);
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
		if($result!==false){
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