<?php
/**
 * 管理
 * 
 */

namespace Home\Controller;
use Think\Controller;
class ArticleManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function ArticleManageList(){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		//dump($_SESSION['SESS_EmployeeInfo']);
		
		
		
		$dbc=M('com_top_article','',$this->getDbLink(1));
		$c_dbc=M('article_belong_class','',$this->getDbLink(1));
// 		if($_SESSION['SESS_EmployeeInfo']['role_id']!=1){
// 			$s['_string'].="com_top_article.employee_id = ".$_SESSION['SESS_EmployeeInfo']['id'];
// 		}
			
		
		$condition=$pageList['condition'];
		if($condition != ''){			
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
					$end=$arr[2]?$arr[2]:date('Y-m-d',time());
					$start=$arr[1]?$arr[1]:0;
					$start=$start.' 00:00:00';
					$end=$end.' 23:59:59';
					$s['_string']='1=1 ';
					$s['_string'] .= 'and create_time >"'.$start.'" and create_time < "'.$end.'"';	
					if($arr[3]!=''){
						$s['_string'].=" and com_top_article.title LIKE '%".trim($arr[3])."%'";
					}
					if($arr[4]!=-1){
						$s['_string'].=" and com_top_article.view_type = ".$arr[4];
					}	
					if($arr[5]!=-1){
						$s['_string'].=" and com_top_article.state = ".$arr[5];
					}												
			}			
		}
		

		//dump($s);
		$count=$dbc->where($s)
					->join('LEFT JOIN user_base_info ON com_top_article.doctor_id = user_base_info.user_id')					
					->count();
		$result=$dbc->field('title,title as class,view_type,article_from,examine_time,up_num,down_num,eval_num,user_base_info.user_name as doctor_name,employee_id,com_top_article.state,id')
					->join('LEFT JOIN user_base_info ON com_top_article.doctor_id = user_base_info.user_id')
					->where($s)
					->limit($pageList['start'],$pageList['limit'])
					->order('sort asc,create_time desc')
					->select();
	//echo $dbc->getLastSql();			
		foreach($result as &$v){
			$v['employee_id']=M('employee_info')->where('id='.$v['employee_id'])->getField('name');				
			$stateArr=array(
					0=>'未发布',
					1=>'已发布',
					2=>'已删除',
					3=>'待审核',
			);
			$v['state']=$stateArr[$v['state']];		
			$viewArr=array(
					1=>'一张大图',
					2=>'一张小图',
					3=>'三张小图',
					4=>'无图',
			);
			$v['view_type']=$viewArr[$v['view_type']];			
			$r=$c_dbc->where('article_id='.$v['id'])
					->join('LEFT JOIN article_class ON article_belong_class.class_id=article_class.id')
					->getField('class_name',true);
			$v['class']=implode(',', $r);
		}	
		
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}
	public function ArticleManageEditItemOpt(){
		$result='edit';
		return $result;
	}
	public function ArticleManageDelItemOpt(){
		$result='del';
		return $result;
	}	
	public function ArticleManageAdd($id){
		$dbc=M('com_top_article','',$this->getDbLink(1));
		$poList=$dbc->order('sort')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		
		$class=M('article_class','',$this->getDbLink(1))->where('state=1')->order('sort asc')->select();
		$this->assign('class',$class);
		$this->display('edit');
		
		
	}
	public function ArticleManageCreate(){
		if(IS_POST){
			$pi=I('post.');
			//dump($pi);exit;
			$dbc=M('com_top_article','',$this->getDbLink(1));
			
			$data=array(
				'title'=>$pi['title'],
				'content'=>$_POST['content'],
				'pic'=>	serialize($pi['pic_url']),
				'view_type'=>$pi['view_type'],
				'article_from'=>$pi['article_from'],
				'create_time'=>date('Y-m-d H:i:m',time()),
				'examine_time'=>date('Y-m-d H:i:m',time()),
				'state'=>$pi['state'],
				'sort'=>$pi['sort'],
				'employee_id'=>$_SESSION['SESS_EmployeeInfo']['id'],
			);
			if($pi['state']==0){
				$data['examine_time']=$pi['start_time'];
			}
			
			
			$res=$dbc->add($data);
			//echo $dbc->getLastSql();
			$b_dbc=M('article_belong_class','',$this->getDbLink(1));
			foreach ($pi['class'] as $val){
				$b_dbc->add(array('article_id'=>$res,'class_id'=>$val));
			}
			
			if($res>0){
				$this->ajaxReturn('0');
			}
			else{
				$this->ajaxReturn('2');
			}
		}
		else{
			$this->loginError('3');
		}
	}
		
	public function ArticleManageEdit($id,$data){
		$dbc=M('com_top_article','',$this->getDbLink(1));
		$d=$dbc->where('id='.$data)->find();
		
		if($d['employee_id']==$_SESSION['SESS_EmployeeInfo']['id']){
			$poList=$dbc->order('sort')->select();
			$this->getPlaceModule($id,0);
			$this->assign('poList',$poList);			
		}

		
		
		$d['pic_url']=unserialize($d['pic']); 
		//dump($d);
		$this->assign('data',$d);
		$class=M('article_class','',$this->getDbLink(1))->where('state=1')->order('sort asc')->select();				
		$in_class=M('article_belong_class','',$this->getDbLink(1))->where('article_id='.$data)->getField('class_id',true);
				
		foreach ($class as &$val){
			if(in_array($val[id], $in_class)){
				$val['f']=1;
			}
		}		
		
		$this->assign('in_class',$in_class);
		$this->assign('class',$class);
		$this->display('edit');
	}
	public function ArticleManageModify(){
		if(IS_POST){
			$pi=I('post.');   //我的注释
			
			//dump($_POST);exit;
			
			$dbc=M('com_top_article','',$this->getDbLink(1));	
			
			
			$a_info=$dbc->where('id='.$pi['id'])->find();
			
			$data=array(
					'title'=>$pi['title'],
					'content'=>$_POST['content'],
					'pic'=>	serialize($pi['pic_url']),
					'view_type'=>$pi['view_type'],
					'article_from'=>$pi['article_from'],					
					'state'=>$pi['state'],
					'sort'=>$pi['sort'],
					'employee_id'=>$_SESSION['SESS_EmployeeInfo']['id'],
			);	
// 			if($a_info['state']==3){
// 				$a=array('examine_time'=>date('Y-m-d H:i:m',time()));
// 				$data=array_merge($data,$a);
// 			}
		
				$data['examine_time']=$pi['start_time'];
					
			
				$result=$dbc->where('id='.$pi['id'])->save($data);
				
				//echo $dbc->getLastSql();
				
				$b_dbc=M('article_belong_class','',$this->getDbLink(1));
				$b_dbc->where('article_id='.$pi['id'])->delete();
				foreach ($pi['class'] as $val){
					$b_dbc->add(array('article_id'=>$pi['id'],'class_id'=>$val));
				}
				
				if($result!==false){
					$this->ajaxReturn('0');
				}
				else{
					$this->ajaxReturn('2');
				}
		}
		else{
			$this->loginError('3');
		}
	}	
	public function ArticleManageDel($data){
		$dbc=M('com_top_article','',$this->getDbLink(1));
		$result=$dbc->where('id='.$data)->delete();
		return $result;
	} 
	
	
	
}