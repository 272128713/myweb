<?php
//文章分类管理控制器
namespace Home\Controller;
use Think\Controller;
class ArticleClassManageController extends TemplateController{
	public function index(){
		
	}	
	public function ArticleClassManageList(){
		$dbc=M('article_class','',$this->getDbLink(1));
		$result=$dbc->field('class_name,sort,state,id')
					->order('sort asc')->select();		
		$this->tplListItem(session('SESS_optModuleID'),$result);
	}
	public function ArticleClassManageAdd($id){
		$dbc=M('article_class','',$this->getDbLink(1));
		$poList=$dbc->order('sort')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display('edit');
	}
	public function ArticleClassManageCreate(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('article_class','',$this->getDbLink(1));
				if($dbc->where('class_name="'.$pi['class_name'].'"')->find()){
					$this->ajaxReturn('1');
				}				
				if($dbc->add($pi)>0){														
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
	public function ArticleClassManageEditItemOpt(){
		return 'edit';
	}
	public function ArticleClassManageStatusItemOpt($optData){
		$result=$optData['state']>0?'true':'false';
		return $result;
	}
	public function ArticleClassManageDelItemOpt($optData){
		return 'del';
	}
	public function ArticleClassManageEdit($id,$data){
		$dbc=M('article_class','',$this->getDbLink(1));
		$d=$dbc->where('id='.$data)->find();
		$poList=$dbc->order('sort')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->assign('data',$d);
		$this->display('edit');
	}
	public function ArticleClassManageModify(){
		if(IS_POST){
			$pi=I('post.');
			$dbc=M('article_class','',$this->getDbLink(1));	
// 				if($dbc->where('class_name="'.$pi['class_name'].'"')->find()){
// 					$this->ajaxReturn('1');
// 				}			
				$result=$dbc->where('id='.$pi['id'])->save($pi);
				if($result>0){
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
	public function ArticleClassManageStatus($data){		
		$dbc=M('article_class','',$this->getDbLink(1));
		$t=$dbc->where('id='.$data)->find();
		$status=$t['state']>0?0:1;
		$dbc->where('id='.$data)->setField('state',$status);
		return true;
	}	
	public function ArticleClassManageDel($data){
		$dbc=M('article_class','',$this->getDbLink(1));
		$t=$dbc->where('id='.$data)->find();

		$result=$dbc->where('id='.$data)->delete();
		return $result;
	}	
}