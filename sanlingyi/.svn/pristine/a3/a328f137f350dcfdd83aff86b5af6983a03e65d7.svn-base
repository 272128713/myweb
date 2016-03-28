<?php
/**
 * 医苑天地管理
 * 
 */

namespace Home\Controller;
use Think\Controller;
class DocArticleManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function DocArticleManageList(){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		$dbc=M('bbs_doc_article_info','',$this->getDbLink(1));
		
// 		if($_SESSION['SESS_EmployeeInfo']['role_id']!=1){
// 			$s['_string'].="bbs_doc_article_info.employee_id = ".$_SESSION['SESS_EmployeeInfo']['id'];
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
					$s['_string'] .= 'and createDate >"'.$start.'" and createDate < "'.$end.'"';	
					if($arr[3]!=''){
						$s['_string'].=" and bbs_doc_article_info.title LIKE '%".trim($arr[3])."%'";
					}
					if($arr[4]!=-1){
						$s['_string'].=" and bbs_doc_article_info.columns= ".$arr[4];
					}	
					if($arr[5]!=-1){
						$s['_string'].=" and bbs_doc_article_info.report_flag = ".$arr[5];
					}												
			}			
		}
		

		//dump($s);
		$count=$dbc->where($s)					
					->count();
		//echo $dbc->getLastSql();
		$result=$dbc->field('title,columns,dim_recollection_code.name,doc_id,report_flag,createDate,recommend_num,sys_flag,bbs_doc_article_info.id')
					->join('LEFT JOIN dim_recollection_code ON dim_recollection_code.recollection_id=bbs_doc_article_info.recollection_id')
					->where($s)
					->limit($pageList['start'],$pageList['limit'])
					->order('createDate desc')
					->select();
	//echo $dbc->getLastSql();			
		foreach($result as &$v){			
			if($v['sys_flag']==1){//系统发表
				$v['doc_id']=M('employee_info')->where('id='.$v['doc_id'])->getField('name');
				$v['sys_flag']='系统发表';
			}else{//医生发表
				$v['doc_id']=M('user_base_info','',$this->getDbLink(1))->where('user_id='.$v['doc_id'])->getField('user_name');
				$v['sys_flag']='医生发表';
			}
			$report_flagteArr=array(
					0=>'未投稿',
					1=>'待审批',
					2=>'已通过',
					3=>'未通过',
			);
			$v['report_flag']=$report_flagteArr[$v['report_flag']];		
			
			$classArr=array(
					1=>'学术交流',
					2=>'有问必答',
					3=>'病例分析',
					4=>'行业观察',
					20=>'热点话题',
			);
			$v['columns']=$classArr[$v['columns']];
		}				
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}
	public function DocArticleManageEditItemOpt(){
		$result='edit';
		return $result;
	}
	public function DocArticleManageDelItemOpt(){
		$result='del';
		return $result;
	}	
	public function DocArticleManageAuthItemOpt(){
		$result='auth';
		return $result;
	}	
	public function DocArticleManageAdd($id){
		$dbc=M('bbs_doc_article_info','',$this->getDbLink(1));
		$poList=$dbc->order('sort')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);

		$this->display('edit');
		
		
	}
	public function DocArticleManageCreate(){
		if(IS_POST){
			$pi=I('post.');		
			$dbc=M('bbs_doc_article_info','',$this->getDbLink(1));
			
			$data=array(
				'title'=>$pi['title'],
				'intro'=>$_POST['intro'],
				'content'=>$_POST['content'],
				'columns'=>20,
				'recollection_id'=>0,
				'createDate'=>date('Y-m-d H:i:m',time()),
				'sys_flag'=>1,
				'doc_id'=>$_SESSION['SESS_EmployeeInfo']['id'],
			);
			
			$res=$dbc->add($data);
			//echo $dbc->getLastSql();		
			$img_dbc=M('bbs_doc_article_images','',$this->getDbLink(1));
			foreach ($pi['pic_url'] as $v){
				$arr=array(
						'article_id'=>$res,
						'source_image_url'=>$v,
						'createDate'=>date('Y-m-d H:i:m',time())
				);
				$img_dbc->add($arr);
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
		
	public function DocArticleManageEdit($id,$data){
		$dbc=M('bbs_doc_article_info','',$this->getDbLink(1));
		$d=$dbc->where('id='.$data)->find();
		
	
		$poList=$dbc->order('sort')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);

		$d['imgs']=M('bbs_doc_article_images','',$this->getDbLink(1))->where('article_id='.$data)->getField('source_image_url',true);
		
		$this->assign('data',$d);		
		$this->display('edit');
	}
	public function DocArticleManageModify(){
		if(IS_POST){
			$pi=I('post.');   //我的注释
			
			//dump($_POST);exit;
			
			$dbc=M('bbs_doc_article_info','',$this->getDbLink(1));	
			
			
			$a_info=$dbc->where('id='.$pi['id'])->find();
			
			$data=array(
				'title'=>$pi['title'],
				'intro'=>$pi['intro'],
				'content'=>$_POST['content'],
			);	
			
			$result=$dbc->where('id='.$pi['id'])->save($data);
			
			if($pi['add_img']){
				$img_dbc=M('bbs_doc_article_images','',$this->getDbLink(1));
				$img_dbc->where('article_id='.$pi['id'])->delete();
				foreach ($pi['pic_url'] as $v){
					$arr=array(
							'article_id'=>$pi['id'],
							'source_image_url'=>$v,
							'createDate'=>date('Y-m-d H:i:m',time())
					);
					$img_dbc->add($arr);
					
				}
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
	public function DocArticleManageDel($data){
		$dbc=M('bbs_doc_article_info','',$this->getDbLink(1));
		$result=$dbc->where('id='.$data)->delete();
		
		$img_dbc=M('bbs_doc_article_images','',$this->getDbLink(1));
		$img_dbc->where('article_id='.$data)->delete();
		return $result;
	} 
	public function DocArticleManageAuth($id,$data){
		$dbc=M('bbs_doc_article_info','',$this->getDbLink(1));
		$d=$dbc->where('id='.$data)->find();		
		if($d['sys_flag']==0 && $d['report_flag']==1){
			$poList=$dbc->order('sort')->select();
			$this->getPlaceModule($id,0);
			$this->assign('poList',$poList);			
		}
		$d['imgs']=M('bbs_doc_article_images','',$this->getDbLink(1))->where('article_id='.$data)->getField('source_image_url',true);
		$d['class']=M('article_class','',$this->getDbLink(1))->where('id='.$d['report_columns'])->getField('class_name');	
		$d['img']=strtr(C('IMG_URL').$d['imgs'][0],array('M00'=>'MOO/data'));		

		$this->assign('data',$d);
		$this->display('auth');
	}
	public function DocArticleManageDoAuth(){
		if(IS_POST){
			$pi=I('post.');  				
			//dump($_POST);exit;
			$dbc=M('bbs_doc_article_info','',$this->getDbLink(1));
			$info=$dbc->where('id='.$pi['id'])->find();
			$da=array(
					'report_flag'=>$pi['state']>0?2:3,
			);				
			$result=$dbc->where('id='.$pi['id'])->save($da);

			if($pi['state']==1){
				$imgs=M('bbs_doc_article_images','',$this->getDbLink(1))->where('article_id='.$pi['id'])->getField('source_image_url',true);				
				if($imgs){
					$img=strtr(C('IMG_URL').$imgs[0],array('M00'=>'MOO/data'));
					$content='<p><img style="width:100%;" src="'.$img.'" /></p><div style="font-size: 14px;line-height: 2em;color: #666666;">'.$info['content'].'</div>';					
				}else{
					$content='<div style="font-size: 14px;line-height: 2em;color: #666666;">'.$info['content'].'</div>';
				}
			$a_data=array(
					'title'=>$info['title'],
					'content'=>$content,
					'view_type'=>4,
					'create_time'=>date('Y-m-d H:i:m',time()),
					'doctor_id'=>$info['doc_id'],
					'examine_time'=>date('Y-m-d H:i:m',time()),
					'employee_id'=>$_SESSION['SESS_EmployeeInfo']['id'],
					'state'=>1
			);
			//dump($a_data);exit;
				$aid=M('com_top_article','',$this->getDbLink(1))->add($a_data);				
				M('article_belong_class','',$this->getDbLink(1))->add(array('article_id'=>$aid,'class_id'=>$info['report_columns']));
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
	
	
}