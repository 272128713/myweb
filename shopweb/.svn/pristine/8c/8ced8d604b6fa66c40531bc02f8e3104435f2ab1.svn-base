<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 经销商控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/21
 * Time: 13:43
 */

class AgencyController extends  CommonController{

    /**
     * 查看店铺详情视图
     */
    public  function  shopDetail(){
		session('sid',$_GET['sid']);
    	$data=array(
    		'ss'=>session('yixin_ss'),
    		'sid'=>$_GET['sid'], //店铺ID
    		'page'=>1
		);
		//获取店铺信息
        $result=poster("Agency/getShopInfo",$data);
        if($result['code']==1){
        	$this->info= $result['result'];
        	
        }else{
        	$this->error($result['msg']);
        }
        //dump($result);
        //die();
		//获取员工信息   	
        $result=poster("Agency/getShopWorker",$data);
        //dump($result);
        //die();
        //dump(count($result['result']));
        //die();
        if($result['code']==1){
        	$this->workerInfo= $result['result'];
        	
        }else{
        	$this->error($result['msg']);
        }   	
        //获取会员信息
        $result=poster("Agency/getShopMember",$data);

        //dump($result);
        //die();
        if($result['code']==1){
        	$this->member= $result['result'];
        	
        }else{
        	$this->error($result['msg']);
        }  
        //店铺销售记录
        
        $result=poster("Agency/getShopConsume", $data);
        //dump($result);
    	//die();
        if($result['code']==1){
        	$this->shop= $result['result'];
        	
        }else{
        	$this->error($result['msg']);
        }   	
        $this->display();
    }
	//店铺员工详情分页

    public function updatestaffAjax(){
    	echo ($this->updatestaff());
    }
    public function updatestaff(){
 

    	$data=array(
    		'ss'=>session('yixin_ss'),
    		'sid'=>session('sid'),
    		'page'=>$_GET['page']
		);   	
		$result=poster("Agency/getShopWorker",$data);
        if($result['code']==1){
        	$this->workerInfo=$result['result'];
        	
        }else{
        	//$this->error($result['msg']);
        }   
    	$this->display('shopDetail_staff');
    }
	//店铺会员详情分页

    public function updateuserAjax(){
    	echo ($this->updateuser());
    }
    public function updateuser(){
 
    
    	$data=array(
    		'ss'=>session('yixin_ss'),
    		'sid'=>session('sid'),
    		'page'=>$_GET['page']
		);   	
		$result=poster("Agency/getShopMember",$data);
		//dump($result['result']);
		//die();
        if($result['code']==1){
        	$this->member=$result['result'];
        	
        }else{
        	//$this->error($result['msg']);
        }   
    	$this->display('shopDetail_user');
    }
	//店铺销售记录分页

    public function updateshopAjax(){
    	echo ($this->updateshop());
    }
    public function updateshop(){
    
    
    	$data=array(
    			'ss'=>session('yixin_ss'),
    			'sid'=>session('sid'),
    			'page'=>$_GET['page']
    	);
    	$result=poster("Agency/getShopConsume",$data);
    	
    	if($result['code']==1){
    		$this->shop=$result['result'];
    		 
    	}else{
    		//$this->error($result['msg']);
    	}
    	$this->display('shopDetail_shop');
    }	
	
    //增加员工方法
     
    public  function  addWoker(){
    	if (isset($_GET['sid'])) {
    		$sid=$_GET['sid'];    		
    	}else{
    		$sid=1;
    	}
        if(!IS_POST){
            $this->error('来源不合法');
        }else{
            //调用接口
            $data=array(
    			'ss'=>session('yixin_ss'),
                'sid'=>$sid,
                'mobile'=>I('mobile')
            );
			
            $result=poster("Agency/addWorker",$data);
            if($result['code']==1){
                  $this->redirect('Agency/shopDetail',array('sid'=>$sid));
            }else{
                  $this->error($result['msg']);
            }
        }
    
    }
	
	public	function agency_newshop(){
		$this->display();
	}
	//添加店铺
	public	function addShop(){
        if(!IS_POST){
            $this->error('来源不合法');
        }else{
        	$name=I('shopName');
        	$address=I('shopAdr');
        	$name=htmlspecialchars($name);
			$address=htmlspecialchars($address);
            $data=array(
           		'ss'=>session('yixin_ss'),
                'name'=>$name,
                'address'=>$address
            );
            $result=poster("Agency/addShop",$data);
            if($result['code']==1){
				echo "		
				<script type='text/javascript''>
					goToA();
					function goToA(){
		                    if(navigator.userAgent.match('iPhone')){
		                        goToAgency();
		                    }
		                    if(navigator.userAgent.match('Android')){
		                        Android.goToAgency(1);
		                    }
					}
				</script>
				";
				
            }else{
                  $this->error($result['msg']);
            }
		}
    }
    
    /**
     * 提现
    */ 
    public function agency_getcash(){
    	$session=session('user_info');
    	$this->bank_name=$session['bank_name'];
    	$this->bank_num=$session['bank_num'];
    	$this->account_name=$session['account_name'];
    	$this->agency_money=$session['agency_money'];
    	$data=array(
    		'ss'=>session('yixin_ss'),
    		'page'=>1
    	);
        $result=poster("Agency/applyMoneyLog",$data);
        //dump($result);
        //die();
		$pagenum=count($result['result']);
        if($result['code']==1){
        	$this->cashlist=$result['result'];
        }else{
            $this->error($result['msg']);
        }
        
    	$this->display();
    }
    //提现分页
    public function agency_getcashAjax(){
    	echo ($this->agency_updatecash());
    }
    public function agency_updatecash(){
    	
    	$data=array(
    			'ss'=>session('yixin_ss'),
    			'page'=>$_GET['page']
    	);
    	$result=poster("Agency/applyMoneyLog",$data);

    	if($result['result']){
    		$this->assign('result',$result['result']);
    	}else{
    		//$this->error($result['msg']);
    	}
    	$this->display('agency_cash_box');
    }
    //设置银行账户
    public function setBank(){
    	if(!IS_POST){
    		$this->error('来源不合法');
    	}else{
    		
    		$bank_name=I('bank_name');
    		$bank_num=I('bank_num');
    		$account_name=I('account_name');
    		$data=array(
    			'ss'=>session('yixin_ss'),
    			'bank_name'=>$bank_name,
    			'bank_num'=>$bank_num,
    			'account_name'=>$account_name
    		);
    		$result=poster('Agency/setBank', $data);
	        if($result['code']==1){
	        	$this->redirect('Agency/agency_getcash', array('ss' => session('yixin_ss')));
	        }else{
	            $this->error($result['msg']);
	        }
    	}
    }
    //提现
    public  function applyMoney(){
    	if(!IS_POST){
    		$this->error('来源不合法');
    	}else{
    		$money=I('money');
    		$data=array(
    			'ss'=>session('yixin_ss'),
    			'money'=>$money
    		);
    		$result=poster('Agency/applyMoney', $data);
	        if($result['code']==1){
	        	$this->redirect('Agency/agency_getcash', array('ss' => session('yixin_ss')),1,'页面跳转中...');	        	
	        }else{
	            $this->error($result['msg']);
    		}
    	}
    }
	/**
	 *提醒 
	 */
    public  function agency_remind(){
		$data=array(
			'ss'=>session('yixin_ss')
		);
		$result=poster('Agency/getMsg',$data);	        
		if($result['code']==1){
				$msg=$result['result'];
				$this->msg=$msg;

	        }else{
	            $this->error($result['msg']);
    		}
    	$this->display();
    }
    /**
     *分配会员
     */
    public  function assignMember(){
    	$data=array(
    			'ss'=>session('yixin_ss'),
    			'sid'=>$_GET['sid'],
    			'wid'=>$_GET['wid'],
    			'page'=>empty($_GET['page'])?1:$_GET['page']
    	);
    	if($_GET['page']>1){    		
    		$result=poster('Agency/getReallocationList',$data);
    		$result['result']['sid']=$data['sid'];
    		 
    		//dump($result['result']);exit;
    		 
    		$this->assign('result',$result['result']);
    		
    		$this->display('assignMemberMore');
    		
    	}else{//
    		$result=poster('Agency/getReallocationList',$data);
    		$result['result']['sid']=$data['sid'];
    		 
    		//dump($result['result']);exit;
    		 
    		$this->assign('result',$result['result']);
    		 
    		if($result['code']==1){
    			$msg=$result['result'];
    			$this->msg=$msg;
    		
    		}else{
    			$this->error($result['msg']);
    		}
    		$this->display();
    	}

    	
    } 
    /**
     *提交分配会员
     */
    public  function doAssignMember(){
    	
    	
    	//dump($_POST);exit;
    	$uid=trim($_POST['mid'],',');
    	$data=array(
    			'ss'=>session('yixin_ss'),
    			'uid'=>$uid,
    			'wid'=>$_POST['wid'],
    	);
    	$result=poster('Agency/ReallocationMember',$data);    	 
    	    	 
    	if($result['code']==1){
    		echo '1';
    
    	}else{
    		$this->error($result['msg']);
    	}
    } 
    /**
     *重新分配会员
     */
    public  function reAssignMember(){
    	
//     	$_GET['uid']=50120;
//     	$_GET['sid']=70;
    	
    	$data=array(
    			'ss'=>session('yixin_ss'),
    			'uid'=>$_GET['uid'],
    	);
    	$result=poster('Agency/getMemberDetail',$data);
        	

    	//var_dump($result);exit;

    	
    	if($_GET['page']<1){
    		$w_data=array(
    				'ss'=>session('yixin_ss'),
    				'sid'=>$_GET['sid'],
    				'page'=>empty($_GET['page'])?1:$_GET['page'],
    		);
    		$w_result=poster('Agency/getShopWorker',$w_data);
    		
    		
    		//var_dump($w_result);
    		
    		if($result['code']==1){
    			$this->assign('member_info',$result['result']);
    			$this->assign('worker_list',$w_result['result']);
    		}else{
    			$this->error($result['msg']);
    		}
    		$this->display('agency_reassign');
    	}else{
    		$w_data=array(
    				'ss'=>session('yixin_ss'),
    				'sid'=>$_GET['sid'],
    				'page'=>empty($_GET['page'])?1:$_GET['page'],
    		);
    		$w_result=poster('Agency/getShopWorker',$w_data);
    		
    		//dump($w_result);
    		
    		$this->assign('worker_list',$w_result['result']);
    		$this->display('agency_reassign_worker_list');
    	}

    } 
   //提交重新分配会员       
   public function doReAssignMember(){
	   	$data=array(
	   			'ss'=>session('yixin_ss'),
	   			'uid'=>$_POST['mid'], //会员id
	   			'wid'=>$_POST['wid'], //员工id
	   	);
	   	$result=poster('Agency/afreshMember',$data);
	   	if($result['code']==1){//重新分配成功
	   		echo '1';
	   	}else{
	   		
	   	}
   }
   //员工详情
   public function staffInfo(){
   	if($_GET['page']<1){
   		$data=array(
   				'ss'=>session('yixin_ss'),
   				'wid'=>$_GET['wid'], //员工id
   				'page'=>1,
   		);
   		$result=poster('Agency/getMemberInfo',$data);
   		
   			//dump($result);exit;
   		 
   		if($result['code']==1){
   			$this->assign('result',$result['result']);
   		}else{
   		
   		}
   		$this->display('agency_staff_info');   		
   	}else{
   		$data=array(
   				'ss'=>session('yixin_ss'),
   				'wid'=>$_GET['wid'], //员工id
   				'page'=>$_GET['page'],
   		);
   		
   	
   		
   		$result=poster('Agency/getMemberInfo',$data);
   		
   			//dump($result['result']);
   		 
   		if($result['code']==1){
   			$this->assign('result',$result['result']);
   		}else{
   		
   		}
   		$this->display('agency_staff_info_member');
   	}
  
   }
   	//停用员工
   	
   	public function closeWorker(){
   		 $wid=$_POST['wid'];
   		 
   		 $data=array(
   		 		'ss'=>session('yixin_ss'),
   		 		'uid'=>$wid, //员工id
   		 );
   		 $result=poster('Agency/forbidMember',$data);
   		 
   		 if($result['code']==1){
   		 	echo '1';
   		 }else{
   		 	
   		 }
   	}   
   	
   	//会员详情
   	
   	public  function  getMemberDetail(){
   		$data=array(
   			'ss'=>session('yixin_ss'),
   			'uid'=>$_GET['uid'],
   			'page'=>1
   		);
   		$result=poster("Agency/getMemberDetail", $data);
   		$result_mall=poster("Agency/getMemberConsume",$data);
   		
   		
   		//var_dump($result);
   		//var_dump($result_mall);
   		
    		if($result['code']==1){
    			$this->result=$result['result'];
    		}else{
    			$this->error($result['msg']);
    		}
    		if($result_mall['code']==1){
    			$this->mall=$result_mall['result'];
    		}else{
    			$this->error($result_mall['msg']);
    		}
   		$this->display();
   	}
    public function getMemberDetailajax(){
    	echo ($this->memberdetail());
    }
   	public function memberdetail(){
   		$data=array(
   			'ss'=>session('yixin_ss'),
   			'uid'=>$_GET['uid'],
   			'page'=>$_GET['page']
   		);
   		$result=poster("Agency/getMemberDetail", $data);
   		$result_mall=poster("Agency/getMemberConsume",$data);
   		
   		
    		if($result['code']==1){
    			$this->result=$result['result'];
    		}else{
    			//$this->error($result['msg']);
    		}
    		if($result_mall['code']==1){
    			$this->mall=$result_mall['result'];
    		}else{
    			//$this->error($result_mall['msg']);
    		}
    	$this->display("getMemberDetailajax");
   		
   	}
}
