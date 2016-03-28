<?php
namespace Home\Controller;
use Think\Controller;
class SectionController extends TemplateController{
	public function index(){
		$this->display('Template/blank');
	}	
	
	public function sectionAdd($id){
		$dbc=M('com_sic_section_info','',$this->getDbLink(1));
		$listData=$dbc->where('level=1')->order('position')->select();
		$this->getPlaceModule($id, 0);
		$this->assign('paList',$listData);
		$this->assign('poList',$listData);
		$this->display('add');
	}
	
	public function sectionCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('com_sic_section_info','',$this->getDbLink(1));
			if($dbc->where('name="'.$pi['name'].'" and parentId='.$pi['parentId'])->count()<1){
				$opt=D('radioedit');
				$pi['position']=$opt->getPosition('com_sic_section_info', $pi['position'], $pi['parentId'],1);
				$dbc->startTrans();
				$dbc->where('position>='.$pi['position'])->setInc('position');
				$pi['id']=$this->createNewID();
				$pi['level']=$pi['parentId']==0?1:2;
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
				$this->ajaxReturn('1');
			}
		}
		else{
			$this->loginError('3');
		}
	}
	
	public function sectionEdit($id){
		$dbc=M('com_sic_section_info','',$this->getDbLink(1));
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
	
	public function sectionModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('com_sic_section_info','',$this->getDbLink(1));
			if($dbc->where('id<>'.$pi['id'].' and name="'.$pi['name'].'" and parentId='.$pi['parentId'])->count()<1){
				$dbc->startTrans();
				$opt=D('radioedit');
				if($opt->movePosition('com_sic_section_info',$pi['id'],$pi['parentId'],$pi['position'],1)){
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
	
	public function sectionStatus($data){
		$dbc=M('com_sic_section_info','',$this->getDbLink(1));
		$dbc->startTrans();
		$opt=D('radioedit');
		if($opt->changeStatus('com_sic_section_info',$data,1)){
			$dbc->commit();
			$result=true;
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	
	public function sectionDel($data){
		$dbc=M('com_sic_section_info','',$this->getDbLink(1));
		$dbc->startTrans();
		$opt=D('radioedit');
		$result=$opt->getDel('com_sic_section_info',$data,1);
		if($result['status']){
			$dbc->commit();
			$result=true;
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	
	public function sectionConfig(){
		$t=$this->Post(1,'configure/section');
		$result=$t['result']>0?true:false;
		return $result;
	}
}
?>