<?php
namespace Home\Controller;
use Think\Controller;
class BodyController extends TemplateController{
	public function index(){
		$this->display('Template/blank');
	}
	
	public function getFrameTree(){
		$dbc=M('base_body','',$this->getDbLink(1));
		$data=$dbc->field('id,name,parentId')->order('position')->select();
		$title='请选择需要管理的身体部位信息';
		$direct='bodyEdit';
		$this->tplTree($title, $data, 1, 1, false, true, $direct);
	}	
	
	public function bodyAdd($id){
		$dbc=M('base_body','',$this->getDbLink(1));
		$listData=$dbc->where('level=1')->order('position')->select();
		$this->getPlaceModule($id, 0);
		$this->assign('paList',$listData);
		$this->assign('poList',$listData);
		$this->display('add');
	}
	
	public function bodyCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('base_body','',$this->getDbLink(1));
			if($dbc->where('name="'.$pi['name'].'" and parentId='.$pi['parentId'])->count()<1){
				$opt=D('radioedit');
				$pi['position']=$opt->getPosition('base_body', $pi['position'], $pi['parentId'],1);
				$dbc->startTrans();
				$dbc->where('position>='.$pi['position'])->setInc('position');
				$pi['id']=$this->createNewID();
				$pi['level']=$pi['parentId']==0?1:2;
				$result=$dbc->add($pi);
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
	
	public function bodyEdit($id){
		$dbc=M('base_body','',$this->getDbLink(1));
		$data=$dbc->find($id);
		if($data){
			$parentList=$dbc->where('level=1 and id<>'.$data['id'])->order('position')->select();
			//取出可选的位置信息
			$positionList=$dbc->where('parentId='.$data['parentId'].' and id <> '.$id)->order('position')->select();
			$this->getPlaceModule(session('SESS_optModuleEN'), 0, $id);
			$this->assign('data',$data);
			$this->assign('paList',$parentList);
			$this->assign('poList',$positionList);
			$this->display('edit');
		}
		else{
			$this->display('Index/blank');
		}
	}
	
	public function bodyModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('base_body','',$this->getDbLink(1));
			if($dbc->where('id<>'.$pi['id'].' and name="'.$pi['name'].'" and parentId='.$pi['parentId'])->count()<1){
				$dbc->startTrans();
				$opt=D('radioedit');
				if($opt->movePosition('base_body',$pi['id'],$pi['parentId'],$pi['position'],1)){
					unset($pi['position']);
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
					$dbc->rollback();
					$this->ajaxReturn('2');
				}
			}
			else{
				$this->ajaxReturn('1');
			}
		}
		else{
			$this->loginError('4');
		}
	}
	
	public function bodyStatus($data){
		$dbc=M('base_body','',$this->getDbLink(1));
		$dbc->startTrans();
		$opt=D('radioedit');
		if($opt->changeStatus('base_body',$data,1)){
			$dbc->commit();
			$result=true;
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	
	public function bodyDel($data){
		$dbc=M('base_body','',$this->getDbLink(1));
		$dbc->startTrans();
		$opt=D('radioedit');
		$result=$opt->getDel('base_body',$data,1);
		if($result['status']){
			$res=$dbc->where('id in ('.implode(',', $result['optData']).')')->delete();
			if($res!==false){
				$dbc->commit();
				$result=true;
			}
			else{
				$dbc->rollback();
				$result=false;
			}
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	
	public function bodyConfig(){
		$t=$this->Post(1,'configure/body');
		$result=$t['result']>0?true:false;
		return $result;
	}
}
?>