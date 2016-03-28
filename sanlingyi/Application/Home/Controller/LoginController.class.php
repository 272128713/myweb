<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller{
	public function login()
	{
		$this->display('login');
	}
	public function loginTo()
	{		
		$pi=I('post.');
		$pi['passwd']=sha1($pi['passwd']);
		$dbc=M('employee_info');
		$ep=$dbc->where("account='%s' and passwd='%s'",$pi)->find();
		$result=0;
		if($ep['id']>1){
			if($ep['status']>0){
				$dbc->where('employee_id='.$ep['employee_id'])->setField('session_id',session_id());
			}
			else{
				$result=2;//帐号停用
			}
		}
		else{
			$result=1;//账号密码不匹配
		}
		
		switch($result){
			case 1:
				$this->ajaxReturn('1');
				break;
			case 2:
				$this->ajaxReturn('2');
				break;
			default:
				//如果登陆成功，初始化该用户的所有信息，信息的存储分两部分，session和catch参照readme.txt
				session('SESS_EmployeeInfo',$ep);
				session('SESS_LoginTime',Date('Y-m-d H:i:s'));				
				$c=S($ep['id']);
				if(empty($c['CATCH_ConfigReset'])){
					$login=D('Login');
					$lp=$login->getUserPower($ep['role_id']);
					$catch['CATCH_ModulePower']=$lp['power'];
					$catch['CATCH_Remind']=$lp['remind'];
					############################################################
					//取出该用户针对所有受地域限制的模块所拥有的操作范围
					$catch['CATCH_ModuleRegion']=$login->getUserRegionModule($ep['id'],$ep['role_id'],$lp['area']);
				}
				else{
					$catch['CATCH_ModuleRegion']=$c['CATCH_ModuleRegion'];
					$catch['CATCH_ModulePower']=$c['CATCH_ModulePower'];
				}
				session('SESS_optRemind',$catch['CATCH_Remind']);
				session('SESS_optRegion',$catch['CATCH_ModuleRegion']);				
				session('SESS_optPower',$catch['CATCH_ModulePower']);				
				$catch['CATCH_ConfigReset']=1;
				$catch['CATCH_SessionID']=session_id();
				S($ep['id'],$catch);
				actionLog(0,'登录系统');//操作日志记录
				$this->ajaxReturn('0');
		}
	}
	
	public function loginOut($status=0){
		$dbc=M('employee_info');
		$e=session('SESS_EmployeeInfo');
		$dbc->where('id='.$e['id'])->setField('session_id','');
		session('[destroy]');
		switch ($status)
		{
			case 1:
				$str= "警告：由于您长时间没有操作，<BR />请重新登录！";
				$this->error($str,U('Login/login'),10);
				break;
			case 2:
				$str="警告：您的帐号在其他设备上登录了，<BR />请重新登录并立即修改密码！";
				$this->error($str,U('Login/login'),10);
				break;
			case 3:
				$str="警告：不要试图尝试通过不正常的访问方式使用系统！";
				$this->error($str,U('Login/login'),10);
			case 4:
				$str="提示：系统管理员更改了系统核心参数的配置，<BR />请重新登录！";
				S($e['id'],NULL);
				$this->error($str,U('Login/login'),10);
			default:
				//属于正常退出
				$this->login();
		}
	}

}




