<?php
namespace Home\Controller;
use Think\Controller;
class DegreeController extends TemplateController{
	public function index(){
		$this->display('Template/blank');
	}
	
	public function getFrameTree(){
		$dbc=M('base_degree','',$this->getDbLink(1));
		$data=$dbc->order('position')->select();
		$title='请选择需要管理的学历信息';
		$direct='degreeEdit';
		$this->tplTree($title, $data, 1, 1, false, true,$direct);
	}
	
	public function degreeAdd($id){
		$dbc=M('base_degree','',$this->getDbLink(1));
		$this->getPlaceModule($id, 0);
		$listData=$dbc->order('position')->select();
		$this->assign('poList',$listData);
		$this->display('add');
	}
	
	public function degreeCreate(){
		if(IS_POST){
			$dbc=M('base_degree','',$this->getDbLink(1));
			$pi=I('post.');
			$result=$dbc->where('name="'.$pi['name'].'"')->count();
			if($result<1){
				$success=false;
				$dbc->startTrans();
				$result=$dbc->where('position>'.$pi['position'])->setInc('position');
				if($result!==false){
					$pi['position']++;
					$pi['id']=$this->createNewID();
					$result=$dbc->add($pi);
					if($result>0){
						$success=true;
					}	
				}
				if($success){
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
	
	public function degreeEdit($id){
		$dbc=M('base_degree','',$this->getDbLink(1));
		$t=$dbc->find($id);
		if($t['id']>0){
			$this->getPlaceModule(session('SESS_optModuleID'), 0, $id);
			$listData=$dbc->where('id<>'.$id)->order('position')->select();
			$this->assign('poList',$listData);
			$this->assign('data',$t);
			$this->display('edit');
		}
		else{
			$this->index();
		}
	}
	
	public function degreeModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('base_degree','',$this->getDbLink(1));
			if($dbc->where('name="'.$pi['name'].'" and id<>'.$pi['id'])->count()<1){
				$dbc->startTrans();
				$t=$dbc->find($pi['id']);
				$opt=D('radioedit');
				$pi['position']=$opt->singlePosition('base_degree',$t['position'],$pi['position'],1);
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
	
	public function degreeStatus($data){
		$dbc=M('base_degree','',$this->getDbLink(1));
		$t=$dbc->find($data);
		$s=$t['status']>0?0:1;
		$result=$dbc->where('id='.$data)->setField('status',$s);
		$result=$result>0?true:false;
		return $result;
	}
	
	public function degreeDel($data){
		$dbc=M('base_degree','',$this->getDbLink(1));
		$dbc->startTrans();
		$result=false;
		$t=$dbc->find($data);
		$res=$dbc->where('position>'.$t['position'])->setDec('position');
		if($res!==false){
			$res=$dbc->delete($data);
			if($res>0){
				$result=true;
			}
		}
		if($result){
			$dbc->commit();
		}
		else{
			$dbc->rollback();
		}
		return $result;
	}
	
	public function degreeConfig(){
		$t=$this->Post(1,'configure/degree');
		$result=$t['result']>0?true:false;
		return $result;
	}
}
?>