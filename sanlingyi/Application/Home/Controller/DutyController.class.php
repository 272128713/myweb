<?php
namespace Home\Controller;
use Think\Controller;
class DutyController extends TemplateController{
	public function index(){
		
	}
	
	public function dutyList(){
		$dbc=M('duty_info');
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];		
		$s['name']=array('like','%'.$pageList['parameter']['condition'].'%');
		$count=$dbc->where($s)->count();
		$result=$dbc->field('position,name,resume,status,id')->where($s)
				->limit($pageList['parameter']['start'],$pageList['parameter']['limit'])
				->order('position')->select();
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);
	}
	
	public function dutyEditItemOpt(){
		$result='edit';
		return $result;
	}
	
	public function dutyStatusItemOpt($optData){
		$result=$optData['status']>0?'true':'false';
		return $result;
	}
	
	public function dutyDelItemOpt(){
		$result='del';
		return $result;
	}
	
	public function dutyDepartmentItemOpt(){
		$result='power';
		return $result;
	}
	
	public function dutyAdd(){
		$dbc=M('duty_info');
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule('dutyAdd', 0);
		$this->assign('poList',$poList);
		$this->display('add');
	}
	
	public function dutyCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('duty_info');
			$dbc->startTrans();
			if($dbc->where('name="'.$pi['name'].'"')->count()<1){
				$result=$dbc->where('position>'.$pi['position'])->setInc('position');
				$pi['id']=$this->createNewID();
				$pi['position']=$pi['position']+1;
				if($result!==false){
					$result=$dbc->add($pi);
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
	
	public function dutyEdit($id,$data){
		$dbc=M('duty_info');
		$t=$dbc->find($data);
		$poList=$dbc->where('id<>'.$data)->order('position')->select();
		$this->getPlaceModule($id, 0);
		$this->assign('poList',$poList);
		$this->assign('data',$t);
		$this->display('edit');
	}
	
	public function dutyModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('duty_info');
			$dbc->startTrans();
			if($dbc->where('name="'.$pi['name'].'" and id<>'.$pi['id'])->count()<1){
				$t=$dbc->find($pi['id']);
				$opt=D('radioedit');
				$pi['position']=$opt->singlePosition('duty_info',$t['position'],$pi['position']);
				$result=$dbc->save($pi);
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
				$this->ajaxReturn('1');
			}
		}
		else{
			$this->loginError('3');
		}
	}
	
	public function dutyStatus($data){
		$dbc=M('duty_info');
		$t=$dbc->find($data);
		$status=$t['status']>0?0:1;
		$dbc->where('id='.$data)->setField('status',$status);
		return true;
	}
	
	public function dutyDel($data){
		$dbc=M('duty_info');
		$t=$dbc->find($data);
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
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
	
	public function dutyDepartment($id,$data){
		$dbc=M('duty_info');
		$t=$dbc->find($data);
		$d['objectID']=$data;
		$d['module']=$id;
		$page='Template/singleTree';
		$this->getPlaceModule($id, 0, $data, 1);
		$this->assign('data',$d);
		$this->display($page);
	}
}
?>