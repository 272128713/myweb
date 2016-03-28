<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class RoleController extends TemplateController{
	public function index(){
		
	}
	
	public function roleList(){
		$dbc=M('role_info');
		$result=$dbc->field('position,role_name,resume,status,role_id as id')->order('position')->select();
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);
	}
	
	public function roleAdd(){
		$dbc=M('role_info');
		$poList=$dbc->where('status<9')->order('position')->select();
		$this->getPlaceModule('roleAdd', 0);
		$this->assign('poList',$poList);
		$this->display('add');
	}
	
	public function roleCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('role_info');
			$dbc->startTrans();
			if($dbc->where('role_name="'.$pi['role_name'].'"')->count()<1){
				$data['role_id']=$this->createNewID();
				$data['role_name']=$pi['role_name'];
				$data['position']=$pi['position']+1;
				$data['resume']=$pi['resume'];
				$result=$dbc->where('position>'.$pi['position'])->setInc('position');
				if($result!==false){
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
	
	public function roleEdit($id,$data){
		$dbc=M('role_info');
		$t=$dbc->find($data);
		$poList=$dbc->where('status<9 and role_id<>'.$data)->order('position')->select();
		$this->getPlaceModule($id, 0);
		$this->assign('poList',$poList);
		$this->assign('data',$t);
		$this->display('edit');
	}
	
	public function roleModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('role_info');
			$dbc->startTrans();
			if($dbc->where('role_name="'.$pi['role_name'].'" and role_id<>'.$pi['roleID'])->count()<1){
				$data['role_name']=$pi['role_name'];
				$data['resume']=$pi['resume'];
				$t=$dbc->find($pi['roleID']);
				if($t['position']!=$pi['position']+1){
					$dbc->where('position>'.$t['position'])->setDec('position');
					if($t['position']>$pi['position']){
						$dbc->where('position>'.$pi['position'])->setInc('position');
						$data['position']=$pi['position']+1;
					}
					else{
						$dbc->where('position>='.$pi['position'])->setInc('position');
						$data['position']=$pi['position'];
					}
				}
				$result=$dbc->where('role_id='.$pi['roleID'])->save($data);
				
				
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
	
	public function roleEditItemOpt($optData){
		$result=$optData['status']<9?'edit':'HIDE';
		return $result;
	}
	
	public function roleStatusItemOpt($optData){
		if($optData['status']<9){
			$result=$optData['status']>0?'true':'false';
		}
		else{
			$result='HIDE';
		}
		return $result;
	}
	
	public function roleDelItemOpt($optData){
		$result=$optData['status']<9?'del':'HIDE';
		return $result;
	}
	
	public function rolePowerItemOpt($optData){
		$result=$optData['status']<9?'power':'HIDE';
		return $result;
	}
	
	public function roleRegionItemOpt($optData){
		$result=$optData['status']<9?'power':'HIDE';
		return $result;
	}
	
	public function roleRemindItemOpt($optData){
		$result=$optData['status']<9?'power':'HIDE';
		return $result;
	}	
	
	public function roleStatus($data){
		$dbc=M('role_info');
		$t=$dbc->find($data);
		$status=$t['status']>0?0:1;
		$dbc->where('role_id='.$data)->setField('status',$status);
		return true;
	}
	
	public function roleDel($data){
		$dbc=M('role_info');
		$t=$dbc->find($data);
		$dbc->startTrans();
		$success=false;
		$result=$dbc->delete($data);
		if($result>0){
			$result=$dbc->where('position>'.$t['position'])->setDec('position');
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
	
	public function rolePower($id,$data){
		$this->getPlaceModule($id, 0, $data, 1);
		$param=array(
			'moduleID'=>$id,
			'objectID'=>$data
		);
		$this->assign('param',$param);
		$this->display('Template/singleTree');
	}
	
	public function rolePowerSave($data,$parameter){
		$dbc=M('power_info');
		$dbc->startTrans();
		$success=true;
		$res=$dbc->where('role_id='.$data)->delete();
		if($res!==false){
			$t=explode(',',$parameter);
			foreach ($t as $k=>$v){
				$d['id']=$this->createNewID();
				$d['role_id']=$data;
				$d['module_id']=$v;
				$res=$dbc->add($d);
				if($res<1){
					$success=false;
				}
			}	
		}
		if($success){
			$dbc->commit();
			$this->clearRole($data);
		}
		else{
			$dbc->rollback();
		}
		return $success;
	}
	
	public function roleRegion($id,$data){
		$this->getPlaceModule($id,0,$data,1);
		$param=array(
			'moduleID'=>$id,
			'objectID'=>$data
		);		
		$this->assign('pList',$this->getRegion(1));
		$this->assign('id',$data);
		$this->assign('param',$param);
		$this->tplList($id,$data);
		$this->assign('listController','roleRegionList');
		$this->display('moduleRegion');
	}
	
	public function roleRegionList($moduleID,$objectID,$id=0){
		if($id>0){
			$dbc=M('module_role_region');
			$t=$dbc->field('province,city,id')->where('role_id='.$objectID.' and module_id='.$id)->select();
			foreach($t as &$v){
				$v['province']=$this->getRoleRegion($v);
			}			
			$this->tplListItem($moduleID, $t);
		}
	}
	
	public function roleRegionSave(){
		if(IS_POST){
			$pi=I('get.');
			$dbc=M('module_role_region');
			$dbc->startTrans();
			$success=true;
			if($pi['province']==9){
				$dbc->where('role_id='.$pi['role_id'].' and module_id='.$pi['module_id'])->delete();
			}
			elseif($pi['city']==9){
					$dbc->where('role_id='.$pi['role_id'].' and module_id='.$pi['module_id'].' and province='.$pi['province'])->delete();
			}
			else{
				if($dbc->where('role_id='.$pi['role_id'].' and module_id='.$pi['module_id'].' and province=9')->count()>0){
					$success=false;
				}
				else{
					if($dbc->where('role_id='.$pi['role_id'].' and module_id='.$pi['module_id'].' and province='.$pi['province'].' and (city=9 or city='.$pi['city'].')')->count()>0){
						$success=false;
					}
				}
			}
			if($success){
				$pi['id']=$this->createNewID();
				$result=$dbc->add($pi);
				if($result>0){
					$this->clearRole($pi['role_id']);		
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
	
	public function getRoleRegion($optData){
		if($optData['province']==9){
			$result='不受地区限制';
		}
		else{
			$p=$this->getRegion(4,$optData['province']);
			$p=$p['name'];
			if($optData['city']==9){
				$result=$p.'全部城市';
			}
			else{
				$c=$this->getRegion(4,$optData['city']);
				$c=$c['name'];
				$result=$p.$c;
			}
		}
		return $result;
	}
	
	public function roleRegionDelItemOpt(){
		return 'del';
	}
	
	public function roleRegionDel($data){
		$dbc=M('module_role_region');
		$m_r=$dbc->where('id='.$data)->getField('role_id');		
		$emps=M('employee_info')->where('role_id='.$m_r)->getField(id,true);
		foreach($emps as $v){
			S($v,null);//清除文件缓存数据
		}
		$result=$dbc->delete($data);
		
		return $result;
	}
	public function roleRemind($id,$data){
		$this->getPlaceModule($id, 0, $data, 1);
		$param=array(
				'moduleID'=>$id,
				'objectID'=>$data,
		);
		$this->assign('param',$param);		
		$this->display('Template/singleTree');
	}
	
	public function roleRemindSave($data,$parameter){
		$dbc=M('role_remind');
		$dbc->startTrans();
		$success=true;
		$res=$dbc->where('role_id='.$data)->delete();
		if($res!==false){
			$t=explode(',',$parameter);
			if($parameter != 'NONE'){
			foreach ($t as $k=>$v){
				$d['id']=$this->createNewID();
				$d['role_id']=$data;
				$d['remind_id']=$v;
				$res=$dbc->add($d);
				if($res<1){
					$success=false;
				}
			}
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
	//更新缓存信息	
public function clearRole($role_id){
	$employees=M('employee_info')->where('role_id='.$role_id)->getField('id',true);
	foreach ($employees as $val){
		S($val,null);	//清理用户权限缓存
	}
} 
//更新所有用户缓存
public function clearAllRole(){
	
	$dir = RUNTIME_PATH.'Temp';//要删除的目录
	$this->showDir($dir); 
	
	$this->ajaxReturn('0');
}
function showDir( $filedir ) {
	//打开目录
	$dir = dir($filedir);
	//列出目录中的文件
	while (($file = $dir->read())!==false)
	//while(($file = readdir($dir)) !== false)
	{
		if(is_dir($filedir."/".$file) AND ($file!=".") AND ($file!="..")) {
			//showDir($filedir."/".$file);
		} else {
			$file= $filedir."/".$file;
			if(is_readable($file)){
				@unlink($file);
			}
		}
	}
	$dir->close();

}	

}
?>