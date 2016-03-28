<?php
/**
 *  保险产品管理控制器
 * 
 */

namespace Home\Controller;
use Think\Controller;
class InsuranceManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function InsuranceManageList($id=0){	

		//dump($_SERVER['HTTP_HOST'] );
		//取得分页设置
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		
		$dbc=M('entity_insurance_info','',$this->getDbLink(1));				
		
		//搜索条件
		$condition=$pageList['condition'];

		//排序条件
		$order=$pageList['order'];

		$count=$dbc->count();
		//设置分页		
		$result=$dbc->field('title,entity_insurance_class.class_name,entity_company.name,sale_num,entity_insurance_info.create_time,employee_id,entity_insurance_info.sort,entity_insurance_info.id')
					->join('LEFT JOIN entity_company ON entity_insurance_info.company_id=entity_company.id')
					->join('LEFT JOIN entity_insurance_class ON entity_insurance_info.class_id=entity_insurance_class.id')										
					->order('entity_insurance_info.sort asc')->select();		
		//echo $dbc->getLastSql();
		foreach($result as &$v){								
			$v['employee_id']=M('employee_info')->where('id='.$v['employee_id'])->getField('name');
			$stateArr=array(
				0=>'未发布',
				1=>'已发布',
				2=>'已停止',		
			);
			$v['state']=$stateArr[$v['state']];			
		}
		//dump($result);
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);		
	}
	
	public function InsuranceManageEditItemOpt(){
		$result='InsuranceManageEdit';
		return $result;
	}
	public function InsuranceManageAddItemOpt(){
		$result='InsuranceManageAdd';
		return $result;
	}	
	public function InsuranceManageDelItemOpt(){
		$result='InsuranceManageDel';
		return $result;
	}	
	
	public function InsuranceManageEdit($id,$data){

		$dbc=M('entity_insurance_info','',$this->getDbLink(1));
		$result=$dbc->where('id='.$data)->find();		
		//取出模板里面的按钮
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		
		$class=M('entity_insurance_class','',$this->getDbLink(1))->where('state=1')->select();
		$this->assign('class',$class);
		$company=M('entity_company','',$this->getDbLink(1))->where('state=1')->select();
		$this->assign('company',$company);		
		$this->assign('data',$result);
		$this->display('edit');
	}
	
	public function InsuranceManageModify(){
		if(IS_POST){
			//actionLog(1,'活动修改');//操作日志记录
			$data=$_POST;
			
			$dbc=M('entity_insurance_info','',$this->getDbLink(1));			
			$result=$dbc->where('id='.$data['id'])->save($data);
			
			//echo $dbc->getLastSql();
			
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
	public function InsuranceManageAdd($id,$data){
	
		$dbc=M('entity_insurance_info','',$this->getDbLink(1));
		
		$class=M('entity_insurance_class','',$this->getDbLink(1))->where('state=1')->select();		
		$this->assign('class',$class);
		$company=M('entity_company','',$this->getDbLink(1))->where('state=1')->select();
		$this->assign('company',$company);		
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display('edit');
	}
	
	public function InsuranceManageCreate(){
		if(IS_POST){
			//actionLog(1,'新增活动');//操作日志记录
			$data=$_POST;
			
			//dump($data);exit;
				
			$dbc=M('entity_insurance_info','',$this->getDbLink(1));

			$data['employee_id']=$_SESSION['SESS_EmployeeInfo']['id'];
			$data['create_time']=date('Y-m-d H:i:s',time());
			$result=$dbc->add($data);
				
			//echo $dbc->getLastSql();exit;
				
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
	public function InsuranceManageDel($data){
		$dbc=M('entity_insurance_info','',$this->getDbLink(1));
		$t=$dbc->find($data);
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
		actionLog(1,'删除活动');//操作日志记录
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