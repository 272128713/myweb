<?php
namespace Home\Controller;
use Think\Controller;
class RegionController extends TemplateController{
	public function index(){
		$this->display('Template/blank');
	}
			
	public function regionAdd($id){
		$dbc=M('base_region','',$this->getDbLink(1));
		$parentID=I('get.parentId');
		if($parentID>0){
			$row=$dbc->find($parentID);
			$parentName=$row['name'];
			$level=$row['level']+1;
		}
		else{
			$parentID=0;
			$parentName='本身就是省级/直辖市地区';
			$level=1;
		}
		$listData=$dbc->where('parentId='.$parentID)->order('position')->select();
		$data['parentName']=$parentName;
		$data['parentID']=$parentID;
		$data['level']=$level;
		$this->getPlaceModule($id, 0);
		$this->assign('poList',$listData);
		$this->assign('data',$data);
		$this->display('add');
	}
	
	public function regionCreate(){
		if(IS_POST){
			$dbc=M('base_region','',$this->getDbLink(1));
			$pi=I('post.');
			$sql="name='".$pi['name']."' and parentId=".$pi['parentId'];
			if($dbc->where($sql)->count()<1){
				$dbc->startTrans();
				$opt=D('radioedit');
				$d['position']=$opt->getPosition('base_region',$pi['position'],$pi['parentId'],1);
				$dbc->where('position>='.$pi['position'])->setInc('position');
				$d['name']=$pi['name'];
				$d['id']=$pi['code'];
				$d['parentId']=$pi['parentId'];
				$d['level']=$pi['level'];
				$row=$dbc->add($d);
				if($row !== false){
					$dbc->commit();
					$this->ajaxReturn('0');
				}
				else{
					$dbc->rollback();
					$this->ajaxReturn('2');
				}
			}
			else{
				$this->ajaxReturn('1');  //重名
			}				
		}
		else{
			$this->loginError('3');
		}
	}
	
	public function regionEdit($id){
		$dbc=M('base_region','',$this->getDbLink(1));
		$data=$dbc->find($id);
		if(!empty($data['name'])){
			if($data['parentId']>0){
				$parentName=$dbc->where('id='.$data['parentId'])->getField('name');
			}
			else{
				$parentName='本身就是省级/直辖市地区';
			}
			$positionList=$dbc->where('parentId='.$data['parentId'].' and id<>'.$id)->order('position')->select();
			$data['parentName']=$parentName;
			$this->getPlaceModule(session('SESS_optModuleID'),0,$id);
			$this->assign('data',$data);
			$this->assign('poList',$positionList);
			$this->display('edit');
		}
		else{
			$this->display('Template/blank');
		}
	}
	
	public function regionEditDo(){
		if(IS_POST){
			$dbc=M('base_region');
			$pi=I('post.');
			if($dbc->where('id<>'.$pi['code'].' and name="'.$pi['name'].'"')->count()<1){
				$dbc->startTrans();
				$opt=D('radioedit');
				$data['name']=$pi['name'];
				$data['id']=	$pi['code'];
				if($opt->movePosition('base_region',$pi['mid'],$pi['parentId'],$pi['position'],1)){
					$result=$dbc->where('id='.$pi['mid'])->save($data);
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
				$this->ajaxReturn('1');//重名
			}				
		}
		else{
			$this->loginError('3');
		}			
	}
	
	public function regionStatus($data){
		$dbc=M('base_region','',$this->getDbLink(1));
		$dbc->startTrans();
		$opt=D('radioedit');
		if($opt->changeStatus('base_region',$data,1)){
			$dbc->commit();
			$result=true;
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	
	public function regionDel($data){
		$dbc=M('base_region','',$this->getDbLink(1));
		$dbc->startTrans();
		$opt=D('radioedit');
		$result=$opt->getDel('base_region',$data,1);
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
	
	public function regionConfig(){
		$t=$this->Post(1,'configure/region');
		$result=$t['result']>0?true:false;
		return $result;
	}
}
?>