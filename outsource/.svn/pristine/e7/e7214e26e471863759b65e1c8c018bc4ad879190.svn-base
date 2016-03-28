<?php
/**
 * 获取员工会员总数
 */


class WorkerModel extends  Model{
	/**
	 * 获取员工会员上限数
	 */
	public  function get_total_num($id){
		$sql="select parent_id from mall_member where member_id=$id";
		$agenid=$this->conn->getOne($sql);
		$sql="select parent_id from mall_member where member_id=$agenid";
		$boossid=$this->conn->getOne($sql);
		$sql="select worker_num from mall_store where member_id=$boossid";
		return  $this->conn->getOne($sql);
	}

    /**
     * 获取供应商id
     */
    public  function get_apply($id){
        $sql="select parent_id from mall_member where member_id=$id";
        $agenid=$this->conn->getOne($sql);
        $sql="select parent_id from mall_member where member_id=$agenid";
        $boossid=$this->conn->getOne($sql);
        $sql="select store_id from mall_store where member_id=$boossid";
        return  $this->conn->getOne($sql);
    }

    /**
     * 添加会员
     */
    public  function  addMember($worker,$pi){
            $pid=$worker['member_id'];
            $agency_id=$worker['parent_id'];
            $sid=$worker['store_id'];
            $gmoney=0;
            $gpoints=0;
            $pi['user_type_id']=1;
            $pi['store_id']=$sid;
            $pi['parent_id']=$pid;
            $pi['agency_id']=$agency_id;
            $pi['member_time']=time();
            
            //查询地址其他信息
            $this->conn->startTrans();
            $times=time();
            $sql=insert_sql("mall_member_tmp",$pi);
            if(!$this->conn->execute($sql)){
                $this->conn->completeTrans(false);
                $this->logger->error(sprintf("add member fail.".$sql));
                ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
            }
            $uid=$this->conn->getInsertID();
            $this->conn->completeTrans(true);
            
            
            /**
            //增加session
            $sql="insert into mall_user_session_info (user_id)
                  VALUES($uid)";
           if(!$this->conn->execute($sql)){
                $this->conn->completeTrans(false);
                $this->logger->error(sprintf("add session fail.".$sql));
                ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
             }
           //个人信息版本
            $sql="insert into mall_user_version_info (user_id)
                  VALUES($uid)";
           if(!$this->conn->execute($sql)){
                $this->conn->completeTrans(false);
                $this->logger->error(sprintf("add session fail.".$sql));
                ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
            }
           //店铺会员数
           $sql="select shop_member_num from mall_shop WHERE  shop_id=".$worker['store_id'];
           $oldnum=$this->conn->getOne($sql);
           $new_num=$oldnum+1;
           $sql="update mall_shop set shop_member_num=$new_num WHERE shop_id=". $worker['store_id'];
           //更新员工数量
           if(!$this->conn->execute($sql)){
               $this->conn->completeTrans(false);
               $this->logger->error(sprintf("update num fail.".$sql));
               ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
           }
           
           //给员工冲钱记录
        
           $sql="insert into mall_pd_recharge (worker_id,pdr_member_id,pdr_amount,pdr_payment_name,pdr_add_time,giver_money)
           VALUES($pid,$uid,'$gmoney','首次个人充值',UNIX_TIMESTAMP(),'0')";
           if(!$this->conn->execute($sql)){
           	$this->conn->completeTrans(false);
           	$this->logger->error(sprintf("log_record_error.".$sql));
           	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
           }
           //给员工冲钱记录积分奖励
           $sql=add_points_log($uid,$gpoints,'首次个人充值奖励',4);
           if(!$this->conn->execute($sql)){
           	$this->conn->completeTrans(false);
           	$this->logger->error(sprintf("log_record_error.".$sql));
           	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
           }*/
         


    }

    /**
     * 给会员充值
     */
    public  function  recharge($user,$uid,$m,$way){
        $wid=$user['member_id'];
        $nter=$this->conn->getOne("select value from mall_shop_config where name='RECHARGE_REWARD'");
        $points=0;
        if($way==2){
        	$inter=C('ntegral');
        	$points+=$m/$inter*$nter;
        }
        $sql="insert into mall_pd_recharge_tmp (worker_id,pdr_member_id,pdr_amount,pdr_payment_name,pdr_add_time,giver_money,pdr_type)
                  VALUES($wid,$uid,'$m','个人充值',UNIX_TIMESTAMP(),'$points',$way)";
        if(!$this->conn->execute($sql)){
            $this->conn->completeTrans(false);
            $this->logger->error(sprintf("add change_log fail.".$sql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        
        /*
        $sql="update mall_member set agency_money=agency_money+$m,member_points=member_points+$points where member_id=$uid";
        if(!$this->conn->execute($sql)){
            $this->conn->completeTrans(false);
            $this->logger->error(sprintf("add money fail.".$sql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        $sql=add_points_log($uid,$points,'充值奖励',3);
           if(!$this->conn->execute($sql)){
           	$this->conn->completeTrans(false);
           	$this->logger->error(sprintf("log_record_error.".$sql));
           	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
           }
        */   
        $this->conn->completeTrans(true);
    }
     
    
    /**
     * 根据员工id获取员工信息
     * @param unknown $id
     */
    public  function  getWokerDetail($id){
    	$limit=conf('CAN_HAVE_MEMBER_NUM');
    	$sql="select 
    	a.member_name as mobile,
    	a.member_id,
    	a.member_truename,
    	a.store_id,
    	$limit as total_num,
    	b.thumbnail_image_url,
    	b.image_ver as pav ,
    	b.base_ver as piv,
    	c.shop_name,
    	count(d.member_id) as now_num
    		
    	from mall_member as a,
    	mall_user_version_info as b,
    	mall_shop as c,
    	mall_member as d
    	where
    	b.user_id=a.member_id and
    	c.shop_id=a.store_id and 
    	d.parent_id=$id and d.user_type_id=1
    	and a.member_id=$id	
    	";
    	    	
    	$result=$this->conn->getRow($sql);
    	$result['total_money']=$this->getSaleMoney($id);
    	if(!$result){
    		return  false;
    	}
    	return $result;
    }
    
    /**
     * 根据id获取员工的销售记录
     * 按年按月
     */
    public  function  getSaleMoney($id,$condition=null){
    	if(!is_null($condition)){
    		$str=$condition;
    	}else{
    		$str='';
    	}
    	$sql="select  IFNULL(sum(order_amount),0)as total_money from view_sale_log
    	where parent_id=$id ".$str;
    	
    	
    	$result=$this->conn->getOne($sql);
    	return  $result;
    	
    }
    
    /**
     * 查询一个店的销售冠军的名字和头像可以设置某年某月某天
     */
    
    public  function  champion($sid,$condition=null){
    	if(!is_null($condition)){
    		$str=$condition;
    	}else{
    		$str='';
    	}
    	$conn=$this->conn;
    	$sql="SELECT  IFNULL(sum(order_amount),0)as total_money,parent_id as member_id from view_sale_log
    	where store_id=$sid ".$str." group by member_id ORDER BY total_money desc limit 1";
    	$user=$conn->getRow($sql);
    	if(!$user){
    		return  false;
    	}
    	$sql2="
    	      select a.member_id as jmember_id,
    	       a.member_truename as jmember_truename,
    		   b.thumbnail_image_url as jthumbnail_image_url,
    	       b.image_ver as jpav ,
               b.base_ver as jpiv
               from mall_member as a,
    	       mall_user_version_info as b
    	       where
    	       b.user_id=a.member_id and a.member_id =".$user['member_id'];
    		 	
    	return  $conn->getRow($sql2);
    }
    /**
     * 员工取得待处理的业务列表
     * 1充值列表，2退款列表，3销售列表。4退货列表，5新会员列表
     * 
     */
    public function getTaskList($user,$params){
    	$page=$params['page']<=0?1:$params['page'];
    	$limit=($page - 1) * 10;
    
    	if($params['type']==1){
    		$sql="select p.pdr_id,FROM_UNIXTIME(p.pdr_add_time, '%Y-%m-%d %H:%i:%S') manage_time,p.manage_state,m.member_id,m.member_name mobile,m.member_truename,v.image_ver pav,v.base_ver piv,v.thumbnail_image_url from mall_pd_recharge_tmp p
					left join mall_member m on p.pdr_member_id=m.member_id
					left join mall_user_version_info v on  p.pdr_member_id=v.user_id
					where worker_id =".$user['member_id']." 
    					order by pdr_id desc limit $limit,10";
    		$res=$this->conn->getAll($sql);
    	}elseif ($params['type']==2){
    		$sql="select r.id,r.manage_state,r.member_id,r.create_time manage_time,m.member_id,m.member_name mobile,m.member_truename,v.image_ver pav,v.base_ver piv,v.thumbnail_image_url from mall_refund_apply r
					left join mall_member m on r.member_id=m.member_id
					left join mall_user_version_info v on  r.member_id=v.user_id
					where worker_id =".$user['member_id']." and  r.manage_state>=1
    					order by id desc limit $limit,10";
    		$res=$this->conn->getAll($sql);
    		
    	}elseif ($params['type']==3 || $params['type']==4){

    		
    		if($params['type']==3){//
    			$state='40,50';
    			$sql="select o.order_id,o.order_state,o.take_time manage_time,o.buyer_id,m.member_id,m.member_name mobile,m.member_truename,v.image_ver pav,v.base_ver piv,v.thumbnail_image_url from mall_order o
					left join mall_member m on o.buyer_id=m.member_id
					left join mall_user_version_info v on  o.buyer_id=v.user_id
					where buyer_id in (select member_id from mall_member where user_type_id=1 and parent_id=".$user['member_id'].") and o.order_state in($state)
    								order by order_id desc limit $limit,10";
    		}elseif($params['type']==4){
    			$state='0,70';
    			$sql="select o.order_id,o.order_state,o.return_time manage_time,o.buyer_id,m.member_id,m.member_name mobile,m.member_truename,v.image_ver pav,v.base_ver piv,v.thumbnail_image_url from mall_order o
					left join mall_member m on o.buyer_id=m.member_id
					left join mall_user_version_info v on  o.buyer_id=v.user_id
					where buyer_id in (select member_id from mall_member where user_type_id=1 and parent_id=".$user['member_id'].") and o.order_state in($state)
    								order by order_id desc limit $limit,10";
    		}
    		$res=$this->conn->getAll($sql);
    	}elseif ($params['type']==5){
    		$sql="select t.member_id,t.manage_state,t.member_truename,FROM_UNIXTIME(t.member_time, '%Y-%m-%d %H:%i:%S') manage_time,t.member_name mobile,t.member_id  from mall_member_tmp t
					where t.parent_id=".$user['member_id']." 
    					order by t.member_id desc limit $limit,10";
    		$res=$this->conn->getAll($sql);
    	}
    	return $res;
    }    
    
    
}