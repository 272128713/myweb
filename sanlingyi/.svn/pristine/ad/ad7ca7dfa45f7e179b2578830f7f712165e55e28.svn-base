<?php
namespace Home\Model;
use Think\Model;
use Think\Db;
class TreedataModel extends CommonModel{
	public function getModule(){
		$dbc=M('module_info');
		$data=$dbc->field(array('module_id'=>'id','module_name'=>'name','parentId'))->order('position')->select();
		return $data;
	}
	
	public function getDepartment(){
		$dbc=M('department_info');
		$data=$dbc->field(array('department_id'=>'id','department_name'=>'name','parentId'))->order('position')->select();
		return $data;
	}
	
	public function getRegion(){
		$dbc=M('base_region');
		$data=$dbc->field('id,name,parentId')->order('position')->select();
		return $data;
	}
}