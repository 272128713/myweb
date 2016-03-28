<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends TemplateController{
    public function index(){
    	$dbc=M('module_info');
    	$p=implode(',',session('SESS_optPower'));
    	$row=$dbc->where('status>0 and level=1 and module_id in('.$p.')')->order('position')->select();
    	foreach($row as $k=>$v)
    	{
    		$power[$k]=$v;
    		$rw=$dbc->where(' status>0 and parentId='.$v['module_id'].' and module_id in('.$p.')')->order('position')->select();
    		$power[$k]['item']=$rw;
    	}   

    	//dump(session('SESS_optRemind'));
    	
    	if(is_array(session('SESS_optRemind'))){
    		$this->assign("remind",serialize(session('SESS_optRemind')));
    		$this->assign("isRemind",1);
    	}
    	else{
    		$this->assign("isRemind",0);
    	}    	
    	$this->assign("modList",$power);
		$this->display();
    }
    public function changePasswd()
    {
    	if(IS_POST)
    	{
    		$res=M('employee_info');
    		$e=session('SESS_EmployeeInfo');
    		$user['user_id'] = $e['id'];
    		$user['passwd'] = sha1(I('post.op'));
    		
    		
    		
    		$row=$res->where("id='%d' and passwd='%s'",$user)->count();
    		//echo $res->getLastSql();
    		if($row>0)
    		{
    			$np=sha1(I('post.np'));
    			if($res->where('id='.$user['user_id'])->save(array('passwd'=>$np)))
    			{
    				$this->ajaxReturn('0');
    			}
    			else
    			{
    				$this->ajaxReturn('2');
    			}
    		}
    		else
    		{
    			$this->ajaxReturn('1');
    		}
    	}
    	else
    	{
    		$this->display();
    	}
    }
    public function blank(){
		$this->display();
    }
    public function moduleDirect($id){
    	//重置搜索条件    
		if($_SESSION['SESS_ListItemData'][$id]['parameter']['condition'] != ''){
			$_SESSION['SESS_ListItemData'][$id]['parameter']['condition']='';			
		}
		if($_SESSION['SESS_ListItemData'][$id]['parameter']['nowPage'] != 0){
			$_SESSION['SESS_ListItemData'][$id]['parameter']['nowPage']=1;
		}	
		if($_SESSION['SESS_ListItemData'][$id]['parameter']['order'] != ''){
			$_SESSION['SESS_ListItemData'][$id]['parameter']['order']='';
		}	
// 		if(session("SESS_ListItemData.".$id.".parameter.condition") != ''){
// 			session("SESS_ListItemData.".$id.".parameter.condition",'');
// 		}
		
		
    	$dbc=M('module_info');
    	$res=$dbc->find($id);
    	session('SESS_optModuleID',$id);
    	session('SESS_optModuleEN',$res['en']);    	
    	//所有页面都需要显示的bar信息======================================
    	$this->getPlaceModule($id,1);
    	$data['moduleID']=$id;
    	$param=array(
    			'moduleID'=>$id,
    			'objectID'=>''
    	);
    	//结束对bar的操作==================================================
    	switch ($res['module_style']){ 		
    		case 0://整页框架+自定义页面
    			$tplFrame='frameDefined';
    			//不使用template框架，直接调用定义好的controller。
    			$data['dirUrl']=$res[en].'/index';
    			break;
    		case 1://整页框架+列表页面
    			$this->tplList($id);
    			$tplFrame='frameList';
    			//结束属性查找==========================================
    			break;
    		case 2:
    			$tplFrame='frameTree';		//树+明细
    			break;
    		case 3:
    			$this->tplList($id);
    			$tplFrame='frameTreeList';	//树+列表
    			break;
    		default:
    			$tplFrame='frameTreeDifined';	//树+自定义
    	}
    	$this->assign('param',$param);
    	$this->assign('data',$data);
    	$this->display($tplFrame);
    }
}