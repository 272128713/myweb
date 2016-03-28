<?php
use Org\Util\ArrayList;
class ShopownerModel extends Model{
	/**
	 * 取得店铺基本信息
	 */
	public  function getShopBaseInfo($id){
		$sql="select shop_id,shop_name,shop_address,shop_money,shop_member_num,shop_woker_num from mall_shop where shop_id=$id";
		return  $this->conn->getRow($sql);
	}
	
	/**
	 * 添加员工
	 */
	public  function  addWorker($user,$params){
		$pid=$user['member_id'];
		$this->conn->startTrans();
		$times=time();
		$sql="insert into mall_member (user_type_id,parent_id,member_name,store_id,member_time,member_truename,card_id,member_sex)
		VALUES(2,".$user['parent_id'].",".$params['mobile'].",".$user['store_id'].",$times,'".$params['user_name']."','".$params['card_num']."','".$params['sex']."')";
		//print_r($sql);exit;
		if(!$this->conn->execute($sql)){
			$this->conn->completeTrans(false);
			$this->logger->error(sprintf("add member fail.".$sql));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		$uid=$this->conn->getInsertID();
		
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
		//店铺员工数
		$sql="select shop_woker_num from mall_shop WHERE  shop_id=".$user['store_id'];
		$oldnum=$this->conn->getOne($sql);
		$new_num=$oldnum+1;
		$sql="update mall_shop set shop_woker_num=$new_num WHERE shop_id=". $user['store_id'];
		//更新员工数量
		if(!$this->conn->execute($sql)){
			$this->conn->completeTrans(false);
			$this->logger->error(sprintf("update num fail.".$sql));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
	
		$this->conn->completeTrans(true);
	
	
	}	
	/**
	 * 确认充值
	 */
	public  function rechargeConfirm($user,$params){
		if($params['type']==1){//确认
			
			$this->conn->startTrans ();
			
			$sql="select * from mall_pd_recharge_tmp where pdr_id=".$params['oid'];
			$res=$this->conn->getRow($sql);
			
			$wid=$user['member_id'];
			$nter=$this->conn->getOne("select value from mall_shop_config where name='RECHARGE_REWARD'");
			$points=0;
			if($res['pdr_type']==2){
				$inter=C('ntegral');
				$points+=$res['pdr_amount']/$inter*$nter;
			}
			
			$sql="update mall_member set agency_money=agency_money+".$res['pdr_amount'].",member_points=member_points+$points where member_id=".$res['pdr_member_id'];
			$this->conn->execute($sql);

			$sql=add_points_log($res['pdr_member_id'],$points,'充值奖励',3);
			$this->conn->execute($sql);
			
			$sql="insert into mall_pd_recharge (worker_id,pdr_member_id,pdr_amount,pdr_payment_name,pdr_add_time,giver_money,resource_id)
			VALUES(".$res['worker_id'].",".$res['pdr_member_id'].",".$res['pdr_amount'].",'个人充值',".time().",".$res['giver_money'].",0)";
			$this->conn->execute($sql);
			
			$sql="update mall_pd_recharge_tmp set manage_member=".$user['member_id'].",manage_state=1,manage_time=Now() WHERE pdr_id=".$params['oid'];
			//print_r($sql);exit;
			$this->conn->execute($sql);			
			
// 			$dsql='DELETE FROM mall_pd_recharge_tmp WHERE pdr_id='.$params['oid'];
// 			$this->conn->execute($dsql);
			
			if ($this->conn->hasFailedTrans ()) {
				$this->conn->completeTrans ( false );
				return false;
			} else {
				$this->conn->completeTrans ( true );
				return true;
			}
		}else{//待确认
			$sql="select m.member_truename,m.member_name,m.member_address,v.image_ver pav,v.base_ver piv,t.pdr_amount,t.pdr_type,t.giver_money,t.pdr_id,t.pdr_add_time create_time,t.worker_id worker_name from mall_pd_recharge_tmp t 
				left join mall_member m on t.pdr_member_id=m.member_id 
				left join mall_user_version_info v on t.pdr_member_id=v.user_id 
				where pdr_id=".$params['oid'];
			$res=$this->conn->getRow($sql);
			$wsql="select member_truename from mall_member where member_id=".$res['worker_name'];
			$res['worker_name']=$this->conn->getOne($wsql);
			$res['create_time']=date('Y-m-d H:i:m',$res['create_time']);
			$res['pdr_type']=$res['pdr_type']>1?'刷卡充值':'现金充值';
			$res['giver_money']=$res['giver_money']>0?'获赠'.$res['giver_money'].'积分':'无';			
			return $res;		
		}

	}	
	/**
	 * 添加会员确认
	 */
	public  function addMemberConfirm($user,$params){
		if($params['type']==1){//确认
			$this->conn->startTrans();
			
			$sql="select * from mall_member_tmp where member_id=".$params['mid'];
			$res=$this->conn->getRow($sql);
				
			$isql="insert into mall_member (user_type_id,store_id,parent_id,member_name,disease,member_truename,member_provinceid,member_cityid,member_areaid,member_sex,card_id,member_level,agency_money,d_address,p_address,member_address,agency_id,member_state,member_birthday,member_time)
					 values(".$res['user_type_id'].",".$res['store_id'].",".$res['parent_id'].",'".$res['member_name']."','".$res['disease']."','".$res['member_truename']."',".$res['member_provinceid'].",".$res['member_cityid'].",".$res['member_areaid'].",'".$res['member_sex']."','".$res['card_id']."',".$res['member_level'].",".$res['agency_money'].",'".$res['d_address']."','".$res['p_address']."','".$res['member_address']."',".$res['agency_id'].",".$res['member_state'].",'".$res['member_birthday']."','".$res['member_time']."')";					
			$this->conn->execute($isql);
			$uid=$this->conn->getInsertID();
			// 			$dsql='DELETE FROM mall_member_tmp WHERE member_id='.$params['mid'];
			// 			$this->conn->execute($dsql);
			//	print_r($isql);exit;						
			//增加session
			$sql="insert into mall_user_session_info (user_id) VALUES($uid)";
			$this->conn->execute($sql);
			//个人信息版本
			$sql="insert into mall_user_version_info (user_id) VALUES($uid)";
			$this->conn->execute($sql);
			//店铺会员数
			$sql="select shop_member_num from mall_shop WHERE  shop_id=".$res['store_id'];
			$oldnum=$this->conn->getOne($sql);
			$new_num=$oldnum+1;
			$sql="update mall_shop set shop_member_num=$new_num WHERE shop_id=". $res['store_id'];
			//更新员工数量
			$this->conn->execute($sql);
			
			$sql="update mall_member_tmp set manage_member=".$user['member_id'].",manage_state=1,manage_time=Now() WHERE member_id=".$params['mid'];
			//print_r($sql);exit;
			$this->conn->execute($sql);
			if ($this->conn->hasFailedTrans ()) {
				$this->conn->completeTrans ( false );
				return false;
			} else {
				$this->conn->completeTrans ( true );
				return true;
			}
			
				
		}else{//待确认
			$sql="select member_id,member_level,member_truename,member_name,member_sex,card_id,member_address,disease,member_time,parent_id worker_name,manage_state,manage_time,manage_member from mall_member_tmp where member_id=".$params['mid'];
			$res=$this->conn->getRow($sql);

			$res['member_level']=$res['member_level']?'手机会员':'非手机会员';
			$res['member_time']=date('Y-m-d H:i:m',$res['member_time']);			
			$wsql="select member_truename from mall_member where member_id=".$res['worker_name'];			
			$res['worker_name']=$this->conn->getOne($wsql);
			return $res;
		}
	
	}	
	/**
	 * 店长取得待处理的业务列表
	 * 1充值列表，2退款列表，3销售列表。4退货列表，5新会员列表
	 */
	public function getTaskList($user,$params){
		$page=$params['page']<=0?1:$params['page'];		
		$limit=($page - 1) * 10;
		
		if($params['type']==1){
			$sql="select p.pdr_id,p.manage_time,p.manage_state,m.member_id,m.member_name mobile,m.member_truename,v.image_ver pav,v.base_ver piv,v.thumbnail_image_url from mall_pd_recharge_tmp p 
					left join mall_member m on p.pdr_member_id=m.member_id 
					left join mall_user_version_info v on  p.pdr_member_id=v.user_id
					where worker_id in (select member_id from mall_member where user_type_id=2 and store_id=".$user['store_id'].") and p.manage_state=1  
					 order by pdr_id desc limit $limit,10";
			$res=$this->conn->getAll($sql);		
		}elseif ($params['type']==2){
			$sql="select r.id,r.manage_state,r.member_id,r.manage_time,m.member_id,m.member_name mobile,m.member_truename,v.image_ver pav,v.base_ver piv,v.thumbnail_image_url from mall_refund_apply r
					left join mall_member m on r.member_id=m.member_id
					left join mall_user_version_info v on  r.member_id=v.user_id
					where worker_id in (select member_id from mall_member where user_type_id=2 and store_id=".$user['store_id'].") and r.manage_state=2 
								order by id desc limit $limit,10";
			$res=$this->conn->getAll($sql);			
		}elseif ($params['type']==3 || $params['type']==4){
			$state=$params['type']==3?40:0;
			$sql="select o.order_id,o.order_state,o.take_manage_time,o.return_manage_time,o.buyer_id,m.member_id,m.member_name mobile,m.member_truename,v.image_ver pav,v.base_ver piv,v.thumbnail_image_url from mall_order o
					left join mall_member m on o.buyer_id=m.member_id
					left join mall_user_version_info v on  o.buyer_id=v.user_id
					where buyer_id in (select member_id from mall_member where user_type_id=1 and store_id=".$user['store_id'].") and o.order_state=$state
					order by order_id desc limit $limit,10";
			$res=$this->conn->getAll($sql);						
		}elseif ($params['type']==5){
			$sql="select t.member_id,t.manage_state,t.member_truename,t.manage_time,t.member_name mobile,t.member_id  from mall_member_tmp t
					where t.store_id=".$user['store_id']." and t.manage_state=1 
					order by t.member_id desc limit $limit,10";
			$res=$this->conn->getAll($sql);			
		}
		return $res;
	}
	/**
	 * 提货确认
	 */
	public  function pickGoodsConfirm($user,$params){
			$this->conn->startTrans();			
			$sql="select o.*,m.store_id,s.agency_id,m.parent_id from mall_order o
					left join mall_member m on o.buyer_id=m.member_id
					left join mall_shop s on m.store_id=s.shop_id 
					where order_id=".$params['oid'];
			
			$res=$this->conn->getRow($sql);
			if($res['order_state']==40){
				ErrCode::echoJson(0,'订单已经确认');
			}

			$sql2="update  mall_order set order_state='40',finnshed_time=UNIX_TIMESTAMP()  where order_id=".$params['oid'];
			$this->conn->execute($sql2);
				
			$sql3="update mall_shop set shop_money=shop_money+".$res['order_amount']." where agency_id=".$res['agency_id']." and shop_id=".$res['store_id'];
			$this->conn->execute($sql3);
			
			
			$sql4="update mall_member set agency_money=agency_money+".$res['order_amount']." where member_id=".$res['agency_id'];
			$this->conn->execute($sql4);

			
			$sql5="insert into mall_pd_recharge (worker_id,pdr_member_id,pdr_amount,pdr_payment_name,pdr_add_time,resource_id)
			values(".$res['parent_id'].",".$res['agency_id'].",".$res['order_amount'].",'购买商品',UNIX_TIMESTAMP(),".$params['oid'].")";
			$this->conn->execute($sql5);
			
			if ($this->conn->hasFailedTrans ()) {
				$this->conn->completeTrans ( false );
				return false;
			} else {
				$this->conn->completeTrans ( true );
				return true;
			}					
	}
/**
 * 
 * 退货确认
 */
	public function returnGoodsConfirm($user,$params){
		
		$this->conn->startTrans();
		
		$sql="select o.*,m.store_id,s.agency_id,m.parent_id from mall_order o
			left join mall_member m on o.buyer_id=m.member_id
			left join mall_shop s on m.store_id=s.shop_id 
			where order_id=".$params['oid'];
		$o_info=$this->conn->getRow($sql);
		
		
		//判断订单状态是否可退款
		if(!in_array($o_info['order_state'], array(20,40,50))){
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}

		//更改订单状态
		$sql2="update  mall_order set order_state=0 where order_id=".$params['oid'];
		$this->conn->execute($sql2);
		
		$sql="update mall_member set agency_money=agency_money+".$o_info['order_amount']."  where member_id=".$o_info['buyer_id'];
		$this->conn->execute($sql);
		
		if($o_info['order_state']==20){

		}else{				
			$sql3="update mall_shop set shop_money=shop_money-".$o_info['order_amount']." where  shop_id=".$o_info['store_id'];
			$this->conn->execute($sql3);
			
			$sqlnn="update mall_member set agency_money=agency_money-".$o_info['order_amount']." where member_id=".$o_info['agency_id'];
			$this->conn->execute($sqlnn);
			
			$sql5="insert into mall_pd_recharge (worker_id,pdr_member_id,pdr_amount,pdr_payment_name,pdr_add_time,resource_id)
			values(".$o_info['parent_id'].",".$o_info['agency_id'].",'-".$o_info['order_amount']."','退款',UNIX_TIMESTAMP(),'".$params['oid']."')";
			$this->conn->execute($sql5);
			
			$sql="insert into mall_pd_recharge (worker_id,pdr_member_id,pdr_amount,pdr_payment_name,pdr_add_time,resource_id)
			values(".$o_info['parent_id'].",".$o_info['buyer_id'].",'".$o_info['order_amount']."','退款',UNIX_TIMESTAMP(),'".$params['oid']."')";
			$this->conn->execute($sql);
		}
		
		if ($this->conn->hasFailedTrans ()) {
			$this->conn->completeTrans ( false );
			return false;
		} else {
			$this->conn->completeTrans ( true );
			return true;
		}
	}

	/**
	 * 订单详情
	 */
	 function getOrderDetial($oid,$user){
		$sql="select o.bunding_id,o.order_type,o.use_points,o.order_id,o.order_amount,o.add_time,o.take_time,m.store_id,m.member_truename,m.member_name,m.member_address,v.image_ver pav,v.base_ver piv,v.thumbnail_image_url,o.take_manage_member,
				o.take_manage_time,o.return_woker_id,o.return_manage_member,o.return_manage_time,o.return_time,o.mreturn_time,o.order_code,m.parent_id  
					from mall_order o
					left join mall_member m on o.buyer_id=m.member_id
					left join mall_user_version_info v on  o.buyer_id=v.user_id
					where order_id=".$oid;
		$res=$this->conn->getRow($sql);
		

			
		$sql="select o.goods_name,o.goods_price,o.goods_image,g.goods_marketprice,g.points from mall_order_goods o
					left join mall_goods g  on o.goods_id=g.goods_id
					where order_id=".$oid;
		$res['goods_list']=$this->conn->getAll($sql);
		
		foreach ($res['goods_list'] as &$val){
			$market_amount+=$val['goods_marketprice'];
			$pay_amount+=$val['goods_pay_price'];			
			$val['points']=sprintf('%.2f',$val['points']*C('ntegral'));
			$val['goods_image']=str_replace('.','_60.', $val['goods_image']);
		}
		//套餐信息，
		if($res['bunding_id']){
			$sql='select bl_name goods_name,bl_old_price goods_marketprice,bl_discount_price goods_price,bunding_img goods_image from mall_p_bundling where bl_id='.$res['bunding_id'];
			$b_res=$this->conn->getRow($sql);
			$res['goods_list'][count($res['goods_list'])]=$b_res;
		}
		$res['goods_list']=array_reverse($res['goods_list']);
		$res['tip']='现金减免￥'.(sprintf('%.2f',$market_amount-$pay_amount));
		if($res['use_points']>0){
			$res['tip'].='，'.$res['use_points'].'积分抵扣￥'.(sprintf('%.2f',$res['use_points']*C('ntegral'))).'元现金';
		}
		
		$res['take_manage_member']=$this->conn->getOne('select member_truename from mall_member where member_id='.$res['take_manage_member']);
		$res['return_woker_id']=$this->conn->getOne('select member_truename from mall_member where member_id='.$res['return_woker_id']);
		$res['return_manage_member']=$this->conn->getOne('select member_truename from mall_member where member_id='.$res['return_manage_member']);
		$res['worker_name']=$this->conn->getOne('select member_truename from mall_member where member_id='.$res['parent_id']);
		$res['shop_name']=$this->conn->getOne('select shop_name from mall_shop where shop_id='.$res['store_id']);
		
		return $res;
	}
	/**
	 * 店长退款确认
	 */
	function refundConfirm($user,$params){
		//提交确认
		if($params['type']==1){
			$this->conn->startTrans();
			
			$sql="select r.*,m.end_time from mall_refund_apply r 
					left join mall_member m on r.member_id=m.member_id  
					where id=".$params['id'];
			$o_info=$this->conn->getRow($sql);
			
			$sql="update mall_refund_apply set end_time=".$o_info['end_time'].",money=".$params['money'].",manage_time=NOW(),manage_member=".$user['member_id'].",manage_state=2 where id=".$params['id'];
			$this->conn->execute($sql);
			
			$sql="update mall_member set agency_money=agency_money-".$params['money']."  where member_id=".$o_info['member_id'];
			$this->conn->execute($sql);
			
			if ($this->conn->hasFailedTrans ()) {
				$this->conn->completeTrans ( false );
				return false;
			} else {
				$this->conn->completeTrans ( true );
				return true;
			}			
		}else{
			$sql="select r.*,m.member_truename,m.member_name,m.member_address,m.agency_money,v.image_ver pav,v.base_ver piv,v.thumbnail_image_url from mall_refund_apply r
					 left join mall_member m on r.member_id=m.member_id
					 left join mall_user_version_info v on r.member_id=v.user_id 
					 where id=".$params['id'];
			$o_info=$this->conn->getRow($sql);			
		}
		$sql="select member_truename from mall_member where member_id=".$o_info['worker_id'];
		$o_info['worker_name']=$this->conn->getOne($sql);
		$o_info['manage_member']=$this->conn->getOne('select member_truename from mall_member where member_id='.$o_info['manage_member']);
		$o_info['can_return']=date('Y-m-d H:i:m')>=$o_info['end_time']?1:0;
		return $o_info;		
	}
	
	
	
	
	
}