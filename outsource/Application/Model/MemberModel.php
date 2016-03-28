<?php
/**
 * 会员模型
 * @author Administrator
 *
 */
class MemberModel extends Model
{    
	/**
	 * 查询语句
	 * @var unknown
	 */
	private $sql;
	
	/**
	 * 查询会员基本信息
	 */
	public function one($id,$arr=null){
		$sql="select a.member_name as mobile,
			  a.disease,a.member_id,
			  a.member_truename,
			  a.member_birthday,
			  a.member_sex,a.member_address,
			  a.member_level,a.member_points,
			  a.parent_id,
			  a.agency_money,
			  b.thumbnail_image_url,
			  b.image_ver as pav ,
			  b.base_ver as piv
			
			  from mall_member as a,
			  mall_user_version_info as b  
			  where
		      b.user_id=a.member_id 
			  and a.member_id=$id 
			  					 			 			  
		";
		$use= $this->conn->getRow($sql);
		$result=  array_merge($use,$this->getUser($id),$this->total($id));
		$result['blong_log']=$this->getBlongs($id);
		return  $result ? $result :false;
		
		
	}
	
	/**
	 * 返回消费累计值
	 */
	public function  getUser($id){
		$sql=" select  IFNULL(sum(order_amount),0)as use_money,
			          IFNULL(sum(use_points),0) as use_points from mall_order
			  where buyer_id=$id  and order_state=40";
		 $use= $this->conn->getRow($sql);
		 if($use['use_money']>0) $use['use_money']='-'.$use['use_money'];
		 if($use['use_points']>0) $use['use_points']='-'.$use['use_points'];
		 return $use;
	}
	
	/**
	 * 返回总共的充值额度
	 */
	public  function  total($id){
		$sql=" select  IFNULL(sum(pdr_amount),0)	 as pdr_amount from mall_pd_recharge
			  where pdr_member_id=$id ";
		$use= $this->conn->getRow($sql);
		return $use;
		
	}
	
	/**
	 * 返回一个会员头像和版本号
	 */
	public  function getImg($id){
		return  M('User')->GetAvatarImgUrl($id);
	}
	
	/**
	 * 查询会员所属记录
	 * @param unknown $id
	 */
	public function  getBlongs($id){
		$sql="select a.create_time,ifnull(b.member_truename,'') as boss_name, c.member_truename as worker_name,concat(
				IF(a.opdate_id=0,'','店长'),
				IF(a.opdate_id=0,'',b.member_truename),
				IF(a.opdate_id=0,'成为会员','分配') 
               ) as content from mall_member_blong as a 
       
        LEFT  JOIN  mall_member as b ON b.member_id=a.opdate_id 
        LEFT  JOIN  mall_member as c ON c.member_id=a.worker_id 
         where a.member_id=$id";
		$use= $this->conn->getAll($sql);
		return $use;
	}
	
	/**
	 * 分页返回会员列表
	 * @param number $page
	 * @param value  $value 
	 */
	public  function  getList($page=0,$value=null,$sid=null,$parent_id=null){
		$limit=C('page_num');
		$page=page_oprate($page);
		$where='';
		if(!is_null($value) && !is_null($sid)) {
			$where="and (a.member_truename LIKE '%$value%' or a.member_name LIKE '%$value%') and
			        a.store_id=$sid ";
		}else if(is_null($value) && !is_null($sid)){
			$where="and
			a.store_id=$sid ";
		}else if(!is_null($value) && is_null($sid)){
			$where="and a.parent_id=$parent_id and (a.member_truename LIKE '%$value%' or a.member_name LIKE '%$value%')";
		}else{
			$where="and a.parent_id=$parent_id";
		}
		$sql="select 
		a.member_id,
		a.member_truename,	
		a.member_address,
		a.member_name,		
		a.member_level,
		b.thumbnail_image_url,
		b.image_ver as pav ,
		b.base_ver as piv,
		c.member_truename as worker_name			
		from mall_member as a,
		mall_user_version_info as b,
		mall_member as c
		where
		b.user_id=a.member_id and
		c.member_id=a.parent_id and
		a.user_type_id=1 ".$where."
		limit $page,$limit 	
		";
	   
		$use= $this->conn->getAll($sql);		
		return  $use;
		
	}
	
}