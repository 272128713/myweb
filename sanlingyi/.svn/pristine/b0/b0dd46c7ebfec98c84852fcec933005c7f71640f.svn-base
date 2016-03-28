<?php
/**
 * 医院信息管理
 * 
 */

namespace Home\Controller;
use Think\Controller;
class HospitalManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function HospitalManageList($id=0){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		$dbc=M('hospital_base_info_sys','',$this->getDbLink(1));
		
		//分地区条件
		$reg=$this->getRegionCatch(session('SESS_optModuleID'));
		
		if(is_array($reg)){
			$r=implode(',',$reg);
			//$w='district in ('.$r.')';
			$s['section']  = array('in',$r);
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
				$s['province']  =$id;
			}
			elseif($rt['level']==2){
				$s['city']  =$id;
			}
			else{
				$s['section']  =$id;
			}
		}

		$condition=$pageList['condition'];
		if($condition != ''){			
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
					if($arr[1]!=''){
						$s['name']=array('like','%'.$arr[1].'%');
					}			
			}			
		}
		//dump($condition);
		$count=$dbc->where($s)->count();
		$result=$dbc->field('name,province,city,section,address,lev,phone,nature,doc_num,id')					
					->where($s)
					->limit($pageList['start'],$pageList['limit'])					
					->select();
				
		foreach($result as &$v){
			$v['province']=M('hat_province','',$this->getDbLink(1))->where('provinceID='.$v['province'])->getField('province');
			$v['city']=M('hat_city','',$this->getDbLink(1))->where('cityID='.$v['city'])->getField('city');
			$v['section']=M('hat_area','',$this->getDbLink(1))->where('areaID='.$v['section'])->getField('area');			
		}				
		//dump($result);
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}

	public function HospitalManageEditItemOpt(){
		$result='HospitalManageEdit';
		return $result;
	}
	public function HospitalManageAddItemOpt(){
		$result='HospitalManageAdd';
		return $result;
	}	
	public function HospitalManageDelItemOpt(){
		$result='HospitalManageDel';
		return $result;
	}
	public function HospitalManageEdit($id,$data){

		$dbc=M('hospital_base_info_sys','',$this->getDbLink(1));
		$result=$dbc->where('id='.$data)->find();		
		//取出模板里面的按钮
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		
		$result['city_name']=M('hat_city','',$this->getDbLink(1))->where('cityID='.$result['city'])->getField('city');
		$result['section_name']=M('hat_area','',$this->getDbLink(1))->where('areaID='.$result['section'])->getField('area');
		
		//dump($result);
		$this->assign('data',$result);
		
		$provinces=M('hat_province','',$this->getDbLink(1))->select();
		$this->assign('provinces',$provinces);
		
		$citys=M('hat_city','',$this->getDbLink(1))->where('father='.$result['province'])->select();
		$this->assign('citys',$citys);
		
		$areas=M('hat_area','',$this->getDbLink(1))->where('father='.$result['city'])->select();
		$this->assign('areas',$areas);		
		
		$this->display('edit');
	}
	
	public function HospitalManageModify(){
		if(IS_POST){
			actionLog(1,'修改医院信息');//操作日志记录
			$data=$_POST;			
			$dbc=M('hospital_base_info_sys','',$this->getDbLink(1));		
			//unset($data['id']);
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
	public function HospitalManageAdd($id,$data){
	
		$dbc=M('hospital_base_info_sys','',$this->getDbLink(1));
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		
		$provinces=M('hat_province','',$this->getDbLink(1))->select();
		$this->assign('provinces',$provinces);
		
		$this->display('edit');
	}
	
	public function HospitalManageCreate(){
		if(IS_POST){
			actionLog(1,'新增医院信息');//操作日志记录
			$data=$_POST;				
			$dbc=M('hospital_base_info_sys','',$this->getDbLink(1));
			unset($data['id']);
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
	public function HospitalManageDel($data){
		$dbc=M('hospital_base_info_sys','',$this->getDbLink(1));
		$t=$dbc->find($data);
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
		actionLog(1,'删除医院信息');//操作日志记录
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