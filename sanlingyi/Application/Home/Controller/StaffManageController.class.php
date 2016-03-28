<?php
/**
 * 员工审核列表管理控制器
 * 
 */

namespace Home\Controller;
use Think\Controller;
class StaffManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function StaffManageList(){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		$dbc=M('staff_doctor_check','',$this->getDbLink(1));

		$condition=$pageList['condition'];
		if($condition != ''){			
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
					$end=$arr[2]?$arr[2]:date('Y-m-d',time());
					$start=$arr[1]?$arr[1]:0;
					$start=$start.' 00:00:00';
					$end=$end.' 23:59:59';
					$s['_string'] = 'check_time >"'.$start.'" and check_time < "'.$end.'"';
					if($arr[3] != -1){
						$s['com_dic_doctor_info.authentication']=$arr[3];
					}					
					$data=array('start'=>$arr[1],'end'=>$arr[2]);						
					$this->assign('date',$data);				
			}else{//简单搜索
				$s['mobile']=$condition;
			}			
		}
		$count=$dbc->join('LEFT JOIN com_dic_doctor_info ON staff_doctor_check.doctor_id = com_dic_doctor_info.id')
					->where($s)->count();
		$result=$dbc->field('doctor_name,com_dic_doctor_info.hospital,doctor_phone,com_dic_doctor_info.authentication,check_time,staff_name,check_name,doctor_id as id')
					->join('LEFT JOIN com_dic_doctor_info ON staff_doctor_check.doctor_id = com_dic_doctor_info.id')
					->where($s)
					->limit($pageList['start'],$pageList['limit'])
					->order('check_time desc')
					->select();
		
		foreach($result as &$v){
			$res=getHospitalArr($v['id']);
			$v['hospital']=$res['hospital'];
			
			$stateArr=array(
					0=>'未认证',
					1=>'完全认证',
					2=>'待认证',
					3=>'未通过',
					11=>'工牌认证',
			);
			
			$v['authentication']=$stateArr[$v['authentication']];
		}
				
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}
	//按条件导出员工审核列表
	public function getExcel(){
		
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
					$end=$arr[2]?$arr[2]:date('Y-m-d',time());
					$start=$arr[1]?$arr[1]:0;
					$start=$start.' 00:00:00';
					$end=$end.' 23:59:59';
					$s['_string'] = 'check_time >"'.$start.'" and check_time < "'.$end.'"';
					
					if($arr[3] != -1){
						$s['user_base_info.authentication']=$arr[3];
					}
					
					$data=array('start'=>$arr[1],'end'=>$arr[2]);						
					$this->assign('date',$data);				
			}else{//简单搜索
				$s['mobile']=$condition;
			}
		}
		
		$row=M('staff_doctor_check','',$this->getDbLink(1))
				->field('staff_name,doctor_name,com_dic_user_info.id,user_base_info.reg_date,user_base_info.authentication,check_time,doctor_phone')
				->join('LEFT JOIN com_dic_user_info ON staff_doctor_check.doctor_id=com_dic_user_info.id')
				->join('LEFT JOIN user_base_info ON staff_doctor_check.doctor_id=user_base_info.user_id')
				->where($s)				
				->order('check_time desc')
				->select();
		//echo M('staff_doctor_check','',$this->getDbLink(1))->getLastSql();
		//dump($row);exit;
		foreach($row as $k=>$v){
			$hospitalArr=getHospitalArr($v['id']);
			$new_arr[$k]=$v;
			$new_arr[$k]['id']=$hospitalArr['hospital'];
			
			$v['hospital']=$res['hospital'];
				
			$stateArr=array(
					0=>'未认证',
					1=>'完全认证',
					2=>'待认证',
					3=>'未通过',
					11=>'工牌认证',
			);
				
			$new_arr[$k]['authentication']=$stateArr[$v['authentication']];

		}
		
		
		
		
		// 输出Excel文件头，可把user.csv换成你要的文件名
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="staff_doctor_check_list.csv"');
		header('Cache-Control: max-age=0');

		// 打开PHP文件句柄，php://output 表示直接输出到浏览器
		$fp = fopen('php://output', 'a');
		
		// 输出Excel列名信息
		$head = array('推广人','医生姓名','医院名称','注册时间','认证类型','审核时间','手机号');
		foreach ($head as $i => $v) {
			// CSV的Excel支持GBK编码，一定要转换，否则乱码
			$head[$i] = iconv('utf-8', 'gbk', $v);
		}
		
		// 将数据通过fputcsv写到文件句柄
		fputcsv($fp, $head);			
		foreach ($new_arr as $key => $val) {			
			//var_dump($val);			
				foreach($val as $k=>$v){
					$new[$k] = iconv('utf-8', 'gbk', $v);
				}				
			fputcsv($fp, $new);
		}	
	}

	public function StaffManageChangeItemOpt(){
		$result='staffManageChange';
		return $result;
	}
	//修改发展人
	public function staffManageChange($id,$data){
		
		$model=M('staff_doctor_check','',$this->getDbLink(1));
		$da['staff_name']=$model->where('doctor_id='.$data)->getField('staff_name');
		$da['doctor_id']=$data;
		$this->assign('data',$da);
		
		$agent_model=M('com_dic_authentication_info','',$this->getDbLink(1));
		$agents=$agent_model->field('id,name')->where('status=1')->select();
		$this->assign('agents',$agents);
		
		$poList=$model->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display('change');
	}	
	public function staffManageChangeModify(){
		if(IS_POST){

			$dbc=M('staff_doctor_check','',$this->getDbLink(1));

			$doctor_id=I('post.doctor_id');
			$staff_name=I('post.staff_name');			
			
			$dbc->startTrans();
			$d_info=$dbc->where('doctor_id='.$doctor_id)->find();
			actionLog(1,$_SESSION['SESS_EmployeeInfo']['name'].'修改'.$d_info['doctor_phone'].'的认证人('.$d_info['staff_name'].'->'.$staff_name.')');//操作日志记录
			
			$result=$dbc->where('doctor_id='.$doctor_id)->save(array('staff_name'=>$staff_name));
			
			if(I('post.agent')!=-1 && I('post.spreader')!=-1){
				$agent_code=M('com_dic_authentication_info','',$this->getDbLink(1))->where('id='.I('post.agent'))->getField('code');
				$spreader_code=M('spreader_info','',$this->getDbLink(1))->where('id='.I('post.spreader'))->getField('code');
				//修改医生的代理商
				M('com_dic_doctor_info','',$this->getDbLink(1))->where('id='.$doctor_id)->save(array('agent'=>$agent_code,'spreader'=>$spreader_code));				
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
	
	
	
}