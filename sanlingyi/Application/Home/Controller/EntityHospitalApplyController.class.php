<?php
/**
 * 实体诊所申请管理
 * 
 */

namespace Home\Controller;
use Think\Controller;
class EntityHospitalApplyController extends TemplateController{
	public function index(){
		
	}	
	
	public function EntityHospitalApplyList(){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		$dbc=M('entity_skyhospital_apply_info','',$this->getDbLink(1));

		$condition=$pageList['condition'];
		if($condition != ''){			
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
					$end=$arr[2]?$arr[2]:date('Y-m-d',time());
					$start=$arr[1]?$arr[1]:0;
					$start=$start.' 00:00:00';
					$end=$end.' 23:59:59';
					$s['_string'] = 'apply_time >"'.$start.'" and apply_time < "'.$end.'"';	
					if($arr[3]!=''){
						$s['_string'].=" and user_base_info.user_name LIKE '%$arr[3]%'";
					}			
			}			
		}
		//dump($condition);
		$count=$dbc->where($s)
					->join('LEFT JOIN user_base_info ON entity_skyhospital_apply_info.doc_id = user_base_info.user_id')					
					->count();
		$result=$dbc->field('user_name,apply_time,type,targets,clinic_name,address,entity_skyhospital_apply_info.state,id')
					->join('LEFT JOIN user_base_info ON entity_skyhospital_apply_info.doc_id = user_base_info.user_id')
					->where($s)
					->limit($pageList['start'],$pageList['limit'])
					->order('apply_time desc')
					->select();
				
		foreach($result as &$v){
			$res=getAddressArr($v['address']);
			$v['address']=$res['address'];			
			$typeArr=array(
				1=>'全科诊所',
				2=>'中医诊所',
				3=>'专科诊所',
				4=>'健康咨询中心',
				5=>'督脉正阳保健中心',
				6=>'现有诊所',
				7=>'现有健康服务机构',
			);
			$v['type']=$typeArr[$v['type']];
			$targetsArr=array(
					1=>'妇女',
					2=>'离退老人',
					3=>'白领与私营',
					4=>'社区居民',
			);
			$v['targets']=$targetsArr[$v['targets']];		
			$stateArr=array(
					//0：未提交；1：待审批  2通过；3：未通过；
					0=>'未提交',
					1=>'待审批',
					2=>'通过',
					3=>'未通过',
			);
			$v['state']=$stateArr[$v['state']];				
		}				
		//dump($result);
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}

	public function EntityHospitalApplyChangeItemOpt(){
		$result='EntityHospitalApplyChange';
		return $result;
	}
	public function EntityHospitalManageItemOpt(){
		$result='EntityHospitalManage';
		return $result;
	}
	public function EntityHospitalDoctorManageItemOpt(){
		$result='EntityHospitalDoctorManage';
		return $result;
	}
	//修改
	public function EntityHospitalApplyChange($id,$data){
		
		$model=M('entity_skyhospital_apply_info','',$this->getDbLink(1));
		$data=$model->where('id='.$data)->find();
		
		$data['manager_name']=M('employee_info')->where('id='.$data['manager_id'])->getField('name');
		
		
		$hosArr=explode('-',$data['address']);
		
		$data['province']=$hosArr[0];
		$data['city']=$hosArr[1];
		$data['area']=$hosArr[2];
		$data['address']=$hosArr[3];
		//dump($hosArr);
		
		$data['city_name']=M('hat_city','',$this->getDbLink(1))->where('cityID='.$hosArr[1])->getField('city');
		$data['section_name']=M('hat_area','',$this->getDbLink(1))->where('areaID='.$hosArr[2])->getField('area');
		

		
		$provinces=M('hat_province','',$this->getDbLink(1))->select();
		$this->assign('provinces',$provinces);
		
		$citys=M('hat_city','',$this->getDbLink(1))->where('father='.$data['province'])->select();
		$this->assign('citys',$citys);
		
		$areas=M('hat_area','',$this->getDbLink(1))->where('father='.$data['city'])->select();
		$this->assign('areas',$areas);
		
		//查找三个申请人里面有没有已经有诊所的	
		
		$entity_doctor=M('entity_skyhospital_doctor_info','',$this->getDbLink(1));
		
		$res_1=$entity_doctor->field('user_base_info.user_name')->join('LEFT JOIN user_base_info On entity_skyhospital_doctor_info.doctor_id=user_base_info.user_id')
				->where('doctor_id='.$data['doc_id'].' and entity_skyhospital_doctor_info.hospital_id != '.$data['id'])->find();
		
		$res_2=$entity_doctor->field('user_base_info.user_name')->join('LEFT JOIN user_base_info On entity_skyhospital_doctor_info.doctor_id=user_base_info.user_id')
				->where('doctor_id='.$data['doc_a_id'].' and entity_skyhospital_doctor_info.hospital_id != '.$data['id'])->find();		
		//echo $entity_doctor->getLastSql();
		$res_3=$entity_doctor->field('user_base_info.user_name')->join('LEFT JOIN user_base_info On entity_skyhospital_doctor_info.doctor_id=user_base_info.user_id')
				->where('doctor_id='.$data['doc_b_id'].' and entity_skyhospital_doctor_info.hospital_id != '.$data['id'])->find();

			$data['tips']='';
			if($res_1){
				$data['tips'].=$res_1['user_name'].'医生已经在其他诊所';
			}
			if($res_2){
				$data['tips'].=','.$res_2['user_name'].'医生已经在其他诊所';
			}
			if($res_3){
				$data['tips'].=','.$res_3['user_name'].'医生已经在其他诊所';
			}						
			
			$data['tips']=trim($data['tips'],',');
	
		
		$poList=$model->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);	

		//dump($data);

		$this->assign('data',$data);

		$this->display('change');
	}	
	public function EntityHospitalApplyChangeModify(){
		if(IS_POST){
			
			
			//dump($_POST);exit;
			
			actionLog(1,'审批实体诊所');//操作日志记录

			$dbc=M('entity_skyhospital_apply_info','',$this->getDbLink(1));
			
			$doc_dbc=M('entity_skyhospital_doctor_info','',$this->getDbLink(1));
			
			$id=I('post.id');
			$state=I('post.state');		
			
			$dbc->startTrans();
			
			$arr=array('state'=>$state,					
					'type'=>I('post.type'),
					'targets'=>I('post.targets'),
					'address'=>I('post.province').'-'.I('post.city').'-'.I('post.section').'-'.I('post.address'),
					'clinic_name'=>I('post.clinic_name'),
					'clinic_nature'=>I('post.clinic_nature'),
					'clinic_rent'=>I('post.clinic_rent'),
					'clinic_age'=>I('post.clinic_age'),
					'clinic_edge'=>I('post.clinic_edge'),
					'clinic_nature'=>I('post.clinic_nature'),
					'manage_content'=>I('post.manage_content'),
					'manage_state'=>I('post.manage_state'),
					'manage_time'=>date('Y-m-d H:m:s',time()),
					'manager_id'=>$_SESSION['SESS_EmployeeInfo']['id'],
					'longitude'=>I('post.longitude'),
					'latitude'=>I('post.latitude'),
			);
			
			$result=$dbc->where('id='.$id)->save($arr);
			
			if($state==2){//审批通过将，医生信息，同步到诊所于医生关联表

				$doc_dbc->where('hospital_id='.$id)->delete();
				
				$dino=$dbc->where('id='.$id)->find();				
				$doc_dbc->add(array('hospital_id'=>$id,'doctor_id'=>$dino['doc_a_id']));
				$doc_dbc->add(array('hospital_id'=>$id,'doctor_id'=>$dino['doc_b_id']));
				$doc_dbc->add(array('hospital_id'=>$id,'doctor_id'=>$dino['doc_id']));				
			}else{
				//清除诊所于医生信息关联
				$doc_dbc->where('hospital_id='.$id)->delete();
				
				$m_state=I('post.manage_state');
				
				//dump(I('post.manage_content'));exit;
				
				
				if($m_state==1){
															
				}elseif($m_state==2){
					
					$m_arr=array('type'=>0,'clinic_name'=>'');
				}elseif($m_state==3){
					
					$m_arr=array('type'=>0,'clinic_name'=>'');
				}elseif($m_state==4){
					
					$m_arr=array('doc_a_id'=>0);
				}
				$dbc->where('id='.$id)->save($m_arr);
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
	public function EntityHospitalManage($id,$data){  
		
		
		
		$model=M('entity_skyhospital_apply_info','',$this->getDbLink(1));
		$data=$model->where('id='.$data)->find();
		
		$poList=$model->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->assign('data',$data);
		
		$this->display('edit');
	}
	public function EntityHospitalManageModify(){
		if(IS_POST){
			//actionLog(1,'审批实体诊所');//操作日志记录
		
			$dbc=M('entity_skyhospital_apply_info','',$this->getDbLink(1));
				
			$id=I('post.id');
			
				
			$dbc->startTrans();
				
			$arr=array('state'=>$state,
					'other_service_content'=>I('post.other_service_content'),
					'clinic_introduce'=>$_POST['clinic_introduce'],
					'sort'=>$_POST['sort'],
			);				
			$result=$dbc->where('id='.$id)->save($arr);
				
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
	public function EntityHospitalDoctorManage($id,$data){
		
		$model=M('entity_skyhospital_apply_info','',$this->getDbLink(1));
		
		
		
		$hinfo=$model->where('id='.$data)->find();
		
		
		$data=array(
				array('doctor_id'=>$hinfo['doc_a_id']),
				array('doctor_id'=>$hinfo['doc_b_id'],),
				array('doctor_id'=>$hinfo['doc_id']),
			);
		$user_model=M('user_base_info','',$this->getDbLink(1));
		
		foreach ($data as &$val){
			$val['doctor_name']=$user_model->where('user_id='.$val['doctor_id'])->getField('user_name');
			$val['mobile']=$user_model->where('user_id='.$val['doctor_id'])->getField('mobile');
		}		
		$this->assign('data',$data);
		//dump($data);
		
		$poList=$model->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display('doctor');
	}
	public function EntityHospitalDoctorManageModify(){
		if(IS_POST){
			//actionLog(1,'审批实体诊所');//操作日志记录
			$dbc=M('entity_skyhospital_doctor_info','',$this->getDbLink(1));
			$ids=I('post.id');
			$content=I('post.content');
			
			foreach ($ids as $k=>$v){
				$dbc->where('doctor_id='.$v)->save(array('content'=>$content[$k]));
			}
			if($result!==false){			
				$this->ajaxReturn('0');
			}else{
				$this->ajaxReturn('1');
			}
		}else{
			$this->loginError('3');
		}
	}	
	
	
	
}