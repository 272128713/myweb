<?php
namespace Home\Model;
use Think\Model;
use Think\Db;
class CommonModel extends Model{
	public function getDB($id=0){
		$db=F('Db');
		if($id>0){
			$db=$db['db'][$id]['link'];	
		}
		return $db;
	}
		
	public function getPrimaryKey($table,$dbID=0){
		$option['COLUMN_KEY']='PRI';
		$option['TABLE_NAME']=$table;
		$db=$this->getDB();
		$option['TABLE_SCHEMA']=$dbID>0?$db['db'][$dbID]['en']:C('DB_NAME');		
		$dbc=M('information_schema.columns','',$db['db'][$dbID]['link']);
		$data=$dbc->field('COLUMN_NAME')->where($option)->find();
		$idKey=$data['COLUMN_NAME'];
		return 	$idKey;
	}
	
	
	public function getFieldList($tbName,$dbID=0){
		$db=$this->getDB();
		$option['TABLE_NAME']=$tbName;
		$option['TABLE_SCHEMA']=$db['db'][$dbID]['en'];
		$dbc=M('information_schema.columns','',$db['db'][$dbID]['link']);
		$data=$dbc->field(array('COLUMN_NAME'=>'name'))->where($option)->order('TABLE_NAME')->select();
		return $data;
	}
}
?>