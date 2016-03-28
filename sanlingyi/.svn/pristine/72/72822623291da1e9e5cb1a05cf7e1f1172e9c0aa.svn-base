<?php
/**
 * 月定制包管理
 * 
 */

namespace Home\Controller;
use Think\Controller;
class MonthOrderController extends TemplateController{
	public function index(){
		
	}	
	
	public function MonthOrderList($id=0){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];

		$dbc=M('sky_service_package_info','',$this->getDbLink(4));
		

		$result=$dbc->field('name,price,createDate,id')
					->where($where)
					->select();			
		$this->tplListItem(session('SESS_optModuleID'),$result,count($result));	
	}	
	public function MonthOrderEditItemOpt(){
		$result='MonthOrderEdit';
		return $result;
	}	
	public function MonthOrderAddItemOpt(){
		$result='MonthOrderAdd';
		return $result;
	}	
	public function MonthOrderDelItemOpt(){
		$result='MonthOrderDel';
		return $result;
	}	
	//空中诊所编辑
	public function MonthOrderEdit($id,$data){
		
		$model=M('sky_service_package_info','',$this->getDbLink(4));
		$include_model=M('sky_service_package_incloude_info','',$this->getDbLink(4));
		$result=$model->where('id='.$data)->find();
		$this->assign('data',$result);
		
		$service_dbc=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
		$service_list=$service_dbc->select();
		foreach ($service_list as $key=>&$val){
			$num=$include_model->where(array('package_id'=>$data,'service_id'=>$val['id']))->getField('nums');
			$val['num']=$num>0?$num:0;
		}
		//dump($service_list);
		$this->assign('service_list',$service_list);
		
		$this->getPlaceModule($id,0);
		
		$dbc=M('com_sic_region_info','',$this->getDbLink(1));
		$region=$dbc->where(array('parentId'=>0))->limit(31)->select();
		$price_arr=unserialize($result['price_remarks']); 
		
		foreach ($region as $key=>&$val){			
			$price=$price_arr[$val['id']];			
			$val['price']=$price>0?$price:0;
		}
		
		
		$this->assign('region',$region);
				
		$this->display('edit');
	}
	public function MonthOrderModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('sky_service_package_info','',$this->getDbLink(4));
			$include_model=M('sky_service_package_incloude_info','',$this->getDbLink(4));
				
			$data=array(
					'name'=>$pi['name'],
					'content'=>$_POST['content'],
					'price'=>$pi['price'],
					'price_remarks'=>serialize($pi['p']),
			);
			
			$dbc->startTrans();				
			//更新之前分省价格
			$price_remarks=$dbc->where('id='.$pi['id'])->getField('price_remarks');
			$old_p=unserialize($price_remarks);
		
			//更新之后分省价格
			$new_p=$pi['p'];
			$need_update=array();
			foreach ($new_p as $k=>$v){
				//该省价格有修改
				if($v!==$old_p[$k]){
					$need_update[$k]=$v;
				}
			}
			$result=$dbc->where('id='.$pi['id'])->save($data);
			
			
			foreach ($pi['num'] as $key=>$val){				
				
				$had=$include_model->where(array('package_id'=>$pi['id'],'service_id'=>$key))->find();
				//服务存在就更新
				if($had){
					if($val > 0){
						$flag=$include_model->where(array('package_id'=>$pi['id'],'service_id'=>$key))->save(array('nums'=>intval($val)));
					}else{
						$flag=$include_model->where(array('package_id'=>$pi['id'],'service_id'=>$key))->delete();
					}	
				}else{//服务不存在就插入
					if($val>0){
						$flag=$include_model->add(array('createDate'=>date('Y-m-d H:i:m',time()),'package_id'=>$pi['id'],'service_id'=>$key,'nums'=>intval($val)));
					}
				}
				if($flag===false){
					$result=false;
				}				
			}	
			$clinic_dbc=M('sky_clinic_base_info','',$this->getDbLink(4));
			$clinic_price_dbc=M('sky_clinic_service_package_price','',$this->getDbLink(4));
			
			
			//更新各个诊所关联的定制包价格
			foreach ($need_update as $key=>$val){				
				$clinic_list=$clinic_dbc->where(array('province'=>$key))->getField('id',true);				
				if(is_array($clinic_list)){
					$map['package_id']=$pi['id'];
					$map['clinic_id']  = array('in',implode(',',$clinic_list));					
					$clinic_price_dbc->where($map)->save(array('price'=>$val));					
				}
			}
			
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
	public function MonthOrderAdd($id,$data){
	
		$service_dbc=M('sky_sys_clinic_service_base_info','',$this->getDbLink(4));
		$service_list=$service_dbc->select();
		$this->assign('service_list',$service_list);	
		$this->getPlaceModule($id,0);
		$this->display('edit');
	}	
	public function MonthOrderCreate(){
			if(IS_POST){
			$pi=I('post.');
			$dbc=M('sky_service_package_info','',$this->getDbLink(4));
			$include_model=M('sky_service_package_incloude_info','',$this->getDbLink(4));				
			$data=array(
					'name'=>$pi['name'],
					'content'=>$_POST['content'],
					'price'=>$pi['price'],		
					'createDate'=>date('Y-m-d H:i:m',time())
			);			
			$dbc->startTrans();			
			$id=$dbc->add($data);						
			foreach ($pi['num'] as $key=>$val){								
				if($val>0){
					$result=$include_model->add(array('createDate'=>date('Y-m-d H:i:m',time()),'package_id'=>$id,'service_id'=>$key,'nums'=>intval($val)));
				}				
			}
						
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
	public function MonthOrderDel($data){
		//首先应该检查可删除条件
		
		//删除
		$dbc=M('sky_service_package_info','',$this->getDbLink(4));
		$include_model=M('sky_service_package_incloude_info','',$this->getDbLink(4));
		
	
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);		
		$result=$include_model->where(array('package_id'=>$data))->delete();
	
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