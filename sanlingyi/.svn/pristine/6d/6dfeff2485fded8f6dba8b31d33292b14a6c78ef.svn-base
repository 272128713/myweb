<?php
//健康代表审批控制器
namespace Home\Controller;
use Think\Controller;
class HealthAgencyController extends TemplateController{
	public function index(){
		
	}	
	public function HealthAgencyList(){		
		//取得分页设置
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		
		//搜索条件
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$map['h_assistant_apply_info.name']=array('like','%'.$arr[1].'%');
				$map['com_dic_user_info.mobile']=array('like','%'.$arr[2].'%');
				$data=array('name'=>$arr[1],'mobile'=>$arr[2]);
				$this->assign('date',$data);
			}else{//简单搜索
		
			}
		}
		
		//信息不全的不显示
		$map['iDcardUrl']  = array('NEQ','');
		$map['iDcardUrl_back']  = array('NEQ','');
		$map['diplomaUrl_f']  = array('NEQ','');
		$map['diplomaUrl_s']  = array('NEQ','');
		$map['job_seniorityUrl_f']  = array('NEQ','');
		$map['job_seniorityUrl_s']  = array('NEQ','');	
		
		
		$dbc=M('h_assistant_apply_info','',$this->getDbLink(1));			
		
		
		$count=$dbc->join('LEFT JOIN com_dic_user_info ON h_assistant_apply_info.ast_id = com_dic_user_info.id')
					->where($map)
					->count();
		//echo $dbc->getLastSql();
		
		$result=$dbc->field('h_assistant_apply_info.name,com_dic_user_info.mobile,h_assistant_apply_info.sex,h_assistant_apply_info.birthday,h_assistant_apply_info.address,degree,h_assistant_apply_info.apply_time,h_assistant_apply_info.state,h_assistant_apply_info.id')
					->join('LEFT JOIN com_dic_user_info ON h_assistant_apply_info.ast_id = com_dic_user_info.id')
					->where($map)
					->order('h_assistant_apply_info.apply_time desc')
					->limit($pageList['start'],$pageList['limit'])					
					->select();
		foreach ($result as &$val){
			//$val['agent_id']=M('com_dic_authentication_info','',$this->getDbLink(1))->where('id='.$val['agent_id'])->getField('name');
			$statusArr=array(
				0=>'未审批',
				1=>'已通过',
				2=>'禁止',
			);
			$eduArr=array(
					0=>'初中',
					1=>'高中',
					2=>'大专',
					3=>'本科',
					4=>'硕士',
					5=>'博士',
			);						
			
			$val['state']=$statusArr[$val['state']];
			$val['degree']=$eduArr[$val['degree']];
			$val['sex']=$val['sex']>1?'女':'男';
		}		
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);
	}
	//设置列表上的操作
	public function healthAgencyAuthItemOpt($optData){
		return 'healthAgencyAuth';
	}
	public function healthAgencyAuth($id,$data){
		
		$region=M('com_sic_region_info','',$this->getDbLink(1));
		
		$dbc=M('h_assistant_apply_info','',$this->getDbLink(1));
		
		$data=$dbc->where('id='.$data)->find();
		
		$data['org_id']=M('h_assistant_belong_org','',$this->getDbLink(1))->where('user_id='.$data['ast_id'])->getField('org_id');

		
		$data['mobile']=M('user_base_info','',$this->getDbLink(1))->where('user_id='.$data['ast_id'])->getField('mobile');
		
		$eduArr=array(
				0=>'初中',
				1=>'高中',
				2=>'大专',
				3=>'本科',
				4=>'硕士',
				5=>'博士',
		);						
		$data['degree']=$eduArr[$data['degree']];
		$data['sex']=  $data['sex']>1?'女':'男';
		
		$data['iDcardUrl']=strtr(C('IMG_URL').$data['iDcardUrl'],array('M00'=>'MOO/data'));
		$data['iDcardUrl_back']=strtr(C('IMG_URL').$data['iDcardUrl_back'],array('M00'=>'MOO/data'));
		$data['diplomaUrl_f']=strtr(C('IMG_URL').$data['diplomaUrl_f'],array('M00'=>'MOO/data'));
		$data['diplomaUrl_s']=strtr(C('IMG_URL').$data['diplomaUrl_s'],array('M00'=>'MOO/data'));
		$data['job_seniorityUrl_f']=strtr(C('IMG_URL').$data['job_seniorityUrl_f'],array('M00'=>'MOO/data'));
		$data['job_seniorityUrl_s']=strtr(C('IMG_URL').$data['job_seniorityUrl_s'],array('M00'=>'MOO/data'));		
		
		
		$data['u_iDcardUrl']=urlencode($data['iDcardUrl']);
		$data['u_iDcardUrl_back']=urlencode($data['iDcardUrl_back']);
		$data['u_diplomaUrl_f']=urlencode($data['diplomaUrl_f']);
		$data['u_diplomaUrl_s']=urlencode($data['diplomaUrl_s']);
		$data['u_job_seniorityUrl_f']=urlencode($data['job_seniorityUrl_f']);
		$data['u_job_seniorityUrl_s']=urlencode($data['job_seniorityUrl_s']);
		
		
		
		
		
		$data['province']=$region->where('id='.$data['province'])->getField('name');
		$data['city']=$region->where('id='.$data['city'])->getField('name');
		$data['district']=$region->where('id='.$data['district'])->getField('name');
		
		//取出所有的代理商
		$agents=M('com_dic_authentication_info','',$this->getDbLink(1))->field('id,name')->where('status =1')->select();
		$this->assign('agents',$agents);
		
		//取出模板里面的按钮
		$poList=$dbc->select();
		
		//echo $dbc->getLastSql();
		
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);

		$this->assign('data',$data);
		$this->display();
	
	}	
	//提交审批
	public function doAuth(){	
		if(IS_POST){
			actionLog(1,'健康代表审批');//操作日志记录
			$pi=I('post.');
			$dbc=M('h_assistant_apply_info','',$this->getDbLink(1));
			
			$dbc->startTrans();
			$result=$dbc->where('id='.$pi['id'])->save(array('state'=>$pi['state']));
			
			
			$uid=$dbc->where('id='.$pi['id'])->getField('ast_id');
			
			if($pi['state']==1){
				M('user_base_info','',$this->getDbLink(1))->where('user_id='.$pi['ast_id'])->save(array('user_type_extend'=>11));
				$result=M('h_assistant_belong_org','',$this->getDbLink(1))->add(array('user_id'=>$uid,'org_id'=>$pi['agent'],'auditor_id'=>$_SESSION['SESS_EmployeeInfo']['id'],'createDate'=>date('Y-m-d H:i:s',time())));				
			}elseif($pi['state']==2){
				M('user_base_info','',$this->getDbLink(1))->where('user_id='.$pi['ast_id'])->save(array('user_type_extend'=>0));				
				M('h_assistant_belong_doctor','',$this->getDbLink(1))->where(array('user_id'=>$uid))->delete();

				//echo M('h_assistant_belong_doctor','',$this->getDbLink(1))->getLastSql();exit; 
			}
			
			if($result!==false){
				$dbc->commit();
				$this->ajaxReturn('0');
			}
			else{
				$dbc->rollback();
				$this->ajaxReturn('2');
			}
		}
		else{
			$this->loginError('3');
		}	
	}	

}