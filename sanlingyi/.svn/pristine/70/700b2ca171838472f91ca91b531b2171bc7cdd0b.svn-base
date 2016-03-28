<?php
//广告审批控制器
namespace Home\Controller;
use Think\Controller;
class AgentDrawApplyController extends TemplateController{
	public function index(){
		
	}	
	public function AgentDrawApplyList(){
		
		//取得分页设置
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		$condition=$pageList['condition'];
		//dump($condition);
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$end=$arr[2]?$arr[2]:date('Y-m-d',time());
				$start=$arr[1]?$arr[1]:0;
				$start=$start.' 00:00:00';
				$end=$end.' 23:59:59';
				$s['_string'] = 'create_time >="'.$start.'" and create_time < "'.$end.'"';
		
				if($arr[3] != -1){
					$s['agent_draw_money_apply.status']=$arr[3];
				}
			}
		}
		
		
		
		$dbc=M('agent_draw_money_apply','',$this->getDbLink(1));		
		$count=$dbc->where($s)->count();		
		$result=$dbc->field('com_dic_authentication_info.name,money,create_time,agent_draw_money_apply.account_type,agent_draw_money_apply.account_name,agent_draw_money_apply.account_num,end_time,employee_id,agent_draw_money_apply.resume,agent_draw_money_apply.status,agent_draw_money_apply.id')
					->join('LEFT JOIN com_dic_authentication_info ON agent_draw_money_apply.uid=com_dic_authentication_info.id')
					->where($s)
					->order('create_time desc')
					->select();
		
		foreach ($result as &$val){
			$val['employee_id']=M('employee_info')->where('id='.$val['employee_id'])->getField('name');
			$statusArr=array(
				0=>'未处理',
				2=>'成功处理',
				1=>'未成功处理',
			);
			$val['status']=$statusArr[$val['status']];
		}
		
		//echo $dbc->getLastSql();
		
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);
	}
	//设置列表上的操作
	public function agentDrawAuthItemOpt($optData){
		return 'agentDrawAuth';
	}
	public function agentDrawAuth($id,$data){
		
		
		$dbc=M('agent_draw_money_apply','',$this->getDbLink(1));
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		
		
		$data=$dbc->where('id='.$data)->find();		
		$data['agent_name']=M('com_dic_authentication_info','',$this->getDbLink(1))->where('id='.$data['uid'])->getField('name');

		//dump($data);
		$this->assign('data',$data);
		$this->display('agentdrawauth');
	
	}	
	//提交审批
	public function doAgentDrawAuth(){	
		if(IS_POST){
			actionLog(1,'处理代理商提现');//操作日志记录
			$pi=I('post.');
			$dbc=M('agent_draw_money_apply','',$this->getDbLink(1));
			$dbc->startTrans();
			$result=$dbc->where('id='.$pi['id'])->save(array('status'=>$pi['status'],'employee_id'=>$_SESSION['SESS_EmployeeInfo']['id'],'end_time'=>date('Y-m-d H:i:s',time()),'resume'=>$pi['resume']));
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
	
	
	//按条件导出提现申请列表
	public function getExcel(){
	
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
	
		$dbc=M('agent_draw_money_apply','',$this->getDbLink(1));
	
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$end=$arr[2]?$arr[2]:date('Y-m-d',time());
				$start=$arr[1]?$arr[1]:0;
				$start=$start.' 00:00:00';
				$end=$end.' 23:59:59';
				$s['_string'] = 'create_time >="'.$start.'" and create_time < "'.$end.'"';
	
				if($arr[3] != -1){
					$s['agent_draw_money_apply.status']=$arr[3];
				}
			}
		}			
		$result=$dbc->field('com_dic_authentication_info.name,money,create_time,agent_draw_money_apply.account_type,agent_draw_money_apply.account_name,agent_draw_money_apply.account_num,end_time,employee_id,agent_draw_money_apply.resume,agent_draw_money_apply.status')
		->join('LEFT JOIN com_dic_authentication_info ON agent_draw_money_apply.uid=com_dic_authentication_info.id')
		->where($s)
		->order('create_time desc')
		->select();
	
// 		dump($s);
// 		echo $dbc->getLastSql();
// 		dump($result);exit;
		
		$success_count=0;
		$error_count=0;
		$no_count=0;
	
		$success_money=0;
		$error_money=0;
		$no_money=0;
	
		foreach ($result as &$data){
				
			if($data['status']==2){
				$success_count++;
				$success_money+=$data['money'];
			}elseif($data['status']==1){
				$error_count++;
				$error_money+=$data['money'];
			}else{
				$no_count++;
				$no_money+=$data['money'];
			}
				
			$statusArr=array(
					0=>'未处理',
					2=>'成功处理',
					1=>'未成功处理',
			);
			$data['status']=$statusArr[$data['status']];
				
			$data['employee_id']=M('employee_info')->where('id='.$data['employee_id'])->getField('name');
				
		}
	
	
		// 输出Excel文件头，可把user.csv换成你要的文件名
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="代理商提现申请.csv"');
		header('Cache-Control: max-age=0');
	
		// 打开PHP文件句柄，php://output 表示直接输出到浏览器
		$fp = fopen('php://output', 'a');
	
		// 输出Excel列名信息
		$head = array('申请人','金额','申请时间','账户类型','账户名','卡号','处理时间','处理人','备注','状态');
		foreach ($head as $i => $v) {
			// CSV的Excel支持GBK编码，一定要转换，否则乱码
			$head[$i] = iconv('utf-8', 'gbk', $v);
		}
	
		// 将数据通过fputcsv写到文件句柄
		fputcsv($fp, $head);
	
		foreach ($result as $key => $val) {
			foreach($val as $k=>$v){
				$new[$k] = iconv('utf-8', 'gbk', $v);
			}
			fputcsv($fp, $new);
		}
	
		$count=count($result);
	
	
		$null=array('','','','','','','','');
		//统计信息
		fputcsv($fp,$null);
		fputcsv($fp,$null);
		fputcsv($fp, array(iconv('utf-8', 'gbk', '共'.$count.'条数据')));
		fputcsv($fp, array(iconv('utf-8', 'gbk', '未处理'.$no_count.'条，金额'.$no_money.'元')));
		fputcsv($fp, array(iconv('utf-8', 'gbk', '未成功处理'.$error_count.'条，金额'.$error_money.'元')));
		fputcsv($fp, array(iconv('utf-8', 'gbk', '成功处理'.$success_count.'条，金额'.$success_money.'元')));
	
	
	
	}	

}