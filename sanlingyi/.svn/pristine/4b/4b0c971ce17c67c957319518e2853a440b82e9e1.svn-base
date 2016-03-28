<?php
/**
 * 空中诊所服务管理
 * 
 */

namespace Home\Controller;
use Think\Controller;
class SkyClinicServiceController extends TemplateController{
	public function index(){
		
	}	
	
	public function SkyClinicServiceList($id=0){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];

		$dbc=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
		


		$condition=$pageList['condition'];
		if($condition != ''){			
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
// 					$end=$arr[2]?$arr[2]:date('Y-m-d',time());
// 					$start=$arr[1]?$arr[1]:0;
// 					$start=$start.' 00:00:00';
// 					$end=$end.' 23:59:59';
// 					$s .= ' and reg_date >"'.$start.'" and reg_date < "'.$end.'"';	
// 					if($arr[1]!=''){
// 						$s.=" and sky_clinic_base_info.name LIKE '%$arr[1]%'";					
// 					}							
			}			
		}	
		$where=$s;
		$count=$dbc->where($where)
					->count();
		//echo $dbc->getLastSql();
		$result=$dbc->field('name,price,createDate,toplimit,id')
					->where($where)
					->limit($pageList['start'],$pageList['limit'])
					
					->select();			
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}	
	public function SkyClinicServiceEditItemOpt(){
		$result='SkyClinicServiceEdit';
		return $result;
	}	
	public function SkyClinicServiceAddItemOpt(){
		$result='SkyClinicServiceAdd';
		return $result;
	}	
	public function SkyClinicServiceDelItemOpt(){
		$result='SkyClinicServiceDel';
		return $result;
	}	
	//空中诊所编辑
	public function SkyClinicServiceEdit($id,$data){
		
		$model=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
		$data=$model->where('id='.$data)->find();
		$this->assign('data',$data);
		
		$this->getPlaceModule($id,0);
				
		$this->display('edit');
	}
	public function SkyClinicServiceModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
			$data=array(
					'name'=>$pi['name'],
					'summary'=>$pi['summary'],
					'content'=>$_POST['content'],
					'price'=>$pi['price'],
					'toplimit'=>$pi['toplimit'],
					'image_url'=>$pi['image_url'],
					'inner_image_url'=>$pi['inner_image_url'],
					
			);
			
			$dbc->startTrans();			
			$result=$dbc->where('id='.$pi['id'])->save($data);
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
	public function SkyClinicServiceAdd($id,$data){
	
		$dbc=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
	
	
		$this->getPlaceModule($id,0);
	
	
		$this->display('edit');
	}	
	public function SkyClinicServiceCreate(){
		if(IS_POST){
	
			$pi=I('post.');
			$dbc=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
			$data=array(
					'name'=>$pi['name'],
					'summary'=>$pi['summary'],
					'content'=>$_POST['content'],
					'price'=>$pi['price'],
					'toplimit'=>$pi['toplimit'],
					'image_url'=>$pi['image_url'],
					'inner_image_url'=>$pi['inner_image_url'],
					'createDate'=>date('Y-m-d H:i:m',time()),
			);
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
	public function SkyClinicServiceDel($data){
		$dbc=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
	
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
	
	
}