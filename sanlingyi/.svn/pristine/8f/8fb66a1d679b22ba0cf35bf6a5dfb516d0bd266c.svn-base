<?php
namespace Home\Controller;
use Think\Controller;
class DbConfigController extends TemplateController{
	public function dbConfigList(){
		$dbc=M('db_info');
		$result=$dbc->field('position,en,name,position as no,type,host,port,user,passwd,server,status,id')
				->order('position')->limit($pageList['start'],$pageList['limit'])->select();
		$this->tplListItem(session('SESS_optModuleID'),$result);
	}
	
	public function dbEditItemOpt($optData){
		return 'edit';
	}
	
	public function dbStatusItemOpt($optData){
		$result=$optData['status']>0?'true':'false';
		return $result;
	}
	
	public function dbDelItemOpt($optData){
		return 'del';
	}
	
	public function dbAdd($id){
		$this->getPlaceModule($id, 0);
		$db=F('Db');
		$this->assign('type',$db['type']);
		$dbc=M('db_info');
		$t=$dbc->order('position')->select();
		$this->assign('poList',$t);
		$this->display('add');
	}
	
	public function dbCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('db_info');
			$dbc->startTrans();
			$success=false;
			if($dbc->where('name="'.$pi['name'].'" or (en="'.$pi['en'].'" and host="'.$pi['host'].'"')->count()<1){
				$pi['id']=$dbc->max('id')+1;
				if($dbc->where('position>'.$pi['position'])->setInc('position')!==false){
					$pi['position']=$pi['position']+1;
					if($dbc->add($pi)>0){
						$success=true;						
					}
				}
				if($success){
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
	
	public function dbEdit($id,$data){
		$db=F('Db');
		$this->assign('type',$db['type']);
		$dbc=M('db_info');
		$t=$dbc->where('id<>'.$data)->order('position')->select();
		$this->assign('poList',$t);
		$t=$dbc->find($data);
		$this->assign('data',$t);
		$this->getPlaceModule($id,0);
		$this->display('edit');
	}
	
	public function dbModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('db_info');
			$dbc->startTrans();
			if($dbc->where('(db_name="'.$pi['db_name'].'" or (db_en="'.$pi['db_en'].'" and db_host="'.$pi['db_host'].'") and db_id<>'.$pi['db_id'])->count()<1){
				$t=$dbc->find($pi['id']);
				$opt=D('radioedit');
				$pi['position']=$opt->singlePosition('db_info',$t['position'],$pi['position']);
				$result=$dbc->save($pi);
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
	
	public function dbStatus($data){
		$dbc=M('db_info');
		$t=$dbc->find($data);
		$status=$t['status']>0?0:1;
		$dbc->where('id='.$data)->setField('status',$status);
		return true;
	}
	
	public function dbDel($data){
		$dbc=M('db_info');
		$t=$dbc->find($data);
		if($t['position']>1){
			$dbc->startTrans();
			$success=false;
			$result=$dbc->delete($data);
			if($result!==false){
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
	}
	
	public function dbConfigure(){
		$dbc=M('db_info');
		$t=$dbc->where('status>0')->order('position')->select();
		foreach ($t as $k=>$v){
			$t[$k]['link']=$v['type'].'://'.$v['user'].':'.$v['passwd'].'@'.$v['host'].':'.$v['port'].'/'.$v['en'];
			$h[$k]=$v['id'].','.$v['server'];
		}
		$td=$this->formatConfig($t,'db_info');
		$d=array(
			'id' 		=> 0,
			'name'		=> '管理系统主库',
			'en'		=> C('DB_NAME'),
			'type'		=> C('DB_TYPE'),
			'host'		=> C('DB_HOST'),
			'port'		=> C('DB_PORT'),
			'user'		=> C('DB_USER'),
			'passwd'	=> C('DB_PWD'),
			'position'	=> 0,
			'status'	=> 1,
			'link'		=> ''
		);
		array_unshift($td,$d);
		$dbt=M('db_type');
		$s=$dbt->where('status>0')->order('position')->select();
		$sd=$this->formatConfig($s,'db_type');
		$data=array(
			'db'=>$td,
			'type'=>$sd
		);
		F('Db',$data);		
		$s=$this->Post(3,'configureUpdate.php',$h);
		return true;
	}
}
?>