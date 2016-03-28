<?php
/**
 *  活动管理控制器
 * 
 */

namespace Home\Controller;
use Think\Controller;
class QaManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function QaManageList($id=0){
		
		//取得分页设置
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		
		$dbc=M('user_consult','',$this->getDbLink(1));
		$employee_dbc=M('employee_info');
		
		//搜索条件
		$condition=$pageList['condition'];

		//排序条件
		$order=$pageList['order'];

		$count=$dbc->count();
		
		//设置分页		
		$result=$dbc->field('com_dic_user_info.name,user_consult.create_time,user_consult.title,content,employee_id as status,is_read,employee_id,answer_content,answer_time,user_consult.id')
					->join('LEFT JOIN com_dic_user_info ON user_consult.user_id = com_dic_user_info.id')
					->limit($pageList['start'],$pageList['limit'])
					->order('user_consult.id desc')->select();		
		//echo $dbc->getLastSql();
		foreach($result as &$v){					
			$v['content']=msubstr($v['content'],0,30);
			$v['answer_content']=msubstr($v['answer_content'],0,30);
			$v['status']=empty($v['status'])?'未处理':'已处理';
			$v['is_read']=$v['is_read']>0?'已读':'未读';
			$v['employee_id']= $employee_dbc->where('id='.$v['employee_id'])->getField('name');			
		}

		
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);		
	}
	
	public function QaManageAnswerItemOpt(){
		$result='QaManageAnswer';
		return $result;
	}
	
	
	public function QaManageAnswer($id,$data){

		$dbc=M('user_consult','',$this->getDbLink(1));
		$result=$dbc->where('id='.$data)->find();	

		if(empty($result['employee_id'])){
			//取出模板里面的按钮
			$poList=$dbc->order('position')->select();
			$this->getPlaceModule($id,0);
			$this->assign('poList',$poList);			
		}

		
		$this->assign('data',$result);
		$this->display('edit');
	}
	
	public function QaManageAnswerModify(){
		if(IS_POST){
			actionLog(1,'回复留言');//操作日志记录
			$data=$_POST;
			
			$dbc=M('user_consult','',$this->getDbLink(1));
			

			$data['employee_id']=$_SESSION['SESS_EmployeeInfo']['id'];
			$data['answer_time']=date('Y-m-d H:i:s',time());
			
			$result=$dbc->where('id='.$data['id'])->save($data);
			
			//echo $dbc->getLastSql();
			
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