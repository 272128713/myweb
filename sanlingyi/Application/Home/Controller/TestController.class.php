<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class TestController extends CommonController{
	public function testCatch(){
		$data=F('Body');
		$dbc=M('base_body');
		$dbc->startTrans();
		$success=true;
		foreach ($data as $k=>$v){
			$d['id']=$this->createNewID();
			$d['name']=$v['name'];
			$d['level']=$v['level']-1;
			if($d['level']==1){
				$d['parentId']=0;
				$parentId=$d['id'];
			}
			else{
				$d['parentId']=$parentId;
			}
			$d['position']=$k+1;
			$d['status']=1;
			if($dbc->add($d)<1){
				$success=false;
				break;				
			}
		}
		if($success){
			//$dbc->commit();
		}
		else{
			$dbc->rollback();
		}
		var_dump($success);
	}
	
	public function testLoad(){
		$opt=D('Common');
		$db=$this->getDB();
		$dbc=M($table,'',$db['db'][1]['link']);
		$t=$opt->db($link)->table('tb_dept_position')->where('position>69')->order('position')->select();
		//F('Body',$t);
	}
}
?>