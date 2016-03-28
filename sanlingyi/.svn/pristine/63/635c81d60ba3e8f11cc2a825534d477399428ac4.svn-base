<?php
/**
 * APP问题反馈控制器
 * 
 */

namespace Home\Controller;
use Think\Controller;
class AppFeedbackController extends TemplateController{
	public function index(){
		
	}	
	
	public function AppFeedbackList(){
		
		$dbc=M('user_feedback','',$this->getDbLink(1));
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		
		if($pageList['condition'] != ''){
			$s['phone']=$pageList['condition'];			
		}				
		$count=$dbc->where($s)->count();
		$result=$dbc->field('user_id,phone,content,submitdate,feedback_id as id')
					->where($s)
					->limit($pageList['start'],$pageList['limit'])
					->order('submitdate desc')
					->select();
		foreach($result as &$v){
			$v['content']=msubstr($v['content'],0,50,'UTF-8',true);
		}		
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);		
	}
	public function AppFeedbackViewItemOpt(){
		$result='view';
		return $result;
	}	
	public function AppFeedbackDelItemOpt(){
		$result='del';
		return $result;
	}	
	//查看问题反馈
	public function AppFeedbackView($id,$data){
		$da=M('user_feedback','',$this->getDbLink(1))->where('feedback_id='.$data)->find();
		$this->assign('data',$da);				
		$this->display('view');
	}
	//删除问题反馈
	public function AppFeedbackDel($data){
		$dbc=M('user_feedback','',$this->getDbLink(1));
		$dbc->startTrans();
		$success=false;
		$result=$dbc->where('feedback_id='.$data)->delete();
		//echo $dbc->getLastSql();exit;
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