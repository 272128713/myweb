<?php
class UserModel extends Model{
	/**
	 * 检查用户session
	 * @param unknown $session
	 * @return unknown|boolean
	 */
	public function checkSession($session){
		//首先从cache取，若cache不存在则从DB中获取并存入到cache
		$config = new Config ( );
		$server = $config->getLocalConfig("sessionmemcacheserver");
		$serverRow = explode(":", $server);
		$mem = new MemcacheManager($serverRow[0],$serverRow[1]);
		$mem = $mem->getMemcache();
		$memResult =0;
		if(!$mem) {
			$this->logger->error(sprintf("Filter connect fail. server info: %s:%s",$serverRow[0],$serverRow[1]));
			$memResult = -1;
		}else{
			$mem_res = $mem->get($session);
			if ($mem_res!=""){
				$mem->close();
				return $mem_res;
			}
		}
		$crc_session = abs(crc32($session));
		//$sql = "SELECT a.user_id,a.client_type,a.mid,a.push_service_type,a.session,a.mec_ip,a.mec_port,a.lps_ip,a.lps_port,b.user_type_id as role,b.mobile,b.hospital,b.user_name as rn, b.authentication as auth FROM user_session_info as a, user_base_info as b where a.user_id=b.user_id and a.session_hash = $crc_session and a.session='$session'";
		$sql = "SELECT a.user_id,a.client_type,a.mid,a.push_service_type,a.session,a.mec_ip,a.mec_port,a.lps_ip,a.lps_port
		        ,b.* FROM mall_user_session_info as a ,mall_member as b
				where  a.session='$session' and b.member_id=a.user_id";
		$rel = $this->conn->getRow($sql);
		if($rel){
			// 			if ($memResult==0){
			// 				$mem->set($session, $rel,0,432000);
			// 				$mem->close();
			// 			}
			return $rel;
		}else{
			return false;
		}
	}
	
	/**
	 * 绑定im和手机号
	 * @param unknown $user
	 * @param unknown $im
	 */
	public function updateIm($user,$im){
			$sql="update mall_member set im='$im' where member_name='$user'";
			return  $this->conn->execute($sql);
	}
	
	/**
	 * 根据id查询会员头像信息
	 */
	public function getVersion($user_id){
		$sql='select base_ver piv,image_ver pav,thumbnail_image_url from mall_user_version_info where user_id='.$user_id;
		return  $this->conn->getRow($sql);
		
	}
	
	/**
	 * 根据员工id返回健康圈人数
	 */
	public function getMemberNum($pid){
		$sql="select count(*) as member_num from mall_member where parent_id=$pid and user_type_id=1";
		return  $this->conn->getOne($sql)+1;
	}

    /*
     *根据UID返回用户对应的相关信息
     */
    public function getUserInfoByUid($uidlist){
        $condition = " a.user_id in (".$uidlist.") and a.mec_ip !='0.0.0.0'";
        $sql = "SELECT a.user_id,a.client_type,a.user_id as mid ,a.push_service_type,a.mec_ip,a.mec_port,a.lps_ip,a.lps_port,b.member_name as mobile FROM mall_user_session_info as a, mall_member as b where a.user_id=b.member_id and $condition";

        return $this->conn->getAll($sql);
    }

    /*
 	 * 更新获取消息的时间
 	*/
    public function updateLastGetDate($user_id){
        $time = time();// date("Y-m-d H:i:s");
        $updateSql = "update mall_user_session_info set last_get_msg_date='$time' where user_id='$user_id'";
        $this->conn->execute($updateSql);
    }

    /*优化注册流程*/
    public function userRegisterCheck($username){
        $sql = "select member_id from mall_member WHERE member_name= '$username'";
        $result=$this->conn->getRow($sql);
        if($result)
        {
            return false;
        }
        else
        {
            //可以注册
            return true;
        }
    }
    
    /*是否正审核*/
    public function userCheck($username){
    	$sql = "select member_id from mall_member_tmp WHERE member_name= '$username'";
    	$result=$this->conn->getRow($sql);
    	if($result)
    	{
    		return false;
    	}
    	else
    	{
    		//可以注册
    		return true;
    	}
    }
    /**
     * 查询头像
     */
    public function GetAvatarImgUrl($user_id){
        $GetImgUrlSql = "select source_image_url,thumbnail_image_url from mall_user_version_info where user_id=$user_id";
        $ImgUrls = $this->conn->getRow($GetImgUrlSql);
        if($ImgUrls == "")
            return false;
        return $ImgUrls;
    }

    /**
     * 更新个人基本信息
     */
    public function updatePersonalInfo($sql,$user_id){
        $this->conn->startTrans();
        $this->conn->execute($sql);

        $updateSql = "update mall_user_version_info set base_ver = base_ver + 1 where user_id='$user_id'";
        $this->conn->execute($updateSql);

        if($this->conn->hasFailedTrans()){
            $this->conn->completeTrans(false);
            return false;
        } else {
            $this->conn->completeTrans(true);
            return true;
        }
    }

    /**
     * 查询会员是否存在根据手机号
     */
    public  function  isMember($mobile){
        $uid=$this->conn->getOne("select member_id from mall_member where member_name='$mobile'");
        if($uid){
           return $uid;
        }else{
            ErrCode::echoErr(ErrCode::API_ERR_PHONE_NO_EXSIT,1);
        }
    }

    public  function  signIn($uid){
            $nows=date('Y-m-d H:i:s');
            $conn=$this->conn;
            $logger=$this->logger;
            if(date('d')=='01'){
                 $nums=1;
            }else{
                //查询昨天是否签到
                $sql="select month_nums from mall_sign_record
                     WHERE date_format(sign_time,'%Y-%m-%d')=date_sub(curdate(),interval 1 day) and member_id=$uid";
                $onum=$conn->getOne($sql);
                if($onum){
                    $nums=$onum+1;
                }else{
                    $nums=1;
                }
            }
            $sql="insert into mall_sign_record (member_id,sign_time,month_nums) VALUES($uid,'$nows',$nums)";
            if(!$conn->execute($sql)){
                $logger->error(sprintf("add signIn fail.".$sql));
                ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
            }
            $sql="select * from mall_sign_setting";
            $rule=$conn->getAll($sql);
            $award=0;
            $inter=C('ntegral');
            $conn->startTrans();
           foreach($rule as $v){

                if($v['sign_type']==0){
                    //连续签到
                    $sql="select * from mall_sign_record where date_format(sign_time,'%Y-%m')
                          =date_format(now(),'%Y-%m') and member_id=$uid";
                    $day=$v['day_num'];
                    $sqls="select count(*) as sign_num  from ($sql) as a where DATE_ADD(a.sign_time,INTERVAL  $day day)>=NOW()";
                    $sign_num=$conn->getOne($sqls);
                    if($sign_num==$day){
                    	$extend_id=$v['id'];
                    	//是否已经给了
                    	$sql="select member_id from mall_points_record where date_format(create_date,'%Y-%m')
                    	=date_format(now(),'%Y-%m') and member_id=$uid and type=1 and extend_id=$extend_id";
                    	
                    	if(!$this->conn->getOne($sql)){
                    		$award+=$v['reward'];
                    		$money=$v['reward'];
                    		$notice='本月连续签到'.$day.'天奖励';
                    		
                    		$sql="insert into mall_points_record (member_id,points,pdr_payment_name,create_date,extend_id)
                    		VALUES ($uid,$money,'$notice',now(),$extend_id)";
                    		if(!$conn->execute($sql)){
                    			$conn->completeTrans(false);
                    			$logger->error(sprintf("add log fail.".$sql));
                    			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
                    		}
                    	}
                       
                    }

                }else if($v['sign_type']==1){
                	
                    //本年累计
                    $sql="select count(*) as sign_num from mall_sign_record where date_format(sign_time,'%Y')
                          =date_format(now(),'%Y') and member_id=$uid";
                    $day=$v['day_num'];
                    $sign_num=$conn->getOne($sql);
                    if($sign_num==$day){
                        $award+=$v['reward'];
                        $money=$v['reward'];
                        $notice='本年累计签到'.$day.'天奖励';
                        $sql="insert into mall_points_record (member_id,points,pdr_payment_name,create_date)
                               VALUES ($uid,$money,'$notice',now())";
                        if(!$conn->execute($sql)){
                            $conn->completeTrans(false);
                            $logger->error(sprintf("add log fail.".$sql));
                            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
                        }
                    }

                }else{
                	//本月累计签到
                	$sql="select count(*) as sign_num from mall_sign_record where date_format(sign_time,'%Y-%m')
                	=date_format(now(),'%Y-%m') and member_id=$uid";
                	$day=$v['day_num'];
                	$sign_num=$conn->getOne($sql);
                	if($sign_num==$day){
                		$award+=$v['reward'];
                		$money=$v['reward'];
                		$notice='本月累计签到'.$day.'天奖励';
                		$sql="insert into mall_points_record (member_id,points,pdr_payment_name,create_date)
                               VALUES ($uid,$money,'$notice',now())";
                		if(!$conn->execute($sql)){
                			$conn->completeTrans(false);
                			$logger->error(sprintf("add log fail.".$sql));
                			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
                		}
                	}
                }

            }
            $trur=$award;
            $sql="update mall_member set member_points=member_points+$trur where member_id=$uid";
            if(!$conn->execute($sql)){
                $conn->completeTrans(false);
                $logger->error(sprintf("add mnoney fail.".$sql));
                ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
            }
            $conn->completeTrans(true);
            return $award;




    }

    /**
     * 购买产品
     */
    public  function buyGoods($user,$return,$p,$pg,$code,$cpoints){
        $store_id=$user['store_id'];
        $uid=$user['member_id'];
        $user_name=$user['member_name'];
        $order_code=create_guid($uid);
        $pid=$return['pakage_id'];
        $order_amount_old=$return['total_price']; //原价
              //使用积分
        if($p){

                $points=$user['member_points'];      //减去的价

                if($points>=$cpoints){
                    $use_points=$cpoints;
                    $order_amount=$order_amount_old-$cpoints*$code;
                }else{
                	$use_points=$points;
                    $order_amount=$order_amount_old-$points*$code;
                }

        }else{
            //不用积分
            $order_amount=$order_amount_old;
            $use_points=0;

        }

        //判断钱够吗
        if($order_amount>$user['agency_money']){
            ErrCode::echoErr(ErrCode::API_NOT_SUFFICIENT_FUNDS,1);
        }
        //$shops_id=$user['store_id'];
        $psid=$user['parent_id'];
        $sql="insert into mall_order(parent_id,order_code,order_state,order_amount_old,use_points,order_amount,buyer_id
                      ,store_id,add_time,buyer_name,bunding_id) VALUES ($psid,'$order_code','20','$order_amount_old','$use_points',
                      '$order_amount','$uid','$store_id',unix_timestamp(),'$user_name',$pid
                      )";

        //开启存储过程
        $logger=$this->logger;
        $conn=$this->conn;
        $conn->startTrans();
        if(!$conn->execute($sql)){
            $conn->completeTrans(false);
            $logger->error(sprintf("add order fail.".$sql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        $order_id=$conn->getInsertID();


        //写商品订单表
        foreach($return['pakege_list'] as $v){
            //组合购买
            if($pg){
                $goods_id=$v['goods_id'];
                $goods_name=$v['goods_name'];
                $goods_price=$v['goods_price_old'];
                $goods_image=$v['img_url'];
                $goods_pay_price=$v['goods_price_now'];
                $sql="insert into mall_order_goods(order_id,goods_id,goods_name,goods_price,goods_image,goods_pay_price,store_id,buyer_id)
                    VALUES ($order_id,$goods_id,'$goods_name','$goods_price','$goods_image','$goods_pay_price',$store_id,$uid)";
                if(!$conn->execute($sql)){
                    $conn->completeTrans(false);
                    $logger->error(sprintf("add pakege_order_goods fail.".$sql));
                    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
                }
            }else{
                if($v['goods_id']==$return['goods_id']){
                    $goods_id=$v['goods_id'];
                    $goods_name=$v['goods_name'];
                    $goods_price=$v['goods_price_old'];
                    $goods_image=$v['img_url'];
                    $goods_pay_price=$order_amount;
                    $sql="insert into mall_order_goods(order_id,goods_id,goods_name,goods_price,goods_image,goods_pay_price,store_id,buyer_id)
                    VALUES ($order_id,$goods_id,'$goods_name','$goods_price','$goods_image','$goods_pay_price',$store_id,$uid)";
                    if(!$conn->execute($sql)){
                        $conn->completeTrans(false);
                        $logger->error(sprintf("add sign_order_goods fail.".$sql));
                        ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
                    }
                }
            }

        }
        //扣除钱和积分
        $sql="update mall_member set agency_money=agency_money-$order_amount,
              member_points=member_points-$use_points where member_id=$uid";

        if(!$conn->execute($sql)){
            $conn->completeTrans(false);
            $logger->error(sprintf("update personnal fail.".$sql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        $conn->completeTrans(true);
        return  $order_id;

    }

    /**
     * 抢红包
     */
    public  function  envelope($user,$eid){
        $uid=$user['member_id'];
        //是否已经抢过
        $sql="select id from mall_bonus_record where bonus_id=$eid and member_id=$uid";
        if($this->conn->getOne($sql)){
            ErrCode::echoErr(ErrCode::API_ENVELOPE_HAVAD,1);
        }
        $sql="select * from mall_bonus_record where bonus_id=$eid and member_id is  null";
        $conn=$this->conn;
        $bonus=$conn->getAll($sql);
        if(!$bonus){
            ErrCode::echoErr(ErrCode::API_ENVELOPE_EMPTY,1);
        }
        $logger=$this->logger;
        $oneNus=$bonus[mt_rand(0,count($bonus)-1)];
        $now=date("Y-m-d H:i:s");
        $sql="update mall_bonus_record set create_time='$now', member_id=$uid WHERE id=".$oneNus['id'];
        //开始写入数据
        $conn->startTrans();
        if(!$conn->execute($sql)){
            $conn->completeTrans(false);
            $logger->error(sprintf("update  bonus fail.".$sql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        $inter=C('ntegral');
        $m=$oneNus['money']/$inter;
        //更新钱数更换成积分
        $sql="update mall_member set member_points=member_points+".$m." WHERE member_id=".$uid;
        if(!$conn->execute($sql)){
            $conn->completeTrans(false);
            $logger->error(sprintf("update  money fail.".$sql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        
        
        $sql=add_points_log($uid,$m,'抢红包获得',2);
//         $sql="insert into mall_pd_recharge (pdr_member_id,pdr_amount,pdr_payment_name,pdr_add_time)
//                   VALUES($uid,'$m','抢红包获得',UNIX_TIMESTAMP())";
        if(!$this->conn->execute($sql)){
            $this->conn->completeTrans(false);
            $this->logger->error(sprintf("add change_log fail.".$sql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        $conn->completeTrans(true);
        return $m;


    }
   
}