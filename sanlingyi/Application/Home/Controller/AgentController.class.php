<?php
//代理商控制器
namespace Home\Controller;
use Think\Controller;
class AgentController extends TemplateController{
	public function index(){
		
	}	
	public function agentList(){
		
		//取得分页设置
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		$dbc=M('com_dic_authentication_info','',$this->getDbLink(1));
		
		$count=$dbc->count();		
		$result=$dbc->field('name,password,code,contract,tel,level,resume,status,id')
					->order('id desc')	
					->limit($pageList['start'],$pageList['limit'])
					->select();
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);
	}
	public function agentAdd($id){
		$dbc=M('com_dic_authentication_info','',$this->getDbLink(1));
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display('add');
	}
	public function agentCreate(){
		if(IS_POST){
			actionLog(1,'新增代理商');//操作日志记录
			$pi=I('post.');
			$dbc=M('com_dic_authentication_info','',$this->getDbLink(1));
			if($dbc->where("code='".$pi['code']."'")->count()<1){
				if($dbc->add($pi)>0){
					$this->ajaxReturn('0');
				}
				else{
					echo $dbc->getLastSql();
					$this->ajaxReturn('2');
				}
			}
			else{
				$this->ajaxReturn('1');
			}
		}
		else{
			$this->loginError('3');
		}
	}
	public function agentEditItemOpt(){
		return 'edit';
	}
	public function agentStatusItemOpt($optData){
		$result=$optData['status']>0?'true':'false';
		return $result;
	}
	public function agentDelItemOpt($optData){
		return 'del';
	}
	public function spreaderViewItemOpt($optData){
		return 'spreaderView';
	}	
	public function spreaderAddItemOpt($optData){
		return 'spreaderAdd';
	}	
	public function spreaderEditItemOpt($optData){
		return 'spreaderEdit';
	}	
	public function spreaderStatusItemOpt($optData){
		$result=$optData['status']>0?'true':'false';
		return $result;
	}	
	public function spreaderDelItemOpt($optData){
		return 'spreaderDel';
	}		
	public function agentEdit($id,$data){
		$dbc=M('com_dic_authentication_info','',$this->getDbLink(1));
		$d=$dbc->find($data);
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->assign('data',$d);
		$this->display('edit');
	}
	public function agentModify(){
		if(IS_POST){
			actionLog(1,'修改代理商');//操作日志记录
			$pi=I('post.');
			$dbc=M('com_dic_authentication_info','',$this->getDbLink(1));
			$dbc->startTrans();
			if($dbc->where('code="'.$pi['code'].'" and id<>'.$pi['id'])->count()<1){
				$result=$dbc->save($pi);
				//echo $dbc->getLastSql();exit;
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
				$this->ajaxReturn('1');
			}
		}
		else{
			$this->loginError('3');
		}
	}
	public function agentStatus($data){
		$dbc=M('com_dic_authentication_info','',$this->getDbLink(1));
		$t=$dbc->find($data);
		$status=$t['status']>0?0:1;
		$dbc->where('id='.$data)->setField('status',$status);
		return true;
	}	
	public function agentDel($data){
		actionLog(1,'删除代理商');//操作日志记录
		$dbc=M('com_dic_authentication_info','',$this->getDbLink(1));
		$t=$dbc->find($data);
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
		if($result!==false){
			if($result!==false){
				$dbc->commit();
				$success=true;
			}
			else{
				$dbc->rollback();
			}
		}
		else{
			$dbc->rollback();
		}
		return $success;
	}
	//查看推广人员
	public function spreaderView($id,$data){
		$dbc=M('module_info');
		$mData=$dbc->find($data);
		
		$d['moduleID']=$id;
		$d['objectID']=$data;
		
		$this->getPlaceModule($id, 0, $data);
		$page='spreaderView';
		
		
		$btData=$dbc->where('en="moduleListLimit"')->find();
		
		$d['listLimit']=$mData['list_limit'];
		$d['btName']=$btData['module_name'];
		$d['btID']=$btData['module_id'];
		$param=array(
				'moduleID'	=> $id,
				'objectID'	=> $data
		);
		$this->assign('param',$param);
		$this->tplList($id,$data);
		$this->assign('listController','moduleListList');
		
		
		$this->assign('data',$d);
		$this->display($page);
	}	
	public function moduleListList($moduleID,$objectID){
	
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[$moduleID];
	
		$dbc=M('spreader_info','',$this->getDbLink(1));
		$data=$dbc->field('name,code,tel,status,id')
		->where('agent_id='.$objectID)
		->select();
	
		$this->tplListItem($moduleID,$data);
	}	
	//添加推广人员
	public function spreaderAdd($id,$data){
		$dbc=M('spreader_info','',$this->getDbLink(1));
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		
		$this->assign('agent_id',$data);
		$this->display();
	}	
	public function spreaderCreate(){
		if(IS_POST){
			actionLog(1,'添加推广人员');//操作日志记录
			$pi=I('post.');
			$dbc=M('spreader_info','',$this->getDbLink(1));			
			if($dbc->where("code='".$pi['code']."' and agent_id=".$pi['agent_id'])->count()<1){
				if($dbc->add($pi)>0){
					$this->ajaxReturn('0');
				}
				else{
					//echo $dbc->getLastSql();
					$this->ajaxReturn('2');
				}
			}
			else{
				$this->ajaxReturn('1');
			}
		}
		else{
			$this->loginError('3');
		}
	}		
	//编辑推广人员
	public function spreaderEdit($id,$data){ 
		$dbc=M('spreader_info','',$this->getDbLink(1));
		$data=$dbc->find($data);
		$this->assign('data',$data);
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display();
	}	
	//更改推广人员状态
	public function spreaderStatus($data){
		$dbc=M('spreader_info','',$this->getDbLink(1));
		$t=$dbc->find($data);
		$status=$t['status']>0?0:1;
		$dbc->where('id='.$data)->setField('status',$status);
		return true;
	}	
	//删除推广人员
	public function spreaderDel($data){
		actionLog(1,'删除推广人员');//操作日志记录
		$dbc=M('spreader_info','',$this->getDbLink(1));
		$t=$dbc->find($data);
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
		if($result!==false){
			if($result!==false){
				$dbc->commit();
				$success=true;
			}
			else{
				$dbc->rollback();
			}
		}
		else{
			$dbc->rollback();
		}
		return $success;
	}	
	//更新推广人员数据
	public function spreaderModify(){
		if(IS_POST){
			actionLog(1,'修改推广人员');//操作日志记录
			$pi=I('post.');
			$dbc=M('spreader_info','',$this->getDbLink(1));
			$dbc->startTrans();
			if($dbc->where('code="'.$pi['code'].'" and id<>'.$pi['id'])->count()<1){
				$result=$dbc->save($pi);
				//echo $dbc->getLastSql();exit;
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
				$this->ajaxReturn('1');
			}
		}
		else{
			$this->loginError('3');
		}
	}	
}