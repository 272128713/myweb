<?php
/**
 * 所有用户购买的K服务控制器
 * 
 */

namespace Home\Controller;
use Think\Controller;
class 	UserBuyKmanageController extends TemplateController{
	public function index(){
		
	}	

	public function UserBuyKmanageList($id=0){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		$dbc=M('fit_service_record','',$this->getDbLink(1));

		//分地区条件
		$reg=$this->getRegionCatch(session('SESS_optModuleID'));		
		if(is_array($reg)){
			$r=implode(',',$reg);
			$s['fit_service_record.district']  = array('in',$r);
		}
		else{
			if($reg==9){
				//$w='true';
			}
			else{
				//$s['user_base_info.district']  = 0;
			}
		}
		if($id>0){
			$rt=$this->getRegion(4,$id);
			if($rt['level']==1){
				$s['fit_service_record.province']  =$id;
			}
			elseif($rt['level']==2){
				$s['fit_service_record.city']  =$id;
			}
			else{
				$s['fit_service_record.district']  =$id;
			}
		}
		
		
			//搜索条件
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$name=trim($arr[1]);
				$type=$arr[2];
				if($type==1){
					$s['user_base_info.user_type_id']=2;
				}else{
					$s['user_base_info.user_type_id']=1;
				}				
				if($name){
					$s['user_base_info.user_name']=array('like','%'.$name.'%');
				}
				if($arr[3] >= 0 ) $s['fit_service_record.status']=$arr[3];
			}else{//简单搜索
				
			}
		}	

		//dump($condition);
		//dump($s);
		
		//排序条件
		$order=$pageList['order'];
		if($order != ''){
			$arr=explode(',', $order);
		
			$list=array(
					'交定金时间'=>'book_date',
					'补余款时间'=>'pay_date',
					'申请退款时间'=>'back_date',
					'退款完成时间'=>'opt_date'
			);
			$orderStr=$list[$arr[0]].' '.$arr[1];
		}else{
			$orderStr='fit_service_record.id desc';
		}

		if($type==1){//搜索医生
			$count=$dbc->join('LEFT JOIN user_base_info ON fit_service_record.doctor_id = user_base_info.user_id')
			->where($s)
			->count();
			//echo $dbc->getLastSql();
			
			$result=$dbc->field('fit_service_record.user_id,com_sic_service_info.name as k_name,user_base_info.user_name as doctor_name,book_date,pay_date,back_date,opt_date,fit_service_record.status,end_date,fit_service_record.money')
			->join('LEFT JOIN user_base_info ON fit_service_record.doctor_id = user_base_info.user_id')
			->join('LEFT JOIN com_sic_service_info ON fit_service_record.service_id = com_sic_service_info.id')
			->where($s)
			->limit($pageList['start'],$pageList['limit'])
			->order($orderStr)
			->select();
			//echo $dbc->getLastSql();
			foreach($result as &$v){
					
				$stateArr=array(
						0=>'预定',
						1=>'退订',
						2=>'补交余款',
						3=>'申请取消服务',
						4=>'服务完成',
						5=>'更换医生',
						6=>'升级服务'
				);
				$v['user_id']=M('user_base_info','',$this->getDbLink(1))->where('user_id='.$v['user_id'])->getField('user_name');
				$v['status']=$stateArr[$v['status']];
					
			}			
		}else{//搜索用户
			$count=$dbc->join('LEFT JOIN user_base_info ON fit_service_record.user_id = user_base_info.user_id')
			->where($s)
			->count();
			//echo $dbc->getLastSql();
				
			$result=$dbc->field('user_base_info.user_name as buyer_name,com_sic_service_info.name as k_name,doctor_id,book_date,pay_date,back_date,opt_date,fit_service_record.status,end_date,fit_service_record.money')
			->join('LEFT JOIN user_base_info ON fit_service_record.user_id = user_base_info.user_id')
			->join('LEFT JOIN com_sic_service_info ON fit_service_record.service_id = com_sic_service_info.id')
			->where($s)
			->limit($pageList['start'],$pageList['limit'])
			->order($orderStr)
			->select();
			
			
			//echo $dbc->getLastSql();
			foreach($result as &$v){
					
				$stateArr=array(
						0=>'预定',
						1=>'退订',
						2=>'补交余款',
						3=>'申请取消服务',
						4=>'服务完成',
						5=>'更换医生',
						6=>'升级服务'
				);
				$v['doctor_id']=M('user_base_info','',$this->getDbLink(1))->where('user_id='.$v['doctor_id'])->getField('user_name');
				$v['status']=$stateArr[$v['status']];
					
			}			
		}

		
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);		
	}
	
}