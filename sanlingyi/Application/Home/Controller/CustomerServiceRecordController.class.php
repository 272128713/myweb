<?php
/**
 *  客服回访分配记录表
 * 
 */

namespace Home\Controller;
use Think\Controller;
class CustomerServiceRecordController extends TemplateController{
	public function index(){
		
	}	
	
	public function CustomerServiceRecordList($id=0){
		//取得分页设置
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		
		$dbc=M('customer_service_record','',$this->getDbLink(1));
		$employee_dbc=M('employee_info');
		
		//搜索条件
		if($_SESSION['SESS_EmployeeInfo']['role_id']==C('CUSTOMER_ROLE_ID')){
			$condition=array('customer_service_record.employee_id'=>$_SESSION['SESS_EmployeeInfo']['id']);
		}
		
		$count=$dbc->where($condition)->count();
		
		//设置分页		
		$result=$dbc->field('user_base_info.user_name,user_base_info.mobile,customer_service_record.create_time,customer_service_record.employee_id,customer_service_record.end_time,customer_service_record.state,customer_service_record.id')
					->join('LEFT JOIN user_base_info ON customer_service_record.doctor_id = user_base_info.user_id')					
					->limit($pageList['start'],$pageList['limit'])
					->where($condition)
					->order('customer_service_record.create_time desc')					
					->select();		
		//echo $dbc->getLastSql();
		foreach($result as &$v){					
			$v['state']=empty($v['state'])?'未处理':'已处理';
			$v['employee_id']= $employee_dbc->where('id='.$v['employee_id'])->getField('name');			
		}
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);		
	}
	
	public function CustomerServiceRecordAddItemOpt(){
		$result='customer_service_record';
		return $result;
	}
	
	
	public function CustomerServiceRecordAdd($id,$data){

		$dbc=M('customer_service_record','',$this->getDbLink(1));
		$result=$dbc->where('id='.$data)->find();
		//dump($result);	

		if(empty($result['state'])){
			//取出模板里面的按钮
			$poList=$dbc->order('position')->select();
			$this->getPlaceModule($id,0);
			$this->assign('poList',$poList);			
		}

		
		$this->assign('data',$result);
		$this->display('edit');
	}
	
	public function CustomerServiceRecordCreate(){
		if(IS_POST){
			actionLog(1,'客服回访');//操作日志记录
			$data=$_POST;
			
			$dbc=M('customer_service_record','',$this->getDbLink(1));
			

			$data['state']=1;
			$data['end_time']=date('Y-m-d H:i:s',time());
			
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
	
				
}