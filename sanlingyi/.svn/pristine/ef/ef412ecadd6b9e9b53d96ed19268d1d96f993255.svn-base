<?php
/**
 * 空中诊所管理
 * 
 */

namespace Home\Controller;
use Think\Controller;
class EnHospitalManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function EnHospitalManageList($id=0){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];

		$dbc=M('sky_clinic_base_info','',$this->getDbLink(4));
		

		
		//分地区条件
		$reg=$this->getRegionCatch(session('SESS_optModuleID'));
		if(is_array($reg)){
			$r=implode(',',$reg);
			//$w='district in ('.$r.')';
			$s['sky_clinic_base_info.area']  = array('in',$r);
		}
		else{
			if($reg==9){
				//$w='true';
			}
			else{
				//$s['com_dic_user_info.district']  = 0;
			}
		}
		if($id>0){
			$rt=$this->getRegion(4,$id);
			if($rt['level']==1){
				$s['sky_clinic_base_info.province']  =$id;
			}
			elseif($rt['level']==2){
				$s['sky_clinic_base_info.city']  =$id;
			}
			else{
				$s['sky_clinic_base_info.area']  =$id;
			}
		}
		
		

		$condition=$pageList['condition'];
		if($condition != ''){			
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
// 					$end=$arr[2]?$arr[2]:date('Y-m-d',time());
// 					$start=$arr[1]?$arr[1]:0;
// 					$start=$start.' 00:00:00';
// 					$end=$end.' 23:59:59';
// 					$s .= ' and reg_date >"'.$start.'" and reg_date < "'.$end.'"';	
					if($arr[1]!=''){
						$s.=" and sky_clinic_base_info.name LIKE '%$arr[1]%'";					
					}							
			}			
		}	
		$where=$s;
		$count=$dbc->where($where)
					->count();
		//echo $dbc->getLastSql();
		$result=$dbc->field('name,area,address,id')
					->where($where)
					->limit($pageList['start'],$pageList['limit'])
					
					->select();
		//echo $dbc->getLastSql();
		foreach($result as &$v){					
			$da=$dbc->find($v['id']);
			$areaInfo=M('com_sic_region_info','',$this->getDbLink(1))->where('id='.$v['area'])->getField('name');
			$cityInfo=M('com_sic_region_info','',$this->getDbLink(1))->where('id='.$da['city'])->getField('name');
			$provinceInfo=M('com_sic_region_info','',$this->getDbLink(1))->where('id='.$da['province'])->getField('name');
			$v['area']=$provinceInfo.$cityInfo.$areaInfo;					
		}				
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}	
	public function EnHospitalManageEditItemOpt(){
		$result='EnHospitalManageEdit';
		return $result;
	}	
	public function EnHospitalManageAddItemOpt(){
		$result='EnHospitalManageAdd';
		return $result;
	}	
	public function EnHospitalManageDelItemOpt(){
		$result='EnHospitalManageDel';
		return $result;
	}
	public function EnHospitalManageEmployeeItemOpt(){
		$result='EnHospitalManageEmployee';
		return $result;
	}		
	public function EnHospitalManageEmployeeListItemOpt(){
		$result='EnHospitalManageEmployeeList';
		return $result;
	}	
	public function EnHospitalManageEmployeeChangeItemOpt(){
		$result='EnHospitalManageEmployeeChange';
		return $result;
	}	
	public function EnHospitalManageEmployeeDelItemOpt(){
		$result='EnHospitalManageEmployeeDel';
		return $result;
	}	
	public function EnHospitalManageServiceItemOpt(){
		$result='EnHospitalManageService';
		return $result;
	}	
	public function EnHospitalManageServiceEditItemOpt(){
		$result='EnHospitalManageServiceEdit';
		return $result;
	}
	public function EnHospitalManageServiceDelItemOpt(){
		$result='EnHospitalManageServiceDel';
		return $result;
	}	
	public function EnHospitalManageMonthItemOpt(){
		$result='EnHospitalManageMonth';
		return $result;
	}
	public function EnHospitalManageMonthEditItemOpt(){
		$result='EnHospitalManageMonth';
		return $result;
	}
	public function EnHospitalManageMonthDelItemOpt(){
		$result='EnHospitalManageMonth';
		return $result;
	}		
	//空中诊所编辑
	public function EnHospitalManageEdit($id,$data){
		
		$model=M('sky_clinic_base_info','',$this->getDbLink(4));
		$data=$model->where('id='.$data)->find();
		$this->assign('data',$data);
		
		//dump($data);
		
		$provinces=M('hat_province','',$this->getDbLink(1))->select();
		$this->assign('provinces',$provinces);
		
		$citys=M('hat_city','',$this->getDbLink(1))->where('father='.$data['province'])->select();
		$this->assign('citys',$citys);
		
		$areas=M('hat_area','',$this->getDbLink(1))->where('father='.$data['city'])->select();
		$this->assign('areas',$areas);
		
		
		$this->getPlaceModule($id,0);
		
		
		$this->display('edit');
	}
	public function EnHospitalManageModify(){
		if(IS_POST){
			$data=$_POST;
			$dbc=M('sky_clinic_base_info','',$this->getDbLink(4));
			$dbc->startTrans();			
			$result=$dbc->where('id='.$data['id'])->save($data);
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
	//添加
	public function EnHospitalManageAdd($id,$data){
	
		$model=M('sky_clinic_base_info','',$this->getDbLink(4));
	
		$provinces=M('hat_province','',$this->getDbLink(1))->select();
		$this->assign('provinces',$provinces);
	
		$citys=M('hat_city','',$this->getDbLink(1))->where('father='.$data['province'])->select();
		$this->assign('citys',$citys);
	
		$areas=M('hat_area','',$this->getDbLink(1))->where('father='.$data['city'])->select();
		$this->assign('areas',$areas);
	
	
		$this->getPlaceModule($id,0);
	
	
		$this->display('edit');
	}	
	public function EnHospitalManageCreate(){
		if(IS_POST){
	
			$data=$_POST;
			$dbc=M('sky_clinic_base_info','',$this->getDbLink(4));
			$dbc->startTrans();			
			$result=$dbc->add($data);
	
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
	public function EnHospitalManageDel($data){
		$dbc=M('sky_clinic_base_info','',$this->getDbLink(4));
	
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
	
		if($result!==false){
			$dbc->commit();
			$success=true;
		}
		else{
			$dbc->rollback();
		}

		return $success;
	}
	//添加
	public function EnHospitalManageEmployee($id,$data){
	
		$model=M('sky_clinic_employee_info','',$this->getDbLink(4));
		$this->assign('id',$data);

		$this->getPlaceModule($id,0);
	
	
		$this->display('employee');
	}
	//搜索手机号获取用户信息
	public function searchMobile(){
		$mobile=$_POST['mobile'];
		$dbc=M('user_base_info','',$this->getDbLink(5));
		$res=$dbc->field('user_id,mobile,user_name,privilege_id')->where('mobile like "%'.$mobile.'%"')->select();
		foreach ($res as &$val){
			$is_doctor=substr($val['privilege_id'], 1,1);//医生
			$val['privilege_id']=$is_doctor>0?'医生':'用户';
		}
		echo json_encode($res);
	}	
	//设置用户身份
	public function setType(){
		$user_id=$_POST['user_id'];
		$hospital_id=$_POST['hospital_id'];
		$type=$_POST['type'];
		
		$dbc=M('sky_clinic_employee_info','',$this->getDbLink(4));
		
		$res=$dbc->where(array('employee_id'=>$user_id,'clinic_id'=>$hospital_id))->find();
		//echo $dbc->getLastSql(); 
		//已经在该诊所
		if($res){
			echo  '2';
			return false;
		}
		$params=array(
					'employee_id'=>$user_id,
					'clinic_id'=>$hospital_id,
					'createDate'=>date('Y-m-d H:i:m',time()),
					'type'=>$type,	
		);
		
		$id=$dbc->add($params);
		if($id){
			echo  '1';
			return false;
		}else{
			echo  '3';
			return false;
		}
		
		
	}
	public function EnHospitalManageEmployeeList($id,$data){
		$param=array(
				'moduleID'=>$id,
				'objectID'=>$data
		);
		$this->assign('param',$param);
		
		$this->tplList($id,$data);
		$this->assign('listController','EnEmployeeList');
		$this->display('dept');
	}	
	public function EnEmployeeList($moduleID,$objectID){
		
		$dbc=M('sky_clinic_employee_info','',$this->getDbLink(4));
		$user_dbc=M('user_base_info','',$this->getDbLink(5));
		$result=$dbc->field('id,employee_id,createDate,type')->where(array('clinic_id'=>$objectID))->select();
		$new_result=array();
		foreach ($result as $key=>$val){
			$arr=array(
					1=>'医生',
					2=>'督脉正阳师',
					3=>'健康代表'
			);
			$user_info=$user_dbc->where('user_id='.$val['employee_id'])->find();
			$new_result[$key]['mobile']=$user_info['mobile'];
			$new_result[$key]['user_name']=$user_info['user_name'];
			$new_result[$key]['type']=$arr[$val['type']];
			$new_result[$key]['createDate']=$val['createDate'];
			$new_result[$key]['id']=$val['id'];
		}
		//dump($new_result);
		$this->tplListItem($moduleID,$new_result);
	}
	
	public function EnHospitalManageEmployeeChange($id,$data){
		
		$this->getPlaceModule($id,0);
		$this->display('employeeChange');		
	}
	//删除诊所人员
	public function EnHospitalManageEmployeeDel($data){
		//首先检查可删除的条件

		//删除人员
		$dbc=M('sky_clinic_employee_info','',$this->getDbLink(4));
		
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
		//echo $dbc->getLastSql();
	
		if($result!==false){
			$dbc->commit();
			$success=true;
		}
		else{
			$dbc->rollback();
		}

		return $success;
	}
	public function EnHospitalManageService($id,$data){
		$param=array(
				'moduleID'=>$id,
				'objectID'=>$data
		);
		$this->assign('param',$param);
		$this->getPlaceModule($id,0);
		$this->tplList($id,$data);
		$this->assign('listController','EnServiceList');
		
		$service_dbc=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
		$dbc=M('sky_clinic_service_base_info','',$this->getDbLink(4));
		$service_list=$service_dbc->select();
		$sList=array();
		foreach ($service_list as $val){
			$res=$dbc->where(array('service_id'=>$val['id'],'clinic_id'=>$data))->find();
			if(!$res){
				$sList[]=$val;
			}
		}
		if(empty($sList)){
			$sList[0]=array('id'=>0,'name'=>'无服务可选');
		}
		$this->assign('sList',$sList);
		$this->assign('clinic_id',$data);
		$this->assign('default','');
		$this->display('service');
	}	
	public function EnServiceList($moduleID,$objectID){
	
		$service_dbc=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
		$dbc=M('sky_clinic_service_base_info','',$this->getDbLink(4));
		
		$result=$dbc->field('service_id,state,drift,price,index_key,id')->where(array('clinic_id'=>$objectID))->order(' index_key asc')->select();
		$new_result=array();
		foreach ($result as $key=>$val){
			$arr=array(
					1=>'开启',
					0=>'关闭',
			);
			$serviceInfo=$service_dbc->where(array('id'=>$val['service_id']))->find();
			
			$new_result[$key]['name']=$serviceInfo['name'];
			$new_result[$key]['price']=$val['price'];
			$new_result[$key]['drift']=$val['drift'];
			$new_result[$key]['state']=$arr[$val['state']];
			$new_result[$key]['index_key']=$val['index_key'];
			$new_result[$key]['id']=$val['id'];
		}
		//dump($new_result);
		$this->tplListItem($moduleID,$new_result);
	}	
	public function	EnHospitalManageServiceCreate(){
		if(IS_POST){
		
			$data=$_POST;
			$dbc=M('sky_clinic_service_base_info','',$this->getDbLink(4));
			$params=array(
				'service_id'=>$data['service_id'],
				'clinic_id'=>$data['clinic_id'],
				'drift'=>$data['drift'],
				'price'=>$data['price'],
				'index_key'=>$data['index_key'],
				'state'=>0,
			);
			$dbc->startTrans();
			$result=$dbc->add($params);
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
	public function EnHospitalManageServiceEdit($id,$data){
		
		$dbc=M('sky_clinic_service_base_info','',$this->getDbLink(4));
		$result=$dbc->where(array('id'=>$data))->find();
		$this->assign('data',$result);
		
		$service_dbc=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
		$base_info=$service_dbc->where(array('id'=>$result['service_id']))->find();
		$this->assign('base',$base_info);
	
		$this->getPlaceModule($id,0);
		$this->display('serviceChange');
	}	
	//删除诊所人员
	public function EnHospitalManageServiceDel($data){
		//首先检查可删除的条件

		//删除人员
		$dbc=M('sky_clinic_service_base_info','',$this->getDbLink(4));
		
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
		//echo $dbc->getLastSql();
	
		if($result!==false){
			$dbc->commit();
			$success=true;
		}
		else{
			$dbc->rollback();
		}

		return $success;
	}	
	public function EnHospitalManageServiceModify(){
		if(IS_POST){

			$data=$_POST;
			$dbc=M('sky_clinic_service_base_info','',$this->getDbLink(4));
			$dbc->startTrans();
			$result=$dbc->where('id='.$data['id'])->save($data);
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
	//诊所设置月定制包关联
	public function EnHospitalManageMonth($id,$data){
		$param=array(
				'moduleID'=>$id,
				'objectID'=>$data
		);
		$this->assign('param',$param);
		$this->getPlaceModule($id,0);
		$this->tplList($id,$data);
		$this->assign('listController','EnMonthList');
	
		$month_dbc=M('sky_service_package_info','',$this->getDbLink(4));
		$dbc=M('sky_clinic_service_package_price','',$this->getDbLink(4));
		$month_list=$month_dbc->select();
		$sList=array();
		foreach ($month_list as $val){
			$res=$dbc->where(array('package_id'=>$val['id'],'clinic_id'=>$data))->find();
			if(!$res){
				$sList[]=$val;
			}
		}
		if(empty($sList)){
			$sList[0]=array('id'=>0,'name'=>'无定制包可选');
		}
		$this->assign('sList',$sList);
		$this->assign('clinic_id',$data);
		$this->assign('default','');
		$this->display('month');
	}
	public function EnMonthList($moduleID,$objectID){
	
		$month_dbc=M('sky_service_package_info','',$this->getDbLink(4));
		$dbc=M('sky_clinic_service_package_price','',$this->getDbLink(4));
	
		$result=$dbc->field('package_id,price,id')->where(array('clinic_id'=>$objectID))->select();
		$new_result=array();
		foreach ($result as $key=>$val){

			$monthInfo=$month_dbc->where(array('id'=>$val['package_id']))->find();
				
			$new_result[$key]['name']=$monthInfo['name'];
			$new_result[$key]['price']=$val['price'];
			$new_result[$key]['id']=$val['id'];
		}
		//dump($new_result);
		$this->tplListItem($moduleID,$new_result);
	}
	public function	EnHospitalManageMonthCreate(){
		if(IS_POST){
	
			$data=$_POST;
			$dbc=M('sky_clinic_service_package_price','',$this->getDbLink(4));
			$params=array(
					'package_id'=>$data['month_id'],
					'clinic_id'=>$data['clinic_id'],
					'price'=>$data['price'],
			);
			$dbc->startTrans();
			$result=$dbc->add($params);
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
	public function EnHospitalManageMonthEdit($id,$data){
	
		$dbc=M('sky_clinic_service_package_price','',$this->getDbLink(4));
		$result=$dbc->where(array('id'=>$data))->find();
		$this->assign('data',$result);
	
		$month_dbc=M('sky_service_package_info','',$this->getDbLink(4));
		$base_info=$month_dbc->where(array('id'=>$result['package_id']))->find();
		$this->assign('base',$base_info);
	
		$this->getPlaceModule($id,0);
		$this->display('monthChange');
	}
	//删除诊所人员
	public function EnHospitalManageMonthDel($data){
		//首先检查可删除的条件
	
		//删除人员
		$dbc=M('sky_clinic_service_package_price','',$this->getDbLink(4));
	
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
		//echo $dbc->getLastSql();
	
		if($result!==false){
			$dbc->commit();
			$success=true;
		}
		else{
			$dbc->rollback();
		}
	
		return $success;
	}
	public function EnHospitalManageMonthModify(){
		if(IS_POST){
	
			$data=$_POST;
			$dbc=M('sky_clinic_service_package_price','',$this->getDbLink(4));
			$dbc->startTrans();
			$result=$dbc->where('id='.$data['id'])->save($data);
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