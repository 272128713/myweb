<?php
namespace Home\Controller;
use Think\Controller;
use Think\Db;
class KserviceController extends TemplateController{
	public function KserviceList($id=0){
		$dbc=M('k_user_buy','',$this->getDbLink(1));
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')]['parameter'];
		$reg=$this->getRegionCatch(session('SESS_optModuleID'));
		
		//dump($id);
		
	
		
		if(is_array($reg)){
			$r=implode(',',$reg);
			$w='district in ('.$r.')';
		}
		else{
			if($reg==9){
				$w='true';
			}
			else{
				$w='district=0';
			}
		}
		if($id>0){
			$rt=$this->getRegion(4,$id);
			if($rt['level']==1){
				$w.=' and province='.$id;
			}
			elseif($rt['level']==2){
				$w.=' and city='.$id;
			}
			else{
				$w.=' and district='.$id;
			}
		}
		
		//echo $w;
		
		$count=$dbc->where($w)->count();
		$result=$dbc->where($w)->limit($pageList['start'],$pageList['limit'])->order('createDate')->select();
		
		//echo $dbc->getLastSql();
		
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);
	}
	public function getKuser($optData){
		$dbc=M('user_base_info','',$this->getDbLink(1));
		$t=$dbc->find($optData['userId']);
		return $t['name'];
	}
	public function getKtype($optData){
		$t='K'.$optData['k_state']/10;
		$c=$optData['isChange']==0?'新购买':'升级';
		$result=$t.'/'.$c;
		return $result;
	}
	public function getKaddress($optData){
		$a=$this->getRegion(4,$optData['province']);
		$add=$a['name'];
		$a=$this->getRegion(4,$optData['city']);
		$add.=$a['name'];
		$a=$this->getRegion(4,$optData['district']);
		$add.=$a['name'];
		$dbc=M('user_base_info','',$this->getDbLink(1));
		$t=$dbc->find($optData['userId']);
		$add='【'.$add.'】'.$t['address'];
		$link='【'.$t['mobile'].'】'.$add;
		return $link;
	}
	public function ksSendOrderItemOpt($optData){
		return 'sendOrder';
	}
	public function KOptStatus($optData){
		if($optData['e_send']>0){
			 
		}
		else{
			$status='未处理';
		}
		return $status;
	}
	public function ksSendOrder($id,$data){
		$dbc=M('k_service');
		$t=$dbc->find($data);
		$dbu=M('user_base_info','',$this->getDbLink(1));
		$u=$dbu->find($t['userId']);
		$d['name']=$u['name'];
		$p=$this->getRegion(4,$t['province']);
		$c=$this->getRegion(4,$t['city']);
		$b=$this->getRegion(4,$t['district']);
		$d['region']=$p['name'].$c['name'].$b['name'];
		$d['address']=$u['address'];
		$d['phone']=$u['mobile'];
		$d['type']=$this->getKtype($t);
		$d['buyTime']=$t['createDate'];
		$this->assign('data',$d);
		
		$dbc=M('power_info');
		//$r=$dbc->join('module_info on module_info.module_id = power_info.module_id')->join('module_role_region on module_role_region.module_id=power_info.module_id and module_role_region.role_id = power_info.role_id')->join('employee_info on employee_info.role_id = power_info.role_id')->join('employee_department on employee_info.id = employee_department.employee_id')->join('department_info on employee_department.department_id = department_info.id')->join('duty_info on duty_info.id=employee_department.duty_id')->field('employee_info.*,department_info.id as deptId,department_info.name as deptName,department_info.position,duty_info.name as dutyName')->where('(module_role_region.province=9 or (module_role_region.province='.$t['province'].' and module_role_region.city=9) or (module_role_region.province='.$t['province'].' and module_role_region.city='.$t['city'].')) and  module_info.module_id='.session('SESS_optModuleID').' and employee_info.status>0')->select();
		$r=$dbc->join('module_info on module_info.module_id = power_info.module_id')->join('module_role_region on module_role_region.module_id=power_info.module_id and module_role_region.role_id = power_info.role_id')->join('employee_info on employee_info.role_id = power_info.role_id')->join('employee_department on employee_info.id = employee_department.employee_id')->field('employee_info.*')->where('(module_role_region.province=9 or (module_role_region.province='.$t['province'].' and module_role_region.city=9) or (module_role_region.province='.$t['province'].' and module_role_region.city='.$t['city'].')) and  module_info.module_id='.session('SESS_optModuleID').' and employee_info.status>0')->select();
		foreach ($r as $k=>$v){
			
		}
		$this->display('operate');		
	} 
}
?>