<?php
/**
 * 员工控制器
 */
class WorkerController extends  CommonController{
	/**
	 * 员工首页
	 */
	public  function  index(){
		$logger = Logger::getLogger(basename(__FILE__));
		$return=array();
		$model=M('Worker');
		$user=$this->user;
		$user_arr=array();
		$user_arr['uid']=$user['member_id'];
		$user_arr['worker_truename']=$user['member_truename'];
		$return=array_merge($return,$user_arr);
		
		$return=array_merge($return,M('User')->getVersion($user['member_id']));
		//门店信息
		$shop_id=$user['store_id'];
		$return['worker_shop_name']=$model->conn->getOne("select shop_name from mall_shop where shop_id=$shop_id");
		$return['total_mnum']=$model->get_total_num($user['member_id']);
		$uid=$user['member_id'];
		$return['now_mnum']=$model->conn->getOne("select count(*) as now_mnum from mall_member
				 where parent_id=$uid and user_type_id=1");

        $uid=$user['member_id'];
        $sql="select  member_id from mall_msg where member_id=$uid and msg_status=0";
        if(M()->conn->getOne($sql)){
            $return['msg_status']=1;
        }else{
            $return['msg_status']=0;
        }
        $pid=$user['parent_id'];
        $sql="select  member_truename as agency_name  from mall_member where member_id=$pid";
        $return['agency_name']=M()->conn->getOne($sql);
		
        $return['sale_money']=$model->getSaleMoney($user['member_id'],"and date_format(now(),'%Y-%m')=FROM_UNIXTIME(finnshed_time,'%Y-%m')");
        //当月销售的钱
        
       $champin=$model
        					->champion($user['store_id'],"and date_format(now(),'%Y-%m')=FROM_UNIXTIME(finnshed_time,'%Y-%m')");
       if(!$champin){
            $champin["jmember_id"]= 0;
            $champin["jmember_truename"]=0;
            $champin["jthumbnail_image_url"]= 0;
            $champin["jpav"]=0;
            $champin["jpiv"]=0;
       } 
       $return=array_merge($return,$champin);
       ErrCode::echoJson(1,'执行成功',$return);
			
	}

    /**
     * 获取会员等级列表
     */
    public  function  getLevel(){
        $sql="select level_id,level_id,level_name from mall_member_levle order by level_sort";
        $result=M('Worker')->conn->getAll($sql);
        ErrCode::echoJson(1,'获取成功',$result);
    }
    
    /**
     * 增加会员
     */
    public  function  addMember(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$model=M('Worker');
    	$params = array(array("member_level",true),
    			        array("member_name",true),
    			        array("disease",true),
    					array("member_truename",true),
    					array("member_sex",true),
    					array("card_id",true),
    			        array("member_areaid",true),
    			        array("d_address",true),
    					array("member_address",true),
    					array("member_provinceid",true),
    					array("member_cityid",true),
    					array("p_address",true)
    	               );
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$username = $params["member_name"];
    	$num_exp = "/^1[034578][0-9]{9}$/";
    	$disease = $params["disease"];
    
    	if(!preg_match($num_exp, $username)){   //判断用户名是否为手机号码
    		$logger->error(sprintf("the username format is error.username is %s",$username));
    		ErrCode::echoErr(ErrCode::API_ERR_USERNAME_FORMAT,1);
    	}
    	if(!M('User')->userRegisterCheck($username)){
    		$logger->error(sprintf("username is exist. username is %s",$username));
    		ErrCode::echoErr(ErrCode::API_ERR_USERNAME_EXIST,1);
    	}
    	if(!M('User')->userCheck($username)){
    		$logger->error(sprintf("username checking %s",$username));
    		ErrCode::echoErr(ErrCode::API_ERR_USERNAME_CHECKING,1);
    	}
    	
    	
    	$worker=$this->user;
    	
    	//取上限比较
    	$rcid=$model->get_total_num($worker['member_id']);
    	//现有会员
    	$sql="select COUNT(*) from mall_member where user_type_id=1 and parent_id=".$worker['member_id'];
    	$now_num=$model->conn->getOne($sql);
    	if($now_num>=$rcid){
    		$logger->error(sprintf("user_num has >100 %s",$username));
    		ErrCode::echoErr(ErrCode::UPPER_LIMIT,1);
    	}
    	$model->addMember($worker,$params);
    	
    	/**
        $shop=$model->conn->getOne('select shop_name from mall_shop WHERE shop_id='.$worker['store_id']);
        $mec_str="您的".$shop."有新用户加入会员手机号为".$username;
        $mec_arr=array(
            'type'=>'WAM',
            'user_id'=>$worker['member_id'],
            'mobile'=>$worker['member_name'],
            'content'=>$mec_str,
            'shop_id'=>$worker['store_id'],
            'accepters'=>M('User')->getUserInfoByUid($worker['parent_id'])
        );
        send_msg(2,$worker['parent_id'],$shop,null,$mec_arr);
        */
    	ErrCode::echoJson(1,'添加成功');
    
    
    }
    
 /******************************************xu***************************************************/
    /**
     * 获取我的会员
     */

    public  function  getMember(){
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
        check_role($user,2);
        $pid=$user['member_id'];
        $sql="select a.member_id,a.member_name as mobile ,a.member_truename, a.member_birthday,a.member_sex,a.member_address,a.member_level
               ,b.thumbnail_image_url,b.image_ver as pav from mall_member as a,mall_user_version_info as b where a.parent_id=$pid
                and b.user_id=a.member_id  and a.user_type_id=1 limit $page,$this->page
                ";
        $result=M('User')->conn->getAll($sql);

        $mode=M('Worker');
        //取得会员上限
        $num=$mode->conn->getOne("select count(*) from mall_member WHERE parent_id=$pid and user_type_id=1");
        $total_num=$mode->get_total_num($pid);
        $return['now_num']=$num;
        $return['has_num']=$total_num-$num;
        $return['member']=$result;
        if($return){
        	ErrCode::echoJson(1,'成功',$return);
        	
        }else{
        	ErrCode::echoJson(1,'获取失败',array());
        	
        }
       

    }

    /**
     *  1.3.22. Worker/getGoods（提货）
     */
    public function getGoods(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$this->conn=M()->conn;
    	$this->Logger=$logger;
    	$params = array(array("ss",true),array("code",true),array("uid",true));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$code = $params["code"];  //提货码
    	$uid=$params['uid']; //会员id
    	$user=$this->user;
    	$wid=$user['member_id'];
        check_role($user,2);
        //查看是否未取消订单
        $order_id="select order_id from mall_order  where buyer_id=$uid and order_code='$code'
        and order_state=20 ";
         
        if(!$this->conn->getOne($order_id)){
        //退货正在进行中
       		 ErrCode::echoErr(ErrCode::CODE_NOT_EXIST,1);
        }else{
        $sql="update  mall_order
        	set order_state= 50,take_time=now(),return_woker_id=$wid where order_code='$code' and buyer_id=$uid";      
        	if($this->conn->execute($sql)){
        	ErrCode::echoJson(1,'成功');
        }else{
        ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        	}
        	}
         /*
    	$wid=$user['member_id']; //获取该登陆的员工的id
    	$store_id=$user['store_id'];
    	$sql="select a.member_id,a.agency_money,b.order_id,b.order_code,b.order_state ,b.order_amount
    	from mall_order as b,mall_member as a 
    	where b.order_code='$code' and b.buyer_id='$uid' and a.member_id=(select parent_id from mall_member where member_id='$wid')
    	";
    	$result=M()->conn->getRow($sql);
    	$order_id=$result['order_id'];
    	$temp1=$result['order_code'];
    	$temp2=$result['order_state'];
    	$order_amount=$result['order_amount']; //该订单的价格
    	$agency_id=$result['member_id']; //经销商的id 
    	$agency_money=$result['agency_money']; //经销商所拥有的钱
    	$agency_money1=$agency_money+$order_amount;
    	if($temp1){
    		$conn=M()->conn;   //M()->conn是连接数据库操作,在这里$conn是object（对象）类型
    		$logger=M()->logger;
    		  if($temp2==20){
    		  	$sql_m="select shop_money  from mall_shop where agency_id='$agency_id' and  shop_id='$store_id'";
    		  	$result_m=M()->conn->getOne($sql_m);  //店铺原有的资金
    		  	$temp_money=$result_m+$order_amount;
    		  	//UNIX_TIMESTAMP()是mysql取得当前时间戳的函数，而time()时间戳，date("Y-m-d H:i:s",time())日期格式，  是php取得当前时间的函数
    		
    		  	
    		  	    //开始事务
    		  	    $conn->startTrans();
    		  	    $sql2="update  mall_order set order_state='40',finnshed_time=UNIX_TIMESTAMP()  where order_code='$code' and buyer_id='$uid'";
    		  	    if(!$conn->execute($sql2)){
    		  	    	$conn->completeTrans(false);
    		  	    	$logger->error(sprintf("update  order_state fail.".$sql2));
    		  	    	ErrCode::echoErr(ErrCode::CODE_NOT_EXIST,1);
    		  	    }
    		  	  
    		  	  	$sql3="update mall_shop set shop_money='$temp_money' where agency_id='$agency_id' and shop_id='$store_id' ";	
    		  	  	if(!$conn->execute($sql3)){
    		  	  		$conn->completeTrans(false);
    		  	  		$logger->error(sprintf("update shop_money fail.".$sql3));
    		  	  		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    		  	  	}
    		  	  	
    		  	  	
    		  	  	$sql4="update mall_member set agency_money='$agency_money1' where member_id='$agency_id'  ";
    		  	  	if(!$conn->execute($sql4)){
    		  	  		$conn->completeTrans(false);
    		  	  		$logger->error(sprintf("update  fail.".$sql4));
    		  	  		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    		  	  	}
    		  	  	
    		  	  	
    		  	  	$sql5="insert into mall_pd_recharge (worker_id,pdr_member_id,pdr_amount,pdr_payment_name,pdr_add_time,resource_id)
    		  	  	values('$wid','$agency_id','+$order_amount','购买商品',UNIX_TIMESTAMP(),'$order_id')";
    		  
    		  	  	if(!$conn->execute($sql5)){
    		  	  		$conn->completeTrans(false);
    		  	  		$logger->error(sprintf("insert agency_money fail.".$sql5));
    		  	  		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    		  	  	}
    		  	  	
    		  	  	
    		  	  	//结束事务，提交数据
    		  	 	$conn->completeTrans(true);
    		  	 	ErrCode::echoJson(1,'成功');
    		  	 	exit();
    		  }		
    	}
    	ErrCode::echoErr(ErrCode::CODE_NOT_EXIST,1);  //更新失败，就提示系统错误
    	*/
    	
    }
    
    /**
     * 1.3.23. Worker/returnGoods（退货）
     */
    public  function returnGoods(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$this->conn=M()->conn;
    	$this->Logger=$logger;
    	$params = array(array("ss",true),array("oid",true),array("uid",false));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$oid=$params['oid'];
    	$uid=$params['uid'];
    	$user=$this->user;
    	$wid=$user['member_id'];
    	check_role($user,2);
    	//查看是否未取消订单
    	$order_id="select order_id from mall_order  where order_id=$oid
    	and (order_state=0 or order_state=70)";
    	
    	if($this->conn->getOne($order_id)){
    	//退货正在进行中
    	ErrCode::echoErr(ErrCode::API_ERR_RETURNISDOING,1);
    	}else{
	    	$sql="update  mall_order
	    		set order_state= 70,return_time=now(),return_woker_id=$wid where order_id=$oid ";
	    			
	    		if($this->conn->execute($sql)){
	    		ErrCode::echoJson(1,'成功');
	    	}else{
	    	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	    	}
    	}
    	/*
        $wid=$user['member_id']; //获取该登陆的员工的id
        $store_id=$user['store_id']; //店铺id
    	$sql="select order_code,order_state from mall_order where order_code='$code' and buyer_id='$uid' ";
    	$result=M()->conn->getRow($sql);
    	$temp1=$result['order_code'];
    	$temp2=$result['order_state'];
    	if($temp1){
    		$conn=M()->conn;   //M()->conn是连接数据库操作
    		$logger=M()->logger;
    		if($temp2==20 || $temp2==40){  //当状态为已付款或者已收货
    			
    			//查询会员的相关信息
    			$sql3="select a.order_id,a.buyer_id,a.order_amount,a.use_points,b.agency_money,b.member_points
    			from  mall_order as a
    			LEFT JOIN mall_member as b  On b.member_id=a.buyer_id
    			where order_code='$code' and buyer_id='$uid'  ";
    			$result3=M()->conn->getRow($sql3);
    			$order_id=$result3['order_id'];
    			$temp3=$result3['order_amount']+$result3['agency_money'];  //这个是买家的钱（用退还的订单钱数加上买家原本有的钱）
    			$temp5=$result3['use_points']+$result3['member_points']; //这个是买家的积分
    			//查询经销商的相关信息
    			$sql333="select member_id,agency_money from mall_member
    			where member_id=(select parent_id from mall_member where member_id='$wid')
    			";
    			$result333=M()->conn->getRow($sql333);
    			$agency_id=$result333['member_id']; //经销商的id
    			$agency_money=$result333['agency_money']; //经销商所拥有的钱
    			$agency_money1=$result333['agency_money']-$result3['order_amount']; //经销商的钱减去订单的钱
    			//查询店铺所拥有的钱
    			$sql_m1="select shop_money  from mall_shop where agency_id='$agency_id' and  shop_id='$store_id'";
    			$result_m1=M()->conn->getOne($sql_m1);  //店铺原有的资金
    			$temp_money1=$result_m1-$result3['order_amount'];  //店铺钱数减去订单的钱数
    			//开始事务
    			$conn->startTrans();
    			//这是退款并更改状态信息
    			$sql2="update  mall_order set order_state=0 where order_code='$code' and buyer_id='$uid'";
    			if(!$conn->execute($sql2)){
    				$conn->completeTrans(false);
    				$logger->error(sprintf("update  order_state fail.".$sql2));
    				ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    			}
    			
    			
    			if($temp2==40){
    				//这是退给会员的钱
    				$sql4="update mall_member set agency_money='$temp3',member_points='$temp5'  where member_id='$uid'";
    				if(!$conn->execute($sql4)){
    					$conn->completeTrans(false);
    					$logger->error(sprintf("update agency_money fail.".$sql4));
    					ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    				}
    				//店铺退钱
    				$sql3="update mall_shop set shop_money='$temp_money1' where agency_id='$agency_id' and shop_id='$store_id' ";
    				if(!$conn->execute($sql3)){
    					$conn->completeTrans(false);
    					$logger->error(sprintf("update shop_money fail.".$sql3));
    					ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    				}
    			
    				//销商退钱
    				$sqlnn="update mall_member set agency_money='$agency_money1' where member_id='$agency_id' ";
    				if(!$conn->execute($sqlnn)){
    					$conn->completeTrans(false);
    					$logger->error(sprintf("update agency_money fail.".$sqlnn));
    					ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    				}
    				
    				$temp23=$result3['order_amount'];
    				$sql5="insert into mall_pd_recharge (worker_id,pdr_member_id,pdr_amount,pdr_payment_name,pdr_add_time,resource_id)
    				values('$wid','$agency_id','-$temp23','退款',UNIX_TIMESTAMP(),'$order_id')";
    				if(!$conn->execute($sql5)){
    					$conn->completeTrans(false);
    					$logger->error(sprintf("insert agency_money fail.".$sql5));
    					ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    				}
    			}
    			
                //这是退给会员的钱
    			$sql4="update mall_member set agency_money='$temp3',member_points='$temp5'  where member_id='$uid'";
    			if(!$conn->execute($sql4)){
    				$conn->completeTrans(false);
    				$logger->error(sprintf("update agency_money fail.".$sql4));
    				ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    			}
    			 
    			//结束事务，提交数据
    			$conn->completeTrans(true);
    			ErrCode::echoJson(1,'成功');
    			exit();
    		}
    	}
    	
    	ErrCode::echoErr(ErrCode::CODE_NOT_EXIST,1);  //更新（删除/增加）失败，就提示系统错误
    	 */
    	
    }
    /**
     *   1.3.25. Worker/getMsgList（获取消息提醒列表）
     */
  public function getMsgList(){
  	
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
  	check_role($user,2); //检查登陆的账户是不是员工
  	$wid=$user['member_id']; //获取该登陆的员工的id
  	//实现业务逻辑       会员的姓名   会员头像      购买商品的消息内容       消息的时间
  	$sql="select a.id as msg_id,a.msg_content,a.msg_status,FROM_UNIXTIME(a.msg_time,'%Y-%m-%d %H:%i') as msg_time,a.msg_type,a.child_id as user_id,
  	        b.member_truename,c.thumbnail_image_url
  			from mall_msg as a
  			LEFT JOIN mall_member as b On a.child_id=b.member_id
  			LEFT JOIN mall_user_version_info as c On a.child_id=c.user_id
  			where a.member_id=$wid
  			order by a.msg_time desc
  	       limit $page,$this->page";
  	$result=M()->conn->getAll($sql);
  	if($result){
  		ErrCode::echoJson(1,'成功',$result);
  	}else{
  		ErrCode::echoJson(1,'没有查找到数据/失败',array());
  	}
  	
  }
  /**
   *  1.4.9. Worker/MemberConsumeList（根据id获取消费记录）
   */
  public  function  MemberConsumeList(){
  	 $logger = Logger::getLogger(basename(__FILE__));
        $params = array(array("page",false),array("ss",true),array("uid",true));
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
        check_role($user,2);
        $uid=$params['uid']; //会员id
        $model=M('User')->conn;
        //查寻订单记录
        $sql="select order_id,order_code,order_state,use_points,order_amount,add_time from mall_order
              where buyer_id=$uid  order by add_time desc  limit $page,$this->page ";
        $result=$model->getAll($sql);
       
        if(empty($result)){
            ErrCode::echoJson(1,'成功',$result);
        }
        //查询订单相关产品
//         foreach($result  as $k=>$v){

//             $sql="select goods_id,goods_name,goods_pay_price,goods_image from mall_order_goods where order_id=".$v['order_id'];
//             $prodcut=$model->getAll($sql);
//             foreach($prodcut as $key=>$value){
//                 $prodcut[$key]['goods_image']=formatImg($value['goods_image'],240);
//             }
//             $result[$k]['product_list']=$prodcut;
//             $gid=$prodcut[0]['goods_id'];
//             //是否有套餐
//             $sql="select bl_id from mall_p_bundling_goods WHERE goods_id=$gid order by bl_goods_id desc";
//             $pid=$model->getOne($sql);
//             //套餐名字
//             if($pid) {
//                 $sql = "select bl_name from mall_p_bundling where bl_id=$pid";
//                 $result[$k]['pakage_name'] = $model->getOne($sql);
//             }
//         }
        //查询订单相关产品
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
        
        if($result){
        	ErrCode::echoJson(1,'成功',$result);
        	 
        }else{
        	ErrCode::echoJson(1,'获取失败',array());
        	 
        }
     
  }
  
 
  
  
/******************************************xu***************************************************/
    
    /**
     * 根据id获取用户资料
     */
    public  function  getUserbyId(){
        $logger = Logger::getLogger(basename(__FILE__));
        $params = array(array("ss",true),array('uid',true));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        if(isset($params['page'])){
            $page=($params['page']-1)*($this->page);

        }
        $user=$this->user;
        //check_role($user,2);
        $uid=$params['uid'];
        if(strpos($uid,',')){
        	$sql="select a.member_name as mobile,a.disease,a.member_id,a.member_truename, a.member_birthday,a.member_sex,a.member_address,a.member_level
        	,b.thumbnail_image_url,b.image_ver as pav ,b.base_ver as piv from mall_member as a,mall_user_version_info as b where
        	b.user_id=a.member_id  and a.member_id in ($uid)
        	";
        	$result=M('User')->conn->getAll($sql);
        	//$result['shop_name']=M('User')->conn->getOne("select shop_name from mall_shop where shop_id=".$user['store_id']);
        }else{
       		 $sql="select a.member_name as mobile,a.disease,a.member_id,a.member_truename, a.member_birthday,a.member_sex,a.member_address,a.member_level
               ,b.thumbnail_image_url,b.image_ver as pav ,b.base_ver as piv from mall_member as a,mall_user_version_info as b where
                b.user_id=a.member_id  and a.member_id=$uid
                ";
        $result=M('User')->conn->getRow($sql);
        $result['shop_name']=M('User')->conn->getOne("select shop_name from mall_shop where shop_id=".$user['store_id']);
        }
        ErrCode::echoJson(1,'成功',$result);

    }

    /**
     * 给会员充值
     */
    public  function  recharge(){
        $logger = Logger::getLogger(basename(__FILE__));
        $params = array(array("ss",true),array('un',true),array('money',true),array('pay_way',true));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        $user=$this->user;
        check_role($user,2);
        $uid=M('User')->isMember($params['un']);
        //是否为本店会员
        $sql="select store_id from mall_member where member_id=$uid";
        $shop_id=M()->conn->getOne($sql);
        if($shop_id!=$user['store_id']){
        	ErrCode::echoErr(ErrCode::NOT_SHOP_MEMBER,1);
        }
        //是否为会员
        $sql="select user_type_id from mall_member where member_id=$uid";
        $shop_id=M()->conn->getOne($sql);
        if($shop_id!=1){
        	ErrCode::echoErr(ErrCode::NOT_SHOP_MEMBER,1);
        }
        $model=M('Worker');
        $way=$params['pay_way'];
        $m=$params['money'];
//         if($way==2){
//     		$rcent=$this->scaleMoney;
//     		$giver_money=$m*$rcent[$m];
//     		$m+=$m*$rcent[$m];
    		
//     	}else{
//     		$giver_money=0;
//     	}
        $result=$model->recharge($user,$uid,$m,$way);
        ErrCode::echoJson(1,'充值成功');
    }

    /**
     * 员工待处理业务列表
     */
    public function taskList(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$model=M('Worker');
    	$params = array(array("ss",true),array("type",true),array("page",false),);
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
    	$res=$model->getTaskList($user,$params);
    
    	ErrCode::echoJson(1,'请求成功',$res);
    }
    
	
}