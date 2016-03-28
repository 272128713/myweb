<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class EmployeeController extends TemplateController{
	public function employeeList($id=0){		
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		$s['employee_info.name']=array('like','%'.$pageList['condition'].'%');
		$dbc=M('employee_info');
		//查看全部员工
		if(empty($id)){  
			$count=$dbc->where($s)->count();
			$data=$dbc->field('employee_info.name as uname,sex,isDept,role_info.role_name,entry_date,employee_info.id,employee_info.status,department_info.name as dep_name,duty_info.name as duty_name')
					  ->join('LEFT JOIN employee_department ON employee_info.id = employee_department.employee_id')		
		 			  ->join('LEFT JOIN department_info ON employee_department.department_id = department_info.id')
		 			  ->join('LEFT JOIN duty_info ON employee_department.duty_id = duty_info.id')
		 			  ->join('LEFT JOIN role_info ON employee_info.role_id = role_info.role_id')
					  ->where($s)
					  ->limit($pageList['start'],$pageList['limit'])
					  ->select();
			//echo $dbc->getLastSql();
		}
		elseif($id=='UN'){
			//查看未分配的员工
			$s['employee_info.isDept']=0;
			$count=$dbc->where($s)->where('isDept=0')->count();
			$data=$dbc->field('employee_info.name as uname,sex,isDept,role_info.role_name,entry_date,employee_info.id,employee_info.status,department_info.name as dep_name,duty_info.name as duty_name')
					  ->join('LEFT JOIN employee_department ON employee_info.id = employee_department.employee_id')		
		 			  ->join('LEFT JOIN department_info ON employee_department.department_id = department_info.id')
		 			  ->join('LEFT JOIN duty_info ON employee_department.duty_id = duty_info.id')
		 			  ->join('LEFT JOIN role_info ON employee_info.role_id = role_info.role_id')
					  ->where($s)
					  ->limit($pageList['start'],$pageList['limit'])
					  ->select();
		}
		else{
			$s['department_id']=$id;
			$count=$dbc->join('employee_department on employee_info.id=employee_department.employee_id')
						->where($s)
						->order('name')->count();
			$data=$dbc->field('employee_info.name as uname,sex,isDept,role_info.role_name,entry_date,employee_info.id,employee_info.status,department_info.name as dep_name,duty_info.name as duty_name')
					  ->join('LEFT JOIN employee_department ON employee_info.id = employee_department.employee_id')		
		 			  ->join('LEFT JOIN department_info ON employee_department.department_id = department_info.id')
		 			  ->join('LEFT JOIN duty_info ON employee_department.duty_id = duty_info.id')
		 			  ->join('LEFT JOIN role_info ON employee_info.role_id = role_info.role_id')
					  ->where($s)
					  ->limit($pageList['start'],$pageList['limit'])
					  ->order('employee_info.name')
					  ->select();
		}
		
		foreach($data as &$v){
			$sexArr=array(
					0=>'未选择',
					1=>'男',
					2=>'女'
			);
			$v['sex']=$sexArr[$v['sex']];
			$v['isDept']=$v['isDept']>0?$v['dep_name'].'/'.$v['duty_name']:'未分配';
		}
		
		$this->tplListItem(session('SESS_optModuleID'),$data,$count);
	}
	
	public function employeeNone(){
		$this->employeeList('UN');
	}
	
	public function employeeView(){
		$this->employeeList();
	}

	
	public function employeeEditItemOpt(){
		return 'edit';
	}
	
	public function employeeStatusItemOpt($optData){
		$result=$optData['status']>0?'true':'false';
		return $result;
	}
	
	public function employeeDepartmentItemOpt(){
		return 'dept';
	}
	
	public function employeeDelItemOpt(){
		return 'del';
	}
	
	public function employeeAdd($id,$data){
		//地区
		$p=$this->getRegion(1,0);
		$this->assign('pList',$p);
		//公司
		$dbd=M('department_info');
		$c=$dbd->where('level=1 and status>0')->order('position')->select();
		$this->assign('coList',$c);
		//角色
		$dbr=M('role_info');
		$r=$dbr->where('status>0 and status<9')->order('position')->select();
		$this->assign('rList',$r);
		//岗位
		$dbu=M('duty_info');
		$u=$dbu->where('status>0 and status<9')->order('position')->select();
		$this->assign('uList',$u);
		//学历
		$x=F('Degree');
		$this->assign('xList',$x);
		
		$this->getPlaceModule($id, 0, $data);
		$this->display('add');
	}
	
	public function employeeCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('employee_info');
			if($dbc->where("account='".$pi['account']."'")->count()<1){
				$dbc->startTrans();
				$pi['id']=$this->createNewID();
				$t['id']=$this->createNewID();
				$t['employee_id']=$pi['id'];
				$t['department_id']=$pi['department_id'];
				$t['duty_id']=$pi['duty_id'];
				$dbt=M('employee_department');
				$result=$dbt->add($t);
				if($result>0){
					unset($pi['department_id']);
					unset($pi['duty_id']);
					$pi['passwd']=sha1('888888');
					$result=$dbc->add($pi);
					if($result>0){
						$catch['CATCH_ConfigReset']=0; //设置更新配置的状态，用户在第一次登录的时候就会初始化配置信息
						S($pi['id'],$catch);
						$dbc->commit();
						$this->ajaxReturn('0');
					}
					else{
						$dbc->rollback();
						$this->ajaxReturn('2');
					}
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
	
	public function employeeEdit($id,$data){
		$this->getPlaceModule($id, 0);
		$dbc=M('employee_info');
		$t=$dbc->find($data);		
		//角色
		$dbr=M('role_info');
		$r=$dbr->where('status>0 and status<9')->order('position')->select();
		$this->assign('rList',$r);
		
		//地区
		$p=$this->getRegion(1,0);
		$this->assign('pList',$p);
		
		//学历
		$x=F('Degree');
		$this->assign('xList',$x);
		
		$this->assign('data',$t);
		$this->display('edit');
	}
	
	public function employeeModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('employee_info');
			if($dbc->where("id<>".$pi['id']." and name='".$pi['name']."'")->count()<1){
				$result=$dbc->save($pi);				
				S($pi['id'],null);	//清理用户权限缓存			
				if($result!==false){
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
	public function employeeDel($id,$data){
		if(IS_POST){			
			$dbc=M('employee_info');			
				$result=$dbc->delete($id);			
				S($id,null);	//清理用户权限缓存
				if($result!==false){
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
	
	public function employeeDepartment($id,$data){
		//部门
		$dbd=M('department_info');
		$d=$dbd->where('status>0 and level=1')->order('position')->select();
		$this->assign('cList',$d);
		//岗位
		$this->getPlaceModule($id, 0, $data);
		$this->assign('employee_id',$data);
		$param=array(
				'moduleID'=>$id,
				'objectID'=>$data
		);
		$this->assign('param',$param);
		
		$this->tplList($id,$data);
		$this->assign('listController','employeeDepartmentList');
		$this->display('dept');	
	}
	
	public function employeeDepartmentList($moduleID,$objectID){		
		$dbc=M('employee_department');
		$data=$dbc->field('department_info.name as dep_name,duty_info.name as duty_name,employee_department.id')
				  ->join('LEFT JOIN department_info ON employee_department.department_id = department_info.id')
		 		  ->join('LEFT JOIN duty_info ON employee_department.duty_id = duty_info.id')
				  ->where('employee_id='.$objectID)
				  ->select();
		//echo $dbc->getLastSql();
		if(!empty($data)){
			$this->tplListItem($moduleID,$data);
		}
	}
	
	public function employeeDepartmentDelItemOpt(){
		return 'del';
	}
	
	public function employeeDepartmentSave(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('employee_department');
			if($dbc->where('department_id='.$pi['department_id'].' and employee_id='.$pi['employee_id'])->count()<1){
				$success=false;
				$dbc->startTrans();
				$pi['id']=$this->createNewID();
				$result=$dbc->add($pi);
				if($result>0){
					$dbe=M('employee_info');
					$result=$dbe->where('id='.$pi['employee_id'])->setField('isDept',1);
					if($result!==false){
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
	
	public function employeeStatus($data){
		$dbc=M('employee_info');
		$t=$dbc->find($data);
		$s=$t['status']>0?0:1;
		$result=$dbc->where('id='.$data)->setField('status',$s);
		return $result;
	}
	
	public function employeeDepartmentDel($data){
		$dbc=M('employee_department');
		$dbc->startTrans();
		$t=$dbc->find($data);
		$s=$dbc->where('employee_id='.$t['employee_id'])->count();
		$success=false;
		if($s<2){
			$dbe=M('employee_info');
			$result=$dbe->where('id='.$t['employee_id'])->setField('isDept',0);
			if($result>0){
				$success=true;
			}
		}
		else{
			$success=true;
		}
		if($success>0){
			$result=$dbc->delete($data);
			if($result<1){
				$success=false;
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
	
}
?>