<?php
namespace Home\Controller;
use Think\Controller;
class DepartmentController extends TemplateController{
	public function index(){
		$this->display('Template/blank');
	}
	
	public function departmentBatch($id){
		$this->getPlaceModule($id, 0, '', 1);
		$param=array(
			'moduleID'=>$id,
			'objectID'=>'',
		);
		$this->assign('param',$param);
		$this->display('Template/singleTree');
	}
	
	public function departmentAdd($id){
		$dbc=M('department_info');
		$this->getPlaceModule($id, 0);
		$listData=$dbc->where('parentId=0')->order('position')->select();
		$data['parentName']='本身就是公司/驻外机构';
		$data['parentID']=0;
		$data['level']=1;
		$dbr=M('base_region','',$this->getDbLink(1));
		$pList=$dbr->where('status>0 and level=1')->order('position')->select();
		$this->assign('pList',$pList);
		$this->assign('poList',$listData);
		$this->assign('data',$data);
		$this->display('add');
	}
	
	public function departmentChild($id,$data){
		$dbc=M('department_info');
		$t=$dbc->find($data);
		$listData=$dbc->where('parentId='.$data)->order('position')->select();
		$d['parentName']=$t['name'];
		$d['parentID']=$data;
		$d['level']=$t['level']+1;
		$this->getPlaceModule($id, 0);
		$this->assign('poList',$listData);
		$this->assign('data',$d);
		$this->display('add');
	}
	
	public function departmentAddDo(){
		if(IS_POST){
			$dbc=M('department_info');
			$pi=I('post.');
			$check=array(
				'name' => $pi['name'],
				'parentId'	=> $pi['parentId']
			);
			$result=$dbc->where($check)->count();
			if($result<1){
				$dbc->startTrans();
				$opt=D('radioedit');
				$pi['position']=$opt->getPosition('department_info',$pi['position'],$pi['parentId']);
				$dbc->where('position>='.$pi['position'])->setInc('position');
				$pi['id']=$this->createNewID();
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
	public function departmentEdit($id){
		$dbc=M('department_info');
		$opt=D('radioedit');
		$s=$dbc->find($id);
		//判断当前ID是否有值，如果没有跳转到blank中。
		if(!empty($s['name'])){
			if($s['level']>1){
				$optData=$opt->getOptData('department_info', $id);
				$mpList=$dbc->where('id not in ('.implode(',',$optData['optData']).')')->order('position')->select();
				$this->assign('mpList',$mpList);
				$dbm=M('module_info');
				$bt=$dbm->where('en="departmentDuty"')->find();
				$s['bt']=$this->formatModuleProperty($bt,$s['name']);
			}
			$oList=$opt->getBrother('department_info',$s['parentId'],$id);
			$this->assign('poList',$oList);
			$this->getPlaceModule(session('SESS_optModuleID'),0,$id);
			$this->assign('data',$s);
			if($s['level']==1){
				$dbr=M('base_region','',$this->getDbLink(1));
				$pList=$dbr->where('status>0 and level=1')->order('position')->select();
				$this->assign('pList',$pList);
			}
			$this->display('edit');
		}
		else{
			$this->display('Template/blank');
		}
	}
	public function departmentEditDo(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('department_info');
			$result=$dbc->where("name='".$pi['name']."' and parentId=".$pi['parentId']." and id<>".$pi['id'])->count();
			if($result<1){
				$dbc->startTrans();
				$opt=D('radioedit');
				if($opt->movePosition('department_info',$pi['id'],$pi['parentId'],$pi['position'])){
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
					$result=2;
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
	public function departmentStatus($data)
	{
		$dbc=M('department_info');
		$dbc->startTrans();
		$opt=D('radioedit');
		if($opt->changeStatus('department_info',$data)){
			$dbc->commit();
			$result=true;
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	public function departmentDel($data)
	{
		$dbc=M('department_info');
		$dbc->startTrans();
		$opt=D('radioedit');
		$result=$opt->getDel('department_info',$data);
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
	public function departmentBatchOpen($data='',$parameter){
		$dbc=M('department_info');
		$dbc->startTrans();
		$result=$dbc->where('id in ('.$parameter.')')->setField('status',1);
		if($result!==false){
			$dbc->commit();
			$result=true;
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	public function departmentBatchClose($data='',$parameter){
		$dbc=M('department_info');
		$dbc->startTrans();
		$result=$dbc->where('id in ('.$parameter.')')->setField('status',0);
		if($result!==false){
			$dbc->commit();
			$result=true;
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	public function departmentBatchDel($data='',$parameter){
		$dbc=M('department_info');
		$dbc->startTrans();
		$success=true;
		$opt=D('radiodetail');
		$data=explode(',',$parameter);
		foreach($data as $k=>$v){
			$result=$opt->getDel('department_info',$v);
			if(!$result){
				$success=false;
				break;
			}
		}
		if($success){
			$dbc->commit();
		}
		else{
			$dbc->rollback();
		}
		return $success;
	}
	
	public function departmentDuty($id,$data){
		$this->getPlaceModule($id, 0, $data, 1);
		$param=array(
			'moduleID'=>$id,
			'objectID'=>$data,
		);
		$this->assign('param',$param);
		$this->display('Template/singleTree');
	}
		
	public function departmentDutySave($data,$parameter){
		$dbc=M('department_duty');
		$dbc->startTrans();
		$success=true;
		$res=$dbc->where('department_id='.$data)->delete();
		if($res!==false){
			$t=explode(',',$parameter);
			foreach ($t as $k=>$v){
				$d['id']=$this->createNewID();
				$d['department_id']=$data;
				$d['duty_id']=$v;
				$res=$dbc->add($d);
				if($res<1){
					$success=false;
				}
			}
		}
		if($success){
			$dbc->commit();
		}
		else{
			$dbc->rollback();
		}
		return $success;
	}
	
	public function getCompany($id){
		$dbc=M('department_info');
		$t=$dbc->find($id);
		$data=$dbc->where('position<'.$t['position'].' and level=1')->order('position desc')->find();
		return $data;
	}
}
?>