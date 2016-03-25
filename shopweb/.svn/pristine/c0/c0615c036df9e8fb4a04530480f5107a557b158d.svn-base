<?php
/**
 *  系统操作日志记录控制器
 * 
 */

namespace Home\Controller;
use Think\Controller;
class ActionLogController extends TemplateController{
	public function index(){
		
	}
	
	public function ActionLogList($id=0){
		
		//取得分页设置
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		$pageList=$pageList['parameter']; 
		
		
		
		$dbc=M('action_log','',$this->getDbLink(1));	
		$employee_dbc=M('employee_info');
		
		//搜索条件
		$condition=$pageList['condition'];



		$count=$dbc->count();
		
		//设置分页		
		$result=$dbc->field('employee_id,action_log.create_time,level,info,remark')					
					->limit($pageList['start'],$pageList['limit'])
					->order('id desc')->select();		
		//echo $dbc->getLastSql();
		foreach($result as &$v){					
			$levelArr=array(
				0=>'普通',
				1=>'<b style="color:red;">危险</b>',
			);
			$v['level']=$levelArr[$v['level']];
			$v['employee_id']=$employee_dbc->where('id='.$v['employee_id'])->getField('name');
		}
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);		
	}
		
				
}