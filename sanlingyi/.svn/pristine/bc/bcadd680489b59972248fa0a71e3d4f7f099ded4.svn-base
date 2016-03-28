<?php
/**
 * 员工审核列表管理控制器
 * 
 */

namespace Home\Controller;
use Think\Controller;
class FirstAidManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function FirstAidManageList(){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		$dbc=M('hd_test','',$this->getDbLink(3));
		$condition=$pageList['condition'];
		if($condition != ''){			
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
					$end=$arr[2]?$arr[2]:date('Y-m-d',time());
					$start=$arr[1]?$arr[1]:0;
					$start=$start.' 00:00:00';
					$end=$end.' 23:59:59';
					$s['_string'] = 'time >"'.$start.'" and time < "'.$end.'"';		
					if($arr[3] != ''){
						$s['phone']=array('LIKE',"%$arr[3]%");
					}	
			}		
		}
		//dump($s);
		$count=$dbc->where($s)->count();
		$result=$dbc->field('phone,sex,age,time,id')					
					->where($s)
					->limit($pageList['start'],$pageList['limit'])
					->order('time desc')
					->select();
		
		foreach($result as &$v){			
			$sexArr=array(
					0=>'未选择',
					1=>'男',
					2=>'女',
			);			
			$v['sex']=$sexArr[$v['sex']];
			$url=U('FirstAidManage/showDetial?id='.$v['id']);
			$v['id']="<a target='_blank' href='$url'>查看问卷</a>";
		}
				
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}
	public function showDetialItemOpt(){
		$result='showDetial';
		return $result;
	}
	public function showDetial(){
		//$url=urldecode($_GET['url']);
		$id=intval($_GET['id']);
		$dbc=M('hd_test','',$this->getDbLink(3));
		$da=$dbc->where('id='.$id)->find();
		$da['q6']=explode(',', trim($da['q6']));
		$da['q11']=explode(',', trim($da['q11']));
		
		//dump($da);
		
		$this->assign('data',$da);
		$this->display('auth');
	}
	
}