<?php
namespace Home\Controller;
use Think\Controller;
class TreeController extends TemplateController{
	public function employeeTree(){
		$dbc=M('department_info');
		$data=$dbc->where('status>0')->order('position')->select();
		$direct='employeeList';
		$this->tplTree('请选择需要查看的部门',$data,1,2,false,true,$direct);
	}
	
	public function departmentTree(){
		$dbc=M('department_info');
		$data=$dbc->order('position')->select();
		$title='请选择需要管理的部门信息';
		$direct='departmentEdit';
		$this->tplTree($title, $data, 1, 1, false, true, $direct);
	}
	
	public function departmentBatchTree(){
		$dbc=M('department_info');
		$data=$dbc->order('position')->select();
		$this->tplTree('请选择需要管理的部门信息',$data,3,0,true,true);
	}
	
	public function departmentDutyTree($moduleID,$objectID){
		$dbc=M('duty_info');
		$t=$dbc->field('id,name')->where('status>0')->order('position')->select();
		$dbm=M('department_duty');
		$dc=$dbm->where('department_id='.$objectID)->getField('duty_id',true);
		foreach ($t as $k=>$v){
			$t[$k]['parentId']=0;
			$t[$k]['selected']=in_array($v['id'], $dc)?1:0;
		}
		$this->tplTree('请选择需要分配的岗位',$t,3,0,true,true);
	}
	
	public function moduleBatchTree(){
		$dbc=M('module_info');
		$data=$dbc->field('module_id as id,module_name as name,parentId')->order('position')->select();
		$this->tplTree('请选择需要管理的功能模块',$data,3,0,true,true);
	}
	
	public function rolePowerTree($moduleID,$objectID){
		$dbc=M('module_info');
		$d=$dbc->field('module_id as id,module_name as name,parentId')->where('status>0')->order('position')->select();
		$dbm=M('power_info');
		$t=$dbm->where('role_id='.$objectID)->getField('module_id',true);
		foreach ($d as $k=>$v){
			$d[$k]['selected']=in_array($v['id'], $t)?1:0;
		}
		$this->tplTree('请选择需要分配的功能权限',$d,4,0,true,true);
	}
	
	public function roleRegionTree($moduleID,$objectID){
		$dbc=M('module_info');
		$t=$dbc->where('area_limit>0')->select();
		$i=0;
		foreach ($t as $k=>$v){
			$idStr[$i]=$v['module_id'];
			$i++;
			if(!in_array($v['parentId'], $idStr)){
				$idStr[$i]=$v['parentId'];
				$i++;
			}
		}
		$t=$dbc->field('module_id as id,module_name as name,parentId,level')->where('status>0 and module_id in ('.implode(',', $idStr).')')->order('position')->select();
		foreach ($t as $k=>$v){
			$t[$k]['selected']=$v['level']>1?1:0;
		}
		$param=array(
				'moduleID'=>$moduleID,
				'objectID'=>$objectID
		);
		$direct='roleRegionList';
		$this->tplTree('请选择需要设置的模块信息',$t,2,2,true,true,$direct,$param);
	}
	
	public function moduleTree(){
		$dbc=M('module_info');
		$data=$dbc->field(array('module_id'=>'id','module_name'=>'name','parentId'))->order('position')->select();
		$title='请选择需要管理的功能模块';
		$direct='moduleEdit';
		$this->tplTree($title, $data, 1, 1, false, true, $direct);
	}
	
	public function KserviceTree(){
		$title='请选择需要查看的地区';
		$direct='KserviceList';
		$this->tplTree($title,$this->getRegionByModule(),1,2,false,true,$direct);
	}
	
	public function regionTree(){
		$dbc=M('com_region_info','',$this->getDbLink(1));
		$data=$dbc->field('id,name,parentId')->order('position')->select();
		$title='请选择需要管理的地区信息';
		$direct='regionEdit';
		$this->tplTree($title, $data, 1, 1, false, true, $direct);
	}
	public function sectionTree(){
		$dbc=M('com_sic_section_info','',$this->getDbLink(1));
		$data=$dbc->field('id,name,parentId')->order('position')->select();
		$title='请选择需要管理的科室信息';
		$direct='sectionEdit';
		$this->tplTree($title, $data, 1, 1, false, true, $direct);
	}
	public function roleRemindTree($moduleID,$objectID){
		$dbc=M('remind_info');
		$t=$dbc->field('id,name')->where('status>0')->order('position')->select();
		$dbm=M('role_remind');
		$dc=$dbm->where('role_id='.$objectID)->getField('remind_id',true);
		foreach ($t as $k=>$v){
			$t[$k]['parentId']=0;
			$t[$k]['selected']=in_array($v['id'], $dc)?1:0;
		}
		$this->tplTree('请选择需要分配的提醒',$t,3,0,true,true);
	}
	//指定省市的健康代表树
	public function docManageAgencyTree($moduleID,$objectID){
		$docInfo=M('com_dic_doctor_info','',$this->getDbLink(1))->field('province,city')->where('id='.$objectID)->find();
		
		$dbc=M('h_assistant_apply_info','',$this->getDbLink(1));
		
// 		$t=$dbc->field('ast_id,name')->where('state=1  and city='.$docInfo['city'])->select();
// 		//如果该医生所在市没有健康代表，就列出该医生所在省健康代表
// 		if(empty($t)){
// 			$t=$dbc->field('ast_id,name')->where('state=1 and  province='.$docInfo['province'])->select();
// 		}
		$t=$dbc->field('ast_id,name')->where('state=1')->select();
		
		$dbm=M('h_assistant_belong_doctor','',$this->getDbLink(1));
		$dc=$dbm->where('doc_id='.$objectID)->getField('user_id',true);
		foreach ($t as $k=>$v){
			$t[$k]['id']=$v['ast_id'];
			$t[$k]['parentId']=0;
			$t[$k]['selected']=in_array($v['ast_id'], $dc)?1:0;
		}		
		
		
		$this->tplTree('请选择需要分配的健康代表',$t,3,0,true,true);
	}	
	public function DocManageTree(){
		$title='请选择需要查看的地区';
		$direct='DocManageList';
		$dbc=M('com_sic_region_info','',$this->getDbLink(1));
		$result=$dbc->order('position')->select();
		$this->tplTree($title,$result,1,2,false,true,$direct);
	}
		
	public function KmanageTree(){
		$title='请选择需要查看的地区';
		$direct='KmanageList';
		
		$dbc=M('com_sic_region_info','',$this->getDbLink(1));
		$result=$dbc->order('position')->select();
		$this->tplTree($title,$result,1,2,false,true,$direct);
	}	
	public function UserBuyKmanageTree(){
		$title='请选择需要查看的地区';
		$direct='UserBuyKmanageList';
		$dbc=M('com_sic_region_info','',$this->getDbLink(1));
		$result=$dbc->order('position')->select();
		$this->tplTree($title,$result,1,2,false,true,$direct);
	}	
	public function HospitalManageTree(){
		$title='请选择需要查看的地区';
		$direct='HospitalManageList';
		$dbc=M('com_sic_region_info','',$this->getDbLink(1));
		$result=$dbc->order('position')->select();
		$this->tplTree($title,$result,1,2,false,true,$direct);
	}	
	public function UserManageTree(){
		$title='请选择需要查看的地区';
		$direct='UserManageList';
		$dbc=M('com_sic_region_info','',$this->getDbLink(1));
		$result=$dbc->order('position')->select();
		$this->tplTree($title,$result,1,2,false,true,$direct);
	}	
	public function EnHospitalManageTree(){
		$title='请选择需要查看的地区';
		$direct='EnHospitalManageList';
		$dbc=M('com_sic_region_info','',$this->getDbLink(1));
		$result=$dbc->order('position')->select();
		$this->tplTree($title,$result,1,2,false,true,$direct);
	}		
}
?>