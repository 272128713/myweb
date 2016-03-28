<?php
/**
 *  活动管理控制器
 * 
 */

namespace Home\Controller;
use Think\Controller;
class ActivityManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function ActivityManageList($id=0){	

		//dump($_SERVER['HTTP_HOST'] );
		//取得分页设置
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		
		$dbc=M('activity_info','',$this->getDbLink(1));		
		$belong_dbc=M('activity_belong_user','',$this->getDbLink(1));
		
		//搜索条件
		$condition=$pageList['condition'];

		//排序条件
		$order=$pageList['order'];

		$count=$dbc->count();
		
		//设置分页		
		$result=$dbc->field('title,user_type,limit_num,limit_num as had_limit_num,start_time,end_time,state,create_time,id')					
					->limit($pageList['start'],$pageList['limit'])
					->order($orderStr)->select();		
		//echo $dbc->getLastSql();
		foreach($result as &$v){					
			$v['had_limit_num']=$belong_dbc->where(array('activity_id'=>$v['id']))->count();
			$v['limit_num']=$v['limit_num']>0?$v['limit_num']:'无限制';
			$typeArr=array(
				1=>'普通用户',
				2=>'医生用户',
				3=>'所有用户'	
			);
			$stateArr=array(
				0=>'未开始',
				1=>'正在进行',
				2=>'已停止',		
			);
			$v['state']=$stateArr[$v['state']];
			$v['user_type']=$typeArr[$v['user_type']];
			
		}

		
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);		
	}
	
	public function ActivityManageEditItemOpt(){
		$result='ActivityManageEdit';
		return $result;
	}
	public function ActivityManageAddItemOpt(){
		$result='ActivityManageAdd';
		return $result;
	}	
	public function ActivityManageDelItemOpt(){
		$result='ActivityManageDel';
		return $result;
	}	
	
	public function ActivityManageEdit($id,$data){

		$dbc=M('activity_info','',$this->getDbLink(1));
		$result=$dbc->where('id='.$data)->find();		
		//取出模板里面的按钮
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		
		$this->assign('data',$result);
		$this->display('edit');
	}
	
	public function ActivityManageModify(){
		if(IS_POST){
			actionLog(1,'活动修改');//操作日志记录
			$data=$_POST;
			
			$dbc=M('activity_info','',$this->getDbLink(1));
			
			if($data['pic_url']!=''){
				$data['img_url']=$data['pic_url'];
			}			
			unset($data['pic_url']);
			unset($data['img']);
			
			
			
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
	public function ActivityManageAdd($id,$data){
	
		$dbc=M('activity_info','',$this->getDbLink(1));
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display('edit');
	}
	
	public function ActivityManageCreate(){
		if(IS_POST){
			actionLog(1,'新增活动');//操作日志记录
			$data=$_POST;
			
				
			$dbc=M('activity_info','',$this->getDbLink(1));

			$data['create_uid']=$_SESSION['SESS_EmployeeInfo']['id'];
			$data['create_time']=date('Y-m-d H:i:s',time());
			$data['img_url']=$data['pic_url'];
			unset($data['pic_url']);
			unset($data['img']);
			$result=$dbc->add($data);
				
			//echo $dbc->getLastSql();exit;
				
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
	public function ActivityManageDel($data){
		$dbc=M('activity_info','',$this->getDbLink(1));
		$t=$dbc->find($data);
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
		actionLog(1,'删除活动');//操作日志记录
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