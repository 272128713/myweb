<?php
/**
 * k服务管理控制器
 * 
 */

namespace Home\Controller;
use Think\Controller;
class KmanageController extends TemplateController{
	public function index(){
		
	}	

	public function KmanageList($id=0){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		$dbc=M('k_requisition','',$this->getDbLink(1));
		
		$examine_dbc=M('k_examine','',$this->getDbLink(1));
		$service_dbc=M('com_sic_service_info','',$this->getDbLink(1));
		
		//分地区条件
		$reg=$this->getRegionCatch(session('SESS_optModuleID'));
		
		//dump($reg);
		
		if(is_array($reg)){
			$r=implode(',',$reg);
			$s['com_dic_doctor_info.district']  = array('in',$r);
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
				$s['com_dic_doctor_info.province']  =$id;
			}
			elseif($rt['level']==2){
				$s['com_dic_doctor_info.city']  =$id;
			}
			else{
				$s['com_dic_doctor_info.district']  =$id;
			}
		}
		
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$s['com_dic_user_info.name']=array('like','%'.$arr[1].'%');
				$s['com_dic_user_info.mobile']=array('like','%'.$arr[2].'%');
				
				if($arr[3] != -1){
					if($arr[3]==1){//未审批
						$s['k_requisition.state']=0;
					}else{
						$s['k_examine.state']=$arr[3];
					}					
				}
				
			}else{//简单搜索
				
			}		
		}	
// 		dump($condition);
// 		dump($s);	
		$count=$dbc->where($s)
					->join('LEFT JOIN com_dic_user_info ON k_requisition.docId = com_dic_user_info.id')
				   ->join('LEFT JOIN com_dic_doctor_info ON k_requisition.docId = com_dic_doctor_info.id')
				   ->join('LEFT JOIN k_examine ON k_requisition.docId = k_examine.docId')
				   ->count();		
		//echo $dbc->getLastSql();		
		$result=$dbc->field('com_dic_user_info.name,com_dic_user_info.mobile,com_dic_user_info.sex,user_base_info.hospital,dim_recollection_code.name as recollection,k_requisition.createDate,k_requisition.id as ktype,staff_doctor_check.staff_name,k_requisition.id as auth_state,k_requisition.id,com_dic_user_info.id as uid')
					->join('LEFT JOIN com_dic_user_info ON k_requisition.docId = com_dic_user_info.id')
					->join('LEFT JOIN com_dic_doctor_info ON k_requisition.docId = com_dic_doctor_info.id')
					->join('LEFT JOIN dim_recollection_code ON com_dic_doctor_info.section_id = dim_recollection_code.recollection_id')					
					->join('LEFT JOIN k_examine ON k_requisition.docId = k_examine.docId')
					->join('LEFT JOIN staff_doctor_check ON k_requisition.docId = staff_doctor_check.doctor_id')
					->join('LEFT JOIN user_base_info ON k_requisition.docId = user_base_info.user_id')
					->where($s)
					->limit($pageList['start'],$pageList['limit'])
					->order('k_requisition.createDate desc')
					->select();		
		//echo $dbc->getLastSql();
		
		foreach($result as &$v){			
			$sexArr=array(
					0=>'未选择',
					1=>'男',
					2=>'女'
			);
			

			//$hArr=getHospitalArr($v['uid']);
			$hArr=getHospitalArr_bak($v['hospital']);
			$v['hospital']=$hArr['hospital'];				
			$v['sex']=$sexArr[$v['sex']];
			
			
			$examine_data=$examine_dbc->where('docId='.$v['uid'])->find();
			
			if($examine_data){
				$stateArr=array(
						0=>'未申请',
						1=>'申请未审批',
						2=>'审批未通过',
						3=>'审批通过需排队',
						4=>'停用',
						5=>'审批通过'
				);				
				$v['auth_state']=$stateArr[$examine_data['state']];
				$v['ktype']=$service_dbc->where('id='.$examine_data['type'])->getField('name');
			}else{
				$v['auth_state']=$v['state']<1?'未审批':''; 
				$v['ktype']='暂无';
			}
		}
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);		
	}
		
	public function kmanageAuthItemOpt(){
		$result='kmanageAuth';
		return $result;
	}
	//审批
	public function kmanageAuth($id,$data){
		
		$k_req=M('k_requisition','',$this->getDbLink(1));		
		$k_exa=M('k_examine','',$this->getDbLink(1));

		$rInfo=$k_req->find($data);
		$docId=$rInfo['docId'];
		$k_exa_data=$k_exa->where('docId='.$docId)->find();
		
		if($k_exa_data){
			$da['state']=$k_exa_data['state'];
			$da['type']=$k_exa_data['type'];
			$da['check_name']=M('employee_info')->where('id='.$k_exa_data['tasterId'])->getField('name');
		}else{
			$da['check_name']=$_SESSION['SESS_EmployeeInfo']['name'];
		}

		//dump($da);
		$ans=str_split($rInfo['answer'],2);
		$this->assign('ans',$ans);
		
		$rens=explode(',',$rInfo['expertId']);
		$str='';		
		foreach($rens as $v){
			$name=M('com_dic_user_info','',$this->getDbLink(1))->where('id='.$v)->getField('name');
			$auth=M('com_dic_doctor_info','',$this->getDbLink(1))->where('id='.$v)->getField('authentication');
			$stateArr=array(
					0=>'未认证',
					1=>'完全认证',
					2=>'待认证',
					3=>'未通过',
					11=>'工牌认证',
			);
			
			//$au=$auth?'已认证':'未认证';
			$str.=$name.'['.$stateArr[$auth].']'.'，';
		}
		$da['docs']=rtrim($str,'，');
		$da['user_id']=$docId;
		$da['ks']=M('com_sic_service_info','',$this->getDbLink(1))->field('id,name')->select();		
		//取出模板里面的按钮
		$poList=$k_req->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		
		
		
		
		
		$this->assign('data',$da);				
		$this->display('auth');
	}	
	public function kmanageAuthModify(){
		if(IS_POST){	
		actionLog(1,'K服务审批');//操作日志记录
		$k_exa = M('k_examine','',$this->getDbLink(1));
		$docId=I('post.user_id');
		$type=I('post.type');
		$state=I('post.state');
	
		$k_exa_id=$k_exa->where('docId='.$docId)->getField('id');
		
		$k_exa->startTrans();
		
		$data=array(
				'state'=>$state,				
				'type'=>$type,
				'examine_time'=>date('Y-m-d H:i:s',time()),		
				'tasterId'=>$_SESSION['SESS_EmployeeInfo']['id'],
		);
		if($k_exa_id){
			$result=$k_exa->where('docId='.$docId)->save($data);
		}else{
			$new_data=array(
					'docId'=>$docId,
					'tasterId'=>$_SESSION['SESS_EmployeeInfo']['id'],
					'createDate'=>date('Y-m-d H:i:s',time()),				
			);	
			$save_data=array_merge($data,$new_data);
			$result=$k_exa->add($save_data);			
		}				
		//更新用户版本号
		M('user_version_info','',$this->getDbLink(1))->where('user_id='.$docId)->setInc('base_ver');
		//$sql = "UPDATE user_base_info SET k=(SELECT type FROM k_examine WHERE docId=$docId) WHERE user_id=$docId AND k=5";
		if($state==5){
			M('user_base_info','',$this->getDbLink(1))->where('user_id='.$docId)->save(array('k'=>$type));
		}else{
			M('user_base_info','',$this->getDbLink(1))->where('user_id='.$docId)->save(array('k'=>$state));
		}
		
		M('k_requisition','',$this->getDbLink(1))->where('docId='.$docId)->save(array('state'=>1));
		
		if($result!==false){
			$k_exa->commit();
			$this->ajaxReturn('0');
		}else{
			$k_exa->rollback();
			$this->ajaxReturn('1');
		}
	}else{
			$this->loginError('3');
		}	
	}				
}