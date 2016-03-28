<?php
/**
 * 经销商控制器
 */
class AgencyController extends  CommonController{
	/**
	 * 经销商首页
	 */
	public  function  index(){
		$logger = Logger::getLogger(basename(__FILE__));
		$return=array();
		$model=M('Agency');
		$user=$this->user;
		$user_arr=array();
		$user_arr['uid']=$user['member_id'];
		$user_arr['agency_truename']=$user['member_truename'];
		$user_arr['agency_province']=$model->getArea($user['member_provinceid']);
		$user_arr['agency_city']=$model->getArea($user['member_cityid']);
		$return=array_merge($return,$user_arr);
		
		$return['member_cityid']=$user['member_cityid'];
        $id=$user['member_id'];
        $return['shop_nums']=$model->conn->getOne("select count(*) as shop_nums from mall_shop where agency_id=$id and
			  shop_state=1");
		//门店信息
		
	    
		
		$uid=$user['member_id'];
        $sql="select  member_id from mall_msg where member_id=$uid and msg_status=0";
        if(M()->conn->getOne($sql)){
        	$return['msg_status']=1;
        }else{
            $return['msg_status']=0;
        }
        
        //员工数
        $sql="select count(*) from mall_member where (user_type_id=2 or user_type_id=4)  
        	  and parent_id=".$user['member_id'];
        $wnum=$model->conn->getOne($sql);
        
        //是否是店长
        if($user['is_manage']==1){
        	$wnum=$wnum+1;
        	$return['user_role']=4;
        }
        //是否是员工
        else if($user['store_id']){
        	$wnum=$wnum+1;
        	$return['user_role']=2;
        }else{
        	$return['user_role']=3;
        }
        $return['wnum']=$wnum;
        
        //会员个数
        $sql="select count(*) from mall_member where user_type_id=1 and 
        	  agency_id=".$user['member_id'];
        $return['mnum']=$model->conn->getOne($sql);
     
        //总的业绩
        $return['total_money']=$user['agency_money'];
        //返回总
        $return['agency_order']=$model->getAgency();
		ErrCode::echoJson(1,'执行成功',$return);
			
	}

    /**
     * 增加会员
     */
    public  function  addWorker(){
        $logger = Logger::getLogger(basename(__FILE__));
        $model=M('Agency');
        $params = array(array("sid",true),array("mobile",true));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        $username = $params["mobile"];
        $num_exp = "/^1[034578][0-9]{9}$/";

        if(!preg_match($num_exp, $username)){   //判断用户名是否为手机号码
            $logger->error(sprintf("the username format is error.username is %s",$username));
            ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
        }
        if(!M('User')->userRegisterCheck($username)){
            $logger->error(sprintf("username is exist. username is %s",$username));
            ErrCode::echoErr(ErrCode::API_ERR_USERNAME_EXIST,1);
        }
        $worker=$this->user;
        $model->addWorker($worker,$params['sid'],$params['mobile']);
        ErrCode::echoJson(1,'添加成功');


    }

    /**
     * 开店
     */
    public  function  addShop(){
        $logger = Logger::getLogger(basename(__FILE__));
        $model=M('Agency');
        $params = array(array("name",true),array("address",true),array("aid",true));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        $user=$this->user;
        check_role($user,3);
        $aid=$user['member_id'];
        $area_id=$params['aid'];
        $shop_name=$params['name'];
        
        //区地址
        $area=$model->getArea($area_id);
        if($area){
        	$addrss=$area.$params['address'];
        }else{
        	$addrss=$params['address'];
        }
       
        $sql="insert into mall_shop (shop_name,shop_address,agency_id,shop_time,shop_areaid)
              VALUES ('$shop_name','$addrss',$aid,UNIX_TIMESTAMP(),$area_id)";
        if($model->conn->execute($sql)){
            ErrCode::echoJson(1,'成功');
        }else{
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }

    }
    
    /**
     * 申请提现
     */
    public  function applyMoney(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$model=M('Agency');
    	$params = array(array("money",true));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
    	check_role($user,3);
    	$money=$params['money'];
    	if($money<100){
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	}
        if($money>$user['agency_money']){
            ErrCode::echoErr(ErrCode::API_NOT_SUFFICIENT_FUNDS,1);
        }
        if($user['bank_num']==''){
            ErrCode::echoErr(ErrCode::API_NOT_HAVE_BANK,1);
        }
        $model->applyMoney($user,$money);
        ErrCode::echoJson(1,'提现成功');
    }
    
    
   /****************************************xu************************************************/ 
    
    /**
     * 1.3.5 applyMoneyLog（提现记录）
     */
    public function applyMoneyLog(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$params = array(array("ss",true),array("page",false));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	//获取参数
    	if(isset($params['page'])){
    		$page=($params['page']-1)*($this->page);
    	}else{
    		$page=0;
    	}
    	$user=$this->user;
    	check_role($user,3); 
    	//获取用户的对应的会员id     从mall_member 这个表中找出对应关系
    	$memberid=$user['member_id'];
    	$sql="select id, money,bank_name,bank_num,account_name,apply_time,apply_status 
    	  	  from mall_apply_money 
    	      where agency_id='$memberid' 
    	      order by apply_time desc 
    	      limit $page,$this->page ";
    	$result=M()->conn->getAll($sql);
    	if($result){
    		$return=array();
    		$return['agency_money']=$user['agency_money'];
    		$return['bank_name']=$user['bank_name'];
    		$return['bank_num']=$user['bank_num'];
    		$return['account_name']=$user['account_name'];
    		$return['list']=$result;
    		ErrCode::echoJson(1,'成功',$return);
    	}else{
    		$return=array();
    		$return['agency_money']=$user['agency_money'];
    		$return['bank_name']=$user['bank_name'];
    		$return['bank_num']=$user['bank_num'];
    		$return['account_name']=$user['account_name'];
            ErrCode::echoJson(1,'失败/或者数据为空',$return);
    	}
    }
    /**
     * 1.3.9. Agency/getShopInfo（获取店铺详情）
     */
    public function getShopInfo(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$params = array(array("sid",true),array("page",false));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	//获取参数
    	$sid = $params["sid"];
    	if(isset($params['page'])){
    		$page=($params['page']-1)*($this->page);
    	}else{
    		$page=0;
    	}
    	$user=$this->user;
    	check_role($user,3);
    	$sql="select shop_name,shop_address, FROM_UNIXTIME(shop_time,'%Y-%m-%d %H:%i:%s') as shop_time,shop_money,shop_member_num,shop_woker_num 
        from mall_shop where shop_id=$sid order by shop_time limit $page,$this->page ";
    	$result=M()->conn->getRow($sql);
    	if($result){
    		ErrCode::echoJson(1,'成功',$result);
    	}else{
    		ErrCode::echoJson(1,'失败/或者数据为空',array());
    	}
    }	 

    /**
     *  1.3.10 Agency/getShopWorker（获取店铺员工列表）
     */
    public function  getShopWorker(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$params = array(array("sid",true),array("page",false),array("type",false));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	//获取参数
    	$sid = $params["sid"];	
    	if(isset($params['page'])){
    		$page=($params['page']-1)*($this->page);
    	}else{
    		$page=0;
    	} 
    	$user=$this->user;
    	//check_role($user,4);
    	//查询本店店长id
    	$manage_id=M()->conn->getOne('select member_id from mall_shop_view  where shop_id='.$sid);
    	//获取用户的对应的会员id     从mall_member 这个表中找出对应关系
    	$memberid=$user['member_id'];  //经销商id
    	
    	$temp=conf('CAN_HAVE_MEMBER_NUM');
    	$sql2="select a.member_name as mobile, if(a.member_id=$memberid,1,0) as is_manager,a.member_id,a.member_truename,b.base_ver as  piv,b.image_ver as pav,b.thumbnail_image_url,
    		  COUNT(c.member_id) as now_num,$temp as total_num
    	      from mall_member as a 
    		  LEFT JOIN mall_user_version_info as b  ON b.user_id=a.member_id 
    		  LEFT JOIN mall_member as c on c.parent_id=a.member_id 
    	      where  a.member_id in (select member_id from mall_member where store_id=$sid)   and  a.user_type_id !=1
    	      GROUP BY a.member_id
    	     limit $page,$this->page ";
    	
    	if(isset($params['type'])){
    		$sql2="select a.member_id,a.member_name as mobile,a.member_truename,b.base_ver as  piv,b.image_ver as pav,b.thumbnail_image_url,
    		COUNT(c.member_id) as now_num,$temp as total_num
    		from mall_member as a
    		LEFT JOIN mall_user_version_info as b  ON b.user_id=a.member_id
    		LEFT JOIN mall_member as c on c.parent_id=a.member_id
    		where  a.member_id in (select member_id from mall_member where store_id=$sid)   and  a.user_type_id =2
    		GROUP BY a.member_id
    		limit $page,$this->page ";
    	}
    	$result=M()->conn->getAll($sql2);
    	//print_r($result);die();
       if($result){
    		ErrCode::echoJson(1,'成功',$result);
    	}else{
    		ErrCode::echoJson(1,'失败/或者数据为空',array());
    	}
    	
    }

    /**
     *  1.3.8  Agency/updateMsg(更新提醒状态）
     */
   public function updateMsg(){
   	
   	$logger = Logger::getLogger(basename(__FILE__));
   	$params = array(array("mid",true));
   	$params = Filter::paramCheckAndRetRes($_POST, $params);
   	if(!$params){
   		$logger->error(sprintf("params is err. params is %s",v($_POST)));
   		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
   	}
   	//获取参数
   	$mid = $params["mid"];   //mid 消息id
   	$user=$this->user;
   	$sql="update   mall_msg SET msg_status=1  where id=$mid";
   	$result =M()->conn->execute ($sql);
   	if(!isset($params['uid'])){
   	   //获取未读条数
   	   $sql="select count(*) from where msg_status=0 and member_id=".$user['member_id'];
   	   ErrCode::echoJson(1,'成功',array('num'=>M()->conn->getOne($sql)));
   	}
   	if($result){
   		  ErrCode::echoJson(1,'成功');
   	}else{
   		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
   	}
   	
   }
    
   /**
    *   1.3.11.Agency/forbidMember（停用员工）
    */
  public function forbidMember(){
  	$logger = Logger::getLogger(basename(__FILE__));
  	$params = array(array("uid",true));
  	$params = Filter::paramCheckAndRetRes($_POST, $params);
  	if(!$params){
  		$logger->error(sprintf("params is err. params is %s",v($_POST)));
  		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
  	}
  
  	//获取参数
  	$uid = $params["uid"];
  	$user=$this->user;
  	//check_role($user,3);//校验是不是经销商
  	$conn=M()->conn;   //M()->conn是连接数据库操作
  	$logger=M()->logger;
    //实现业务逻辑
    $sql="select member_id from mall_member  where  parent_id=$uid and user_type_id=1 limit 1";
  	if($conn->getRow($sql)){
  		ErrCode::echoErr(ErrCode::YOU_HAVE_SOME_MEMBER,1);
  	}
    $sql="update mall_member set member_state=0  where member_id='$uid'";
  	$result = M()->conn->execute($sql);
  	ErrCode::echoJson(1,'成功');
  	
  	
  /* 	$sqll="select shop_woker_num from mall_shop where shop_id=(select store_id from mall_member where member_id=$uid)";
  	$res = M()->conn->getOne($sqll);
    $res1=$res-1;
    $sqlnn="select store_id from mall_member where member_id=$uid";
    $ress = M()->conn->getOne($sqlnn);
  	//开始事务
  	$conn->startTrans();
  	$sql="delete from mall_member where member_id=$uid ";
  	if(!$conn->execute($sql)){
  		$conn->completeTrans(false);
  		$logger->error(sprintf("delete fail.".$sql));
  		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
  	}
  	$sql_n="delete from mall_user_session_info where user_id=$uid ";
  	if(!$conn->execute($sql_n)){
  		$conn->completeTrans(false);
  		$logger->error(sprintf("delete fail.".$sql_n));
  		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
  	}
  	$sql_m="delete from mall_user_version_info where user_id=$uid ";
  	if(!$conn->execute($sql_m)){
  		$conn->completeTrans(false);
  		$logger->error(sprintf("delete fail.".$sql_m));
  		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
  	}
  	$sql_x="update mall_shop set shop_woker_num=$res1  where shop_id=$ress";
  	if(!$conn->execute($sql_x)){
  		$conn->completeTrans(false);
  		$logger->error(sprintf("update fail.".$sql_x));
  		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
  	}
  
  	$sql2="update mall_member set parent_id=1111111111111 where parent_id=$uid";
  	if(!$conn->execute($sql2)){
  		$conn->completeTrans(false);
  		$logger->error(sprintf("update parent_id fail.".$sql2));
  		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
  	}
  	//结束事务，提交数据
  	$conn->completeTrans(true);
  	ErrCode::echoJson(1,'成功');
   */
  }
  /**
   *   1.3.12. Agency/getShopMember（获取店铺会员信息）
   */
  public function getShopMember(){
  		$logger = Logger::getLogger(basename(__FILE__));
  		$params = array(array("sid",true),array("page",false));
  		$params = Filter::paramCheckAndRetRes($_POST, $params);
  		if(!$params){
  			$logger->error(sprintf("params is err. params is %s",v($_POST)));
  			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
  		}
  		//获取参数
  		$sid = $params["sid"];  //店铺id
  		if(isset($params['page'])){
  			$page=($params['page']-1)*($this->page);
  		}else{
  			$page=0;
  		}
  		$user=$this->user;
  		check_role($user,3);  
    $sql="select a.member_id,a.parent_id as worker_id,a.member_truename,a.member_address,a.member_level,
  		b.thumbnail_image_url as member_thumbnail_image_url,b.base_ver as mpiv,b.image_ver as mpav
  		from mall_member as a,mall_user_version_info as  b
         where  a.member_id=b.user_id  and  a.user_type_id = 1  and  a.store_id='$sid' limit $page,$this->page ";
   $result=M()->conn->getAll($sql);
  	foreach($result as $k=>$v){
  		
  		if($result[$k]['worker_id']!='1111111111111'){

  			$temp=$result[$k]['worker_id'];
  			$ssql="select a.member_truename as worker_truename,b.thumbnail_image_url as worker_thumbnail_image_url,
  	          		b.base_ver as wpiv,b.image_ver as wpav 
  	          		from mall_member as a 
  	          		LEFT JOIN mall_user_version_info as b  On  b.user_id=a.member_id 
  	          		where a.member_id='$temp' " ;
  			$result1=M()->conn->getRow($ssql);
  			$result[$k] =array_merge($v,$result1);  //合并数组
  		}
  	}	
  	
  
  	   if($result){
  	   	ErrCode::echoJson(1,'成功',$result);
  	   }else{
  	   	ErrCode::echoJson(1,'失败/或者数据为空',array());
  	   }
  	   
  }
  /**
   *   1.3.13. Agency/getReallocationList（获取未分配的会员信息）
   */
  public function getReallocationList(){
  	$logger = Logger::getLogger(basename(__FILE__));
  	$params = array(array("sid",true),array("wid",true),array("page",false));
    $params = Filter::paramCheckAndRetRes($_POST,$params);
  		if(!$params){
  			$logger->error(sprintf("params is err. params is %s",v($_POST)));
  			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
  		}
  	//获取参数
  	$sid = $params["sid"];  //店铺id
  	$wid = $params["wid"];  //员工的id
  	if(isset($params['page'])){
  		$page=($params['page']-1)*($this->page);
  	}else{
  		$page=0;
  	}
  	$user=$this->user;
  	check_role($user,3);
  	//查询未分配会员的相关信息
  	$sql="select a.member_id,a.member_truename,a.member_address,a.member_level,
  			    b.thumbnail_image_url as member_thumbnail_image_url,
  	            b.base_ver as mpiv,b.image_ver as mpav
  			    from mall_member as a,mall_user_version_info as b 
  			    where a.parent_id=1111111111111 and a.store_id=$sid and a.member_id=b.user_id limit $page,$this->page";
  	$result=M()->conn->getAll($sql);
  	//查询员工的相关信息
  	$sql2="select a.member_id as worker_id,a.member_truename as worker_truename, 
  	b.base_ver as wpiv
  	from mall_member as a
  	LEFT JOIN mall_user_version_info as b On  a.member_id=b.user_id
  	where a.member_id=$wid ";
  	$result2=M()->conn->getRow($sql2);
  	//查询店铺名字
  	$sql3="select shop_name from mall_shop where shop_id=$sid";
  	$result3=M()->conn->getRow($sql3);
  	//查询未分配的会员数
  	$sql4="select count(*) as reallocation_num from mall_member where parent_id=1111111111111 and store_id=$sid";
  	$result4=M()->conn->getRow($sql4);
  	$mode=M('Worker');
  	//取得会员上限
  	$num=$mode->conn->getRow("select count(*) as now_num from mall_member WHERE parent_id=$wid and user_type_id=1"); //统计出该员工现有的会员数
  	$total_num=$mode->get_total_num($wid);
  	$return['total_num']=$total_num;
  	//$return['has_num']=$total_num-$num;  //这个是该员工还能有多少个会员
  	$return['now_num']=$num['now_num'];
  	$return['worker_id']=$result2['worker_id'];
  	$return['worker_truename']=$result2['worker_truename'];
  	$return['wpiv']=$result2['wpiv'];
  	$return['shop_name']=$result3['shop_name'];
  	$return['reallocation_num']=$result4['reallocation_num'];   //未分配的会员数
  	$return['member']=$result;
  	//print_r($return);die();
  	if($return){
  		ErrCode::echoJson(1,'成功',$return);
  		
  	}else{
  		ErrCode::echoJson(1,'失败/或者数据为空',array());
  		
  	}
  	
  }
  
  /**
   * 1.3.14. Agency/getMemberInfo  根据员工id获取会员列表
   */
  public  function  getMemberInfo(){
  	$logger = Logger::getLogger(basename(__FILE__));
  	$params = array(array("wid",true),array("page",false));
  	$params = Filter::paramCheckAndRetRes($_POST, $params);
  	if(!$params){
  		$logger->error(sprintf("params is err. params is %s",v($_POST)));
  		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
  	}
  	if(isset($params['page'])){
  		$page=($params['page']-1)*($this->page);
  
  	}else{
  		$page=0;
  	}
  	$user=$this->user;
  	check_role($user,3); //验证是否是经销商操作
  	$wid=$params['wid'];  //获取到传递过来的员工id
  	$sql="select a.member_id,a.member_truename,a.member_address,a.member_level,
  	 b.thumbnail_image_url, b.base_ver as piv,b.image_ver as pav
  	 from mall_member as a,mall_user_version_info as b 
  	 where a.parent_id=$wid and b.user_id=a.member_id  and a.user_type_id=1 limit $page,$this->page";
  	$result=M('User')->conn->getAll($sql);
    $sql2="select a.member_id as worker_id,a.member_truename as worker_truename, b.thumbnail_image_url as worker_thumbnail_image_url,
    		b.base_ver as wpiv,b.image_ver as wpav
    		from mall_member as a,mall_user_version_info as b  
    		where member_id=$wid and a.member_id=b.user_id and a.user_type_id=2";
    $result2=M()->conn->getRow($sql2);
    $sql_r="select shop_name,shop_id from mall_shop where shop_id=(select store_id from mall_member where member_id=$wid)";
    $result_r=M()->conn->getRow($sql_r);
  	$mode=M('Worker');
  	//取得会员上限
  	$num=$mode->conn->getOne("select count(*) from mall_member WHERE parent_id=$wid and user_type_id=1");
  	$total_num=$mode->get_total_num($wid);
  	$return['total_num']=$total_num;
  	$return['has_num']=$num;
  	$return['worker_id']=$result2['worker_id'];
  	$return['worker_truename']=$result2['worker_truename'];
  	$return['worker_thumbnail_image_url']=$result2['worker_thumbnail_image_url'];
  	$return['wpiv']=$result2['wpiv'];
  	$return['wpav']=$result2['wpav'];
  	$return['shop_name']=$result_r['shop_name'];
  	$return['shop_id']=$result_r['shop_id'];
  	$return['member']=$result;
  	//print_r($return);die();
  if($return){
  		ErrCode::echoJson(1,'成功',$return);
  		
  	}else{
  		ErrCode::echoJson(1,'失败/或者数据为空',array());
  		
  	}
  }
  /**
   * 1.3.15. Agency/ReallocationMember（给员工分配会员）
   */
  public function ReallocationMember(){
  	$logger = Logger::getLogger(basename(__FILE__));
  	$params = array(array("uid",true),array("wid",true));
  	$params = Filter::paramCheckAndRetRes($_POST,$params);
  	if(!$params){
  		$logger->error(sprintf("params is err. params is %s",v($_POST)));
  		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
  	}
  	//获取参数
  	$uid = $params["uid"];   //会员的id（可以为多个，例如1,2,3）注意：这里传递过来是个字符串
  	$wid = $params["wid"];  //员工的id
  	$number=count(explode(",",$uid));  //按照逗号拆分字符串
  	$user=$this->user;
  	check_role($user,4);
  	//给员工分配会员
  	$mode=M('Worker');
   //取得会员上限,统计出该员工现有的会员数
  	$num=$mode->conn->getOne("select count(*)  from mall_member WHERE parent_id=$wid and user_type_id=1");
  	$total_num=$mode->get_total_num($wid); //返回员工所能拥有的最多会员数目
  	$has_num=$total_num-$num;  //这个是该员工还能有多少个会员
  	 $temp=$has_num-$number;
  	// print_r($temp);die();
  	if($temp>=0){
  		$sql="update mall_member set parent_id=$wid where member_id in ($uid)";
  		$result=M()->conn->execute($sql);
  	}else{
  		return "您不能再分配到".$number."个会员了";
  	}
  	if($result){
  		ErrCode::echoJson(1,'给员工分配会员成功');
  	}else{
  		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
  	}
  	
  }
  
  /**
   *  1.3.16. Agency/getMemberDetail（获取会员详情）
   */
  public  function getMemberDetail(){
  		$logger = Logger::getLogger(basename(__FILE__));
  		$params = array(array("uid",true));
  		$params = Filter::paramCheckAndRetRes($_POST,$params);
  		if(!$params){
  			$logger->error(sprintf("params is err. params is %s",v($_POST)));
  			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
  		}
  		//获取参数
  		$uid = $params["uid"]; //会员id
  		$user=$this->user;
  		check_role($user,3); //检查是否是经销商操作
  		$sql="select a.store_id,a.member_id,a.member_truename,a.member_birthday,a.member_sex,a.member_address,a.member_level,
  		      a.member_name as mobile,b.thumbnail_image_url, b.base_ver as piv,b.image_ver as pav,
  		      c.member_truename as worker_truename,c.member_sex as worker_sex ,d.thumbnail_image_url  as worker_thumbnail_image_url
  	          from mall_member as a
  	          LEFT JOIN mall_user_version_info as b On  a.member_id=b.user_id
  	          LEFT JOIN mall_member as c  On a.parent_id=c.member_id
  	          LEFT JOIN mall_user_version_info as d On c.member_id=d.user_id
  			  where a.member_id=$uid";
  	    $result=M()->conn->getRow($sql);
        if($result){
        	$result['age']=getAge($result['member_birthday']);
  	   	    ErrCode::echoJson(1,'成功',$result);
  	     }else{
  	      	 ErrCode::echoJson(1,'失败/或者数据为空',array());
  	    }
  }
  
  /**
   *  1.1.17. Agency/afreshMember（重新分配会员）
   */
 public function afreshMember(){
 	$logger = Logger::getLogger(basename(__FILE__));
 	$this->logger=$logger;
 	$params = array(array("uid",true),array("wid",true),array("shop_id",false));
 	$params = Filter::paramCheckAndRetRes($_POST,$params);
 	if(!$params){
 		$logger->error(sprintf("params is err. params is %s",v($_POST)));
 		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
 	}
 	//获取参数
 	$uid = $params["uid"]; //会员id
 	$wid = $params["wid"]; //员工id 即要分配给哪个员工
 	$user=$this->user;
 	check_role($user,4); //检查是否是经销商操作
 	//实现业务逻辑
     //给员工分配会员
     $mode=M('Worker');
     //取得会员上限,统计出该员工现有的会员数
     $num=$mode->conn->getOne("select count(*)  from mall_member WHERE parent_id=$wid and user_type_id=1");
     $total_num=$mode->get_total_num($wid); //返回员工所能拥有的最多会员数目
     $has_num=$total_num-$num;  //这个是该员工还能有多少个会员
     $temp=$has_num-$number;
     if($temp>=0){
     	 $this->conn=M()->conn;
     	 $this->conn->startTrans();
     	 $conn=$this->conn;
     	 $member_id=$conn->getRow("select store_id,user_type_id from mall_member where member_id=".$params['uid']);
     	 if(isset($params['shop_id']) &&$member_id['store_id']!=$params['shop_id']){
     	 	    	 	
	     	 	//减人数
	     	 	$sql="update mall_shop set shop_member_num= shop_member_num-1 where shop_id=".$member_id['store_id'];
	     	 	if(!$this->conn->execute($sql)){
	     	 		$this->conn->completeTrans(false);
	     	 		$this->logger->error(sprintf("reduce num fail".$sql));
	     	 		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	     	 	}
	     	 	//加人数
	     	 	$sql="update mall_shop set shop_member_num= shop_member_num+1 where shop_id=".$params['shop_id'];
	     	 	if(!$this->conn->execute($sql)){
	     	 		$this->conn->completeTrans(false);
	     	 		$this->logger->error(sprintf("reduce num fail".$sql));
	     	 		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	     	 	}
     	 	
     	 	$sql="update mall_member set store_id=".$params['shop_id'].", parent_id=$wid where member_id=$uid";
     	 }else {
     	 	$sql="update mall_member set  parent_id=$wid where member_id=$uid";
     	 }
     	 
     	 $result=$this->conn->execute($sql);
     }
     if($result){
     	$this->conn->completeTrans(true);
     	ErrCode::echoJson(1,'给员工分配会员成功');
     }else{
     	$this->conn->completeTrans(false);
     	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
     }
 	
 }
 
 /**
  *  1.3.18. Agency/getMemberConsume（获取会员消费记录）
  */
 
 public function getMemberConsume(){
 	$logger = Logger::getLogger(basename(__FILE__));
 	$params = array(array("uid",true),array("page",false));
 	$params = Filter::paramCheckAndRetRes($_POST, $params);
 	if(!$params){
 		$logger->error(sprintf("params is err. params is %s",v($_POST)));
 		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
 	}
 	if(isset($params['page'])){
 		$page=($params['page']-1)*($this->page);
 	
 	}else{
 		$page=0;
 	}
 	$user=$this->user;
 	check_role($user,3); //验证是否是经销商操作
 	$uid=$params['uid'];  //会员id
 	$model=M('User')->conn;
 	//实现业务逻辑
 	//查寻订单记录
 	$sql="select add_time,order_id,order_code,order_state,use_points,order_amount,FROM_UNIXTIME(add_time,'%Y-%m-%d %H:%i:%s') as add_time 
 	 from mall_order
 	where buyer_id=$uid  order by add_time desc  limit $page,$this->page ";
 	$result=$model->getAll($sql);
 	if(empty($result)){
 		ErrCode::echoJson(1,'成功',$result);
 	}

//  	//查询订单相关产品
//  	foreach($result  as $k=>$v){
//  	   $sql2="select goods_id,goods_name,goods_pay_price,goods_image from mall_order_goods where order_id=".$v['order_id'];
//  		$prodcut=$model->getAll($sql2);
//  		foreach($prodcut as $key=>$value){
//  			$prodcut[$key]['goods_image']=formatImg($value['goods_image'],240);
//  		}
//  		$result[$k]['product_list']=$prodcut;
//  		$gid=$prodcut[0]['goods_id'];
//  		//是否有套餐
//  		$sql="select bl_id from mall_p_bundling_goods WHERE goods_id=$gid order by bl_goods_id desc";
//  		$pid=$model->getOne($sql);
//  		//套餐名字
//  		if($pid) {
//  			$sql = "select bl_name from mall_p_bundling where bl_id=$pid";
//  			$result[$k]['pakage_name'] = $model->getOne($sql);
//  		}
 	
//  	}
 	foreach($result  as $k=>$v){
 	
 		$sql="select goods_id,goods_name,goods_pay_price,goods_image from mall_order_goods where order_id=".$v['order_id'];
 		$prodcut=$model->getAll($sql);
 		foreach($prodcut as $key=>$value){
 			$prodcut[$key]['goods_image']=formatImg($value['goods_image'],240);
 		}
 		//$result[$k]['product_list']=$prodcut;
 		 
 		$gid=$prodcut[0]['goods_id'];
 		//商品id
 		$result[$k]['goods_id']=$gid;
 		//是否有套餐
 		$sql="select bl_id from mall_p_bundling_goods WHERE goods_id=$gid order by bl_goods_id desc";
 		$pid=$model->getOne($sql);
 		//套餐名字
 		if($pid) {
 			$sql = "select bl_name from mall_p_bundling where bl_id=$pid";
 	
 			$pname = $model->getOne($sql);
 			//$result[$k]['pakage_name']=$pname;
 			if(count($prodcut)>1){
 				//是套餐
 				$result[$k]['goods_name']=$pname;
 				$result[$k]['goods_image']=$this->domain.'Public/Home/images/pakage.jpg';
 			}else{
 				$result[$k]['goods_name']=$prodcut[0]['goods_name'];
 				$result[$k]['goods_image']=$this->img.$prodcut[0]['goods_image'];
 			}
 	
 		}else{
 			$result[$k]['goods_name']=$prodcut[0]['goods_name'];
 			$result[$k]['goods_image']=$this->img.$prodcut[0]['goods_image'];
 		}
 	
 	
 	}
 	ErrCode::echoJson(1,'成功',$result);
 	

 }
 /**
  * 1.3.19. Agency/getShopConsume（获取门店销售记录）
  */
 
 public function getShopConsume(){
 	
 	$logger = Logger::getLogger(basename(__FILE__));
 	$params = array(array("sid",true),array("page",false));
 	$params = Filter::paramCheckAndRetRes($_POST, $params);
 	if(!$params){
 		$logger->error(sprintf("params is err. params is %s",v($_POST)));
 		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
 	}
 	if(isset($params['page'])){
 		$page=($params['page']-1)*($this->page);
 	
 	}else{
 		$page=0;
 	}
 	$user=$this->user;
 	check_role($user,3); //验证是否是经销商操作
 	$sid=$params['sid'];  //店铺id
 	$model=M('User')->conn; //实例化UserModel这个类，并且连接数据库  这里$model就是一个对象了
 	//实现业务逻辑
 	//根据店铺id查找出来所有的会员id
 	 $sql_n="select member_id from mall_member where store_id=$sid and user_type_id=1";
 	//查寻订单记录
 	$sql="select order_id,buyer_id as member_id,order_code,order_state,use_points,order_amount,FROM_UNIXTIME(add_time,'%Y-%m-%d %H:%i:%s') as add_time 
 			from mall_order
 			where buyer_id in ($sql_n)  
 			order by add_time desc  
 			limit $page,$this->page ";
 	$result=$model->getAll($sql);
 	if(empty($result)){
 		ErrCode::echoJson(1,'成功',$result);
 	}
 	
//  	//查询订单相关产品
//  	foreach($result  as $k=>$v){
 	
//  		$sql2="select goods_id,goods_name,goods_pay_price,goods_image from mall_order_goods where order_id=".$v['order_id'];
//  		$prodcut=$model->getAll($sql2);
//  		foreach($prodcut as $key=>$value){
//  			$prodcut[$key]['goods_image']=formatImg($value['goods_image'],240);
//  		}
//  		$result[$k]['product_list']=$prodcut;
//  		$gid=$prodcut[0]['goods_id'];
//  		//是否有套餐
//  		$sql="select bl_id from mall_p_bundling_goods WHERE goods_id=$gid order by bl_goods_id desc";
//  		$pid=$model->getOne($sql);
//  		//套餐名字
//  		if($pid) {
//  			$sql = "select bl_name from mall_p_bundling where bl_id=$pid";
//  			$result[$k]['pakage_name'] = $model->getOne($sql);
//  		}
 	
 	
//  	}
     
 	//查询订单相关产品
 	
 	foreach($result  as $k=>$v){
 		$img_info=M('User')->getVersion($v['member_id']);
 		$img_info['member_truename']=$model->getOne("select member_truename from mall_member where 
 				member_id=".$v['member_id']);
 		
 	  
 		$sql="select goods_id,goods_name,goods_pay_price,goods_image from mall_order_goods where order_id=".$v['order_id'];
 		$prodcut=$model->getAll($sql);
 		foreach($prodcut as $key=>$value){
 			$prodcut[$key]['goods_image']=formatImg($value['goods_image'],240);
 		}
 		//$result[$k]['product_list']=$prodcut;
 	
 		$gid=$prodcut[0]['goods_id'];
 		//商品id
 		$result[$k]['goods_id']=$gid;
 		//是否有套餐
 		$sql="select bl_id from mall_p_bundling_goods WHERE goods_id=$gid order by bl_goods_id desc";
 		$pid=$model->getOne($sql);
 		//套餐名字
 		if($pid) {
 			$sql = "select bl_name from mall_p_bundling where bl_id=$pid";
 	
 			$pname = $model->getOne($sql);
 			//$result[$k]['pakage_name']=$pname;
 			if(count($prodcut)>1){
 				//是套餐
 				$result[$k]['goods_name']=$pname;
 				$result[$k]['goods_image']=$this->domain.'Public/Home/images/pakage.jpg';
 			}else{
 				$result[$k]['goods_name']=$prodcut[0]['goods_name'];
 				$result[$k]['goods_image']=$this->img.$prodcut[0]['goods_image'];
 			}
 	
 		}else{
 			$result[$k]['goods_name']=$prodcut[0]['goods_name'];
 			$result[$k]['goods_image']=$this->img.$prodcut[0]['goods_image'];
 		}
 		$result[$k]=array_merge($result[$k],$img_info);
 	
 	}
     
 	ErrCode::echoJson(1,'成功',array_values($result));
 }
 
   /****************************************xu************************************************/
    /**
     * 设置银行卡号
     */
    public  function  setBank(){
        $logger = Logger::getLogger(basename(__FILE__));
        $model=M('Agency');
        $params = array(array("bank_name",true),array("bank_num",true),array("account_name",true),);
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        $user=$this->user;
        check_role($user,3);
        $bank_name=$params['bank_name'];
        $bank_num=$params['bank_num'];
        $account_name=$params['account_name'];
        $agency_id=$user['member_id'];
        $sql="update mall_member set bank_name='$bank_name',bank_num='$bank_num',account_name='$account_name' where member_id=$agency_id";
        if($model->conn->execute($sql)){
            ErrCode::echoJson(1,'提现成功');
        }else{
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
    }

    /**
     * 获取经销商提醒列表
     */
    public  function  getMsg(){
        $logger = Logger::getLogger(basename(__FILE__));
        $params = array(array("ss",true),array("page",false));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        if(isset($params['page'])){
            $page=($params['page']-1)*($this->page);

        }else{
            $page=0;
        }
        $user=$this->user;
        $id=$user['member_id'];
        $sql="select a.id as msg_id,a.msg_content,a.shop_id,a.msg_time,a.msg_status,a.msg_type,b.msg_type_name
             from mall_msg as a,mall_msg_type as b where a.member_id=$id and b.msg_type_id=a.msg_type
             order by a.msg_status asc,a.msg_time desc  limit $page,$this->page
             ";
        ErrCode::echoJson(1,'获取成功',M()->conn->getAll($sql));

    }
    
    /**
     * 经销商或店长发布公告
     */
    public function addNotice(){
    	    	
    	$logger = Logger::getLogger(basename(__FILE__));
    	$model=M('Notice');
    	$params = array(array("ss",true),array("content",true),array("receive_type",true),array("receive_store",true));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
    	//check_role($user,3);
    	$res=$model->addNotice($user,$params['receive_type'],$params['receive_store'],$params['content']);
    	if($res){
    		ErrCode::echoJson(1,'成功');
    	}else{
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	}
    }
    /**
     * 经销商或店长取得已发公告列表
     */
    public function getNoticeList(){
    
    	$logger = Logger::getLogger(basename(__FILE__));
    	$model=M('Notice');
    	$params = array(array("ss",true),array("page",false));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
    	//check_role($user,3);
    	$res=$model->getSendNoticeList($user['user_id'],$params['page']);
    	//print_r($res);exit;
    	if($res){
    		ErrCode::echoJson(1,'成功',$res);
    	}else{
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	}
    } 
    /**
     * 经销商详情
     */
    public function getNoticeDetial(){    
    	$logger = Logger::getLogger(basename(__FILE__));
    	$model=M('Notice');
    	$params = array(array("ss",true),array("id",true));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
    	//check_role($user,3);
    	$res=$model->getNoticeDetial($user['user_id'],$params['id']);
    	//print_r($res);exit;
    	if($res){
    		ErrCode::echoJson(1,'成功',$res);
    	}else{
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	}
    }  

    


	
}