<?php
namespace Home\Controller;
use Think\Controller;
use Think\Db;
class ModuleController extends TemplateController{
	public function index(){
		$this->display('Template/blank');
	}
	
	public function moduleChild($data){
		$this->moduleAdd($data);
	}
	 
	public function moduleAdd($parentID=0)
	{
		$dbc=M('module_info');
		if($parentID>0){
			$data=$dbc->find($parentID);
			$level=$data['level']+1;
			$pName=$data['module_name'];
		}
		else{
			$level=1;
			$pName='本身就是一级模块';
		}
		$listData=$dbc->where('parentId='.$parentID)->order('position')->select();
		$data['pid']=$parentID;
		$data['level']=$level;
		$data['pName']=$pName;
		$this->getPlaceModule('moduleAdd', 0, $id);
		$this->assign('poList',$listData);
		$this->assign('data',$data);
		$this->display('add');
	}
	
	public function moduleCreate(){
		if(IS_POST){
			$dbc=M('module_info');
			$pi=I('post.');
			//检验重名：在相同parentID且同level的条件下判断name,或者整表检查en
			$sql="en='".$pi['en']."' or (module_name='".$pi['module_name']."' and parentId=".$pi['parentId'].")";
			if($dbc->where($sql)->count()<1){
				$data['module_id']=$this->createNewID();
				$data['module_name']=$pi['module_name'];
				$data['en']=$pi['en'];
				$data['level']=$pi['level'];
				$data['parentId']=$pi['parentId'];
				$opt=D('radioedit');
				$data['position']=$opt->getPosition('module_info', $pi['position'], $pi['parentId']);
				$dbc->startTrans();
				$dbc->where('position>='.$data['position'])->setInc('position');
				if($pi['level']==2){
					$data['module_style']=$pi['module_style'];
					$data['area_limit']=$pi['area_limit'];
				}
				elseif($pi['level']>2){
					$data['show_place']=$pi['show_place'];
					$data['operate_type']=$pi['operate_type'];
					if($pi['operate_type']==1){
						$data['window_width']=$pi['window_width'];
						$data['window_height']=$pi['window_height'];
					}
					elseif($pi['operate_type']==2){
						$data['isConfirm']=$pi['isConfirm'];
						$data['reload_type']=$pi['reload_type'];
						if($pi['isConfirm']>0){
							$data['confirm_text']=$pi['confirm_text'];
						}
					}
				}
				$result=$dbc->add($data);
				if($result !== false){
					$dbc->commit();
					$this->resetPower();
					$this->ajaxReturn('0');
				}
				else{
					$dbc->rollback();
					$this->ajaxReturn('2');
				}
			}
			else{
				$this->ajaxReturn('1');  //重名
			}			
		}
		else{
			$this->loginError('3');
		}
	}
	public function moduleEdit($id){
		$dbc=M('module_info');
		$data=$dbc->find($id);
		if($data){
			if($data['level']==2){
				$parentList=$dbc->where('level=1')->order('position')->select();
			}
			else{
				$parentList=$dbc->where('module_id='.$data['parentId'])->select();
			}
			//取出可选的位置信息
			$positionList=$dbc->where('parentId='.$data['parentId'].' and module_id<>'.$id)->order('position')->select();
			//取出当前信息的位置信息，用于控制页面中select控件的显示
			$data['op']=$dbc->field('position')->where('parentId='.$data['parentId'].' and position<'.$data['position'])->max('position');
			$this->getPlaceModule(session('SESS_optModuleEN'), 0, $id);
			$this->assign('data',$data);
			$this->assign('mpList',$parentList);
			$this->assign('poList',$positionList);
			$this->display('edit');
		}
		else{
			$this->display('Index/blank');
		}
	}
	public function moduleModify(){
		if(IS_POST){
			$dbc=M('module_info');
			$pi=I('post.');
			$sql="module_id<>".$pi['mid']." and (en='".$pi['en']."' or (module_name='".$pi['module_name']."' and parentId=".$pi['parentId']." and level=".$pi['level']."))";
			if($dbc->where($sql)->count()<1){
				$dbc->startTrans();
				$data['module_name']=	$pi['module_name'];
				$data['en']			=	$pi['en'];
				$data['parentId']	=	$pi['parentId'];
				$opt=D('radioedit');
				$result=true;
				if($opt->movePosition('module_info',$pi['mid'],$pi['parentId'],$pi['position'])){
					if($pi['level']==2){
						$data['module_style']=$pi['module_style'];
						$data['area_limit']=$pi['area_limit'];						
					}
					elseif($pi['level']>2){
						$data['show_place']=$pi['show_place'];
						$data['operate_type']=$pi['operate_type'];
						switch($pi['operate_type']){
							case 1:
								$data['window_width']=$pi['window_width'];
								$data['window_height']=$pi['window_height'];
								break;
							case 2:
								$data['isConfirm']=$pi['isConfirm'];
								$data['reload_type']=$pi['reload_type'];
								if($pi['isConfirm']>0){
									$data['confirm_text']=$pi['confirm_text'];
								}
								break;
						}
						$result=$this->moduleReset($pi['mid']);
					}
					if($result){
						$result=$dbc->where('module_id='.$pi['mid'])->save($data);
						if($result!==false){
							$dbc->commit();
							$this->resetPower();
							$this->ajaxReturn('0');
						}
						else{
							$dbc->rollback();
							$this->ajaxReturn('2');
						}
					}
					else{
						$dbc->rollback();
						$this->ajaxReturn('2');
					}
				}
				else{
					$dbc->rollback();
					$this->ajaxReturn('2');
				}
			}
			else{
				$this->ajaxReturn('1');//重名
			}
		}
		else{
			$this->loginError('3');
		}
	}
	
	public function moduleReset($id){
		$dbc=M('module_info');
		$data['show_place']=3;
		$data['operate_type']=9;
		$data['reload_type']=9;
		$data['isConfirm']=9;
		$data['confirm_text']='';
		$data['window_width']=0;
		$data['window_height']=0;
		$result=$dbc->where('module_id ='.$id)->save($data);
		if($result!==false){
			$result=true;
		}
		return $result;
	}
	
	public function moduleStatus($data)
	{
		$dbc=M('module_info');
		$dbc->startTrans();
		$opt=D('radioedit');
		if($opt->changeStatus('module_info',$data)){
			$dbc->commit();
			$result=true;
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	
	public function moduleDel($data)
	{
		$dbc=M('power_info');
		$dbc->startTrans();
		$opt=D('radioedit');
		$result=$opt->getDel('module_info',$data);
		if($result['status']){
			$res=$dbc->where('module_id in ('.implode(',', $result['optData']).')')->delete();
			if($res!==false){
				$dbc->commit();
				$result=true;
			}
			else{
				$dbc->rollback();
				$result=false;
			}
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;	
	}
	
	public function moduleBatch($id){
		$this->getPlaceModule($id, 0, '', 1);
		$this->assign('url','moduleBatchTree');
		$this->display('Template/singleTree');
	}
	
	public function moduleBatchOpen($data='',$parameter){
		$dbc=M('module_info');
		$dbc->startTrans();
		$result=$dbc->where('module_id in ('.$parameter.')')->setField('status',1);
		if($result!==false){
			$dbc->commit();
			$result=true;
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	
	public function moduleBatchClose($data='',$parameter){
		$dbc=M('module_info');
		$dbc->startTrans();
		$result=$dbc->where('module_id in ('.$parameter.')')->setField('status',0);
		if($result!==false){
			$dbc->commit();
			$result=true;
		}
		else{
			$dbc->rollback();
			$result=false;
		}
		return $result;
	}
	
	public function moduleBatchDel($data='',$parameter){
		$dbc=M('module_info');
		$dbc->startTrans();
		$success=true;
		$opt=D('radiodetail');
		$data=explode(',',$parameter);
		foreach($data as $k=>$v){
			$result=$opt->getDel('module_info',$v);
			if(!$result){
				$success=false;
				break;
			}
		}
		if($success){
			$dbc->commit();
		}
		else{
			$dbc->rollback();
		}
		return $success;
	}
	
	public function moduleList($id,$data){
		$dbc=M('module_info');
		$mData=$dbc->find($data);			
		
		$d['moduleID']=$id;
		$d['objectID']=$data;

		$this->getPlaceModule($id, 0, $data);
		$page='moduleList';
		
		
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
		
		$dbc=M('module_item');
		$data=$dbc->field('position,item_name,item_width,isOrder,isCenter,id,status')
				  ->where('module_id='.$objectID)
				  ->order('position')->select();
		
		$this->tplListItem($moduleID,$data);
	}
	
	public function moduleListLimit($data,$parameter){
		$dbc=M('module_info');
		$result=$dbc->where('module_id='.$data)->setField('list_limit',$parameter);
		if($result!==false){
			$result=true;
		}
		return $result;
	}
		
	public function moduleListItemData($moduleID){
		$dbc=M('module_item');
		$data=$dbc->where('module_id='.$moduleID)->order('position')->select();
		return $data;
	}
	
	public function moduleListDbSave(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('module_info');
			$result=$dbc->save($pi);
			if($result>0){
				$this->ajaxReturn('0');
			}
			else{
				$this->ajaxReturn('1');
			}
		}
		else{
			$this->loginError('3');
		}
	}
	public function moduleListAdd($id,$data){
		$dbc=M('module_info');
		$mData=$dbc->find($data);
		$d=F('Db');
		$d=$d['db'];
		$d=$d[$mData['db_id']];
		$dbData['dbName']=$d['name'];
		$dbData['dbEN']=$d['en'];
		$dbData['tbName']=$mData['db_table'];
		$dbData['moduleID']=$data;
		$t=$dbc->find($id);
		$dbData['buttonID']=$t['parentId'];
		$opt=D('Common');
		$result=$opt->getFieldList($mData['db_table'],$mData['db_id']);
		$dbi=M('module_item');
		$iData=$dbi->where('module_id='.$data)->order('position')->getField('item_field',true);
		$i=0;
		foreach ($result as $k=>$v){
			if(!in_array($v['name'], $iData)){
				$fData[$i]['name']=$v['name'];
				$i++;
			}
		}
		$this->assign('fieldList',$fData);
		$poList=$dbi->where('module_id='.$data)->order('position')->select();
		$this->assign('poList',$poList);
		$this->assign('data',$dbData);
		$this->getPlaceModule($id, 0, $data);
		$this->display();
	}
	
	public function moduleListCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('module_item');
			if($dbc->where('module_id='.$pi['moduleID'].' and item_name="'.$pi['item_name'].'"')->count()<1){
				$dbc->startTrans();
				$data['id']=$this->createNewID();
				$data['module_id']=$pi['moduleID'];
				$data['item_name']=$pi['item_name'];
				$data['item_width']=$pi['item_width'];
				$data['isOrder']=$pi['isOrder'];
				$data['isCenter']=$pi['isCenter'];
				if(!empty($pi['item_field'])){
					$data['item_field']=$pi['item_field'];
				}
				$data['operate']=$pi['operate'];			
					
				$dbc->where('position>'.$pi['position'].' and module_id='.$pi['moduleID'])->setInc('position');
				$data['position']=$pi['position']+1;

				$result=$dbc->add($data);
				if($result>0){
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
	
	public function moduleListReset($data){
		$dbc=M('module_info');
		$d['db_id']=0;
		$d['db_table']='';
		$success=false;
		$dbc->startTrans();
		$result=$dbc->where('module_id='.$data)->save($d);
		if($result!==false){
			$dbi=M('module_item');
			$result=$dbi->where('module_id='.$data)->delete();
			if($result!==false){
				$success=true;
			}
		}
		if($success){
			$dbc->commit();
		}
		else{
			$dbc->rollback();
		}
		return $success;
	}
	
	public function moduleListEditItemOpt(){
		$result='edit';
		return $result;
	}
	
	public function moduleListStatusItemOpt($optData){
		$result=$optData['status']>0?'true':'false';
		return $result;
	}
	
	public function moduleListDelItemOpt(){
		$result='del';
		return $result;
	}
	
	public function moduleListDel($data){
		$dbc=M('module_item');
		$dbc->startTrans();
		$t=$dbc->find($data);
		$res=$dbc->where('module_id='.$t['module_id'].' and position>'.$t['position'])->setDec('position');
		$result=false;
		if($res!==false){
			$res=$dbc->delete($id);
			if($res>0){
				$dbc->commit();
				$result=true;
			}
		}
		return $result;
	}
	
	public function moduleListEdit($id,$data){		
		$dbc=M('module_item');
		$d=$dbc->find($data);
		$dbm=M('module_info');
		$t=$dbm->find($d['module_id']);
		$a=F('Db');
		$a=$a['db'][$t['db_id']];
		$d['dbName']=$a['name'].'['.$a['en'].']';
		$d['tbName']=$t['db_table'];
		$d['moduleID']=$d['module_id'];
		$s=$dbm->find($id);
		$d['buttonID']=$s['parentId'];
		$d['itemID']=$data;
		$opt=D('Common');
		$result=$opt->getFieldList($t['db_table'],$t['db_id']);
		$iData=$dbc->where('module_id='.$d['module_id'])->order('position')->getField('item_field',true);
		$i=0;
		foreach ($result as $k=>$v){
			if(!in_array($v['name'], $iData)){
				$fData[$i]['name']=$v['name'];
				$i++;
			}
		}
		$this->assign('fieldList',$fData);
		$poList=$dbc->where('module_id='.$d['module_id'].' and id<>'.$data)->order('position')->select();
		$this->assign('poList',$poList);
		$this->assign('data',$d);
		$this->getPlaceModule($id, 0, $data);
		$this->display();
	}
	
	public function moduleListModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('module_item');
			if($dbc->where('module_id='.$pi['moduleID'].' and item_name="'.$pi['item_name'].'" and id<>'.$pi['itemID'])->count()<1){
				$dbc->startTrans();
				$data['item_name']=$pi['item_name'];
				$data['item_width']=$pi['item_width'];
				$data['isOrder']=$pi['isOrder'];
				$data['isCenter']=$pi['isCenter'];
				$data['item_field']=empty($pi['item_field'])?'':$pi['item_field'];
				$data['operate']=$pi['operate'];
				$t=$dbc->find($pi['itemID']);
				if($t['position']!=$pi['position']+1){
					$dbc->where('module_id='.$pi['moduleID'].' and position>'.$t['position'])->setDec('position');
					if($t['position']>$pi['position']){
						$dbc->where('module_id='.$pi['moduleID'].' and position>'.$pi['position'])->setInc('position');
						$data['position']=$pi['position']+1;
					}
					else{
						$dbc->where('module_id='.$pi['moduleID'].' and position>='.$pi['position'])->setInc('position');
						$data['position']=$pi['position'];
					}
				}
				$result=$dbc->where('id='.$pi['itemID'])->save($data);
				if($result>0){
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
	public function moduleListStatus($data){
		$dbc=M('module_item');
		$t=$dbc->find($data);
		$status=$t['status']>0?0:1;
		$result=$dbc->where('id='.$data)->setField('status',$status);
		$result=$result>0?true:false;
		return $result;
	}
}
?>