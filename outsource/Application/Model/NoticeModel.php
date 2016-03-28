<?php
class NoticeModel extends Model{
	/**
	 * 发布公告
	 */
	public function addNotice($user,$receive_type,$receive_store,$content){		

		if($receive_type==0){//会员
			$user_type='1';	
		}elseif($receive_type==1){//员工
			$user_type='2';	
		}elseif($receive_type==2){//员工和会员
			$user_type='1,2';	
		}
		//通告接收人
		$ac_sql="select member_id from mall_member where user_type_id in ($user_type) and store_id in($receive_store)";
		$accepters=$this->conn->getAll($ac_sql);
		
			
		//添加通告表
		 $sql="insert into mall_notice (content,send_id,receive_type,receive_store,create_time)
                 VALUES('$content',".$user['user_id'].",$receive_type,'$receive_store',now())";	
		 
		 $this->conn->execute($sql);
		 $id=$this->conn->getInsertID();
		 
		 $mec_str=$user['member_truename'].':'.mb_substr($content,0,20,'utf-8');
		 $accept_arr=array();	
		 $time=time();
		 foreach ($accepters as $val){
		 	$value_str.="(".$val['member_id'].",'$mec_str',$time,5,$id,''),";
		 	$accept_arr[]=$val['member_id'];
		 }
		
		 //添加消息提醒
		 $msg_sql="insert into mall_msg (member_id,msg_content,msg_time,msg_type,child_id,shop_id)VALUES ".trim($value_str,',');
		 $this->conn->execute($msg_sql);
		 
		 //发送mec消息
		 include_once(ROOT_PATH . "/common/MecManager.php");
		 $msgObj = array("type" => 'NTM',
		 		"src"  => $user['user_id'],
		 		"srcm" => $user['member_name'],
		 		"content"=>urlencode($mec_str),
		 		"time" =>time(),
		 		"msg_id"=>$id
		 );
		 
		 //var_dump($msgObj);exit;
		 
		 $ac=M('User')->getUserInfoByUid(implode(',',$accept_arr));
		 $mecManager = new MecManager($user['user_id'],$msgObj,$ac);
		 $staus=$mecManager->sendMessage();
		 
		 
		 if($id){//成功		 	
		 	return $id;
		 }else{
		 	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		 }
	}
	/**
	 * 获取通告详情
	 */
	public function getNoticeDetial(){
		$id=$_POST['id'];
		$sql="select content,receive_type,receive_store,create_time from mall_notice where id=$id";
		$info=$this->conn->getRow($sql);	
		$typeArr=array(
				0=>'会员',
				1=>'员工',
				2=>'全部',
			);		
		$info['receive_type']=$typeArr[$info['receive_type']];		
		$store=explode(',', $info['receive_store']);
		foreach ($store as $val){
			$shop_sql="select shop_name from mall_shop where shop_id=$val";
			$store_name[]=$this->conn->getOne($shop_sql);
		}
		$info['receive_store']=$store_name;
		return $info; 
	}
	/**
	 * 员工或会员获取接收到的通告列表
	 */
	public function getNoteiceList($uid,$page){
		$page=$page<0?1:$page;
		$limit=($page - 1) * 10;
		$sql="select store_id,user_type_id from mall_member where member_id=$uid";
		$user_type=$this->conn->getRow($sql);		

		if($user_type['user_type_id'] == 1){//会员
				
		}elseif($user_type['user_type_id'] == 2){//员工
			
		}elseif($user_type['user_type_id'] == 3 ||  $user_type['user_type_id'] == 4){//经销商
			
		}
		
		
		
		$receive_type=$user_type['user_type_id']=1?'0,2':'1,2';
		
		$sql="select * from mall_notice  where ".$user_type['store_id']." in (receive_store) and receive_type in($receive_type) order by create_time desc limit $limit,10";
		$result=$this->conn->getAll($sql);
		
		return $result;
	}
	/**
	 * 经销商或店长发送的通告列表
	 * 
	 */	
	public function getSendNoticeList($uid,$page){
		$page=$page<=0?1:$page;
		$limit=($page-1) * 10;
		$sql="select member_truename,store_id,user_type_id from mall_member where member_id=$uid";
		$user_type=$this->conn->getRow($sql);
						
		if($user_type['user_type_id'] == 4){//店长
			$sql="select shop_name from mall_shop where shop_id=".$user_type['store_id'];
			$res['shop_name']=$this->conn->getOne($sql);
		}		
		$norice_sql="select * from mall_notice where send_id=$uid order by create_time desc limit $limit,10";		
		$res['list']=$this->conn->getAll($norice_sql);
		
		//print_r($norice_sql);exit;
		
		$count_sql="select count(*) from mall_notice where send_id=$uid";
		$res['count']=$this->conn->getOne($count_sql);
		
		foreach ($res['list'] as &$val){
			$val['content']=mb_substr($val['content'],0,10,'utf-8');
			$typeArr=array(
				0=>'会员',
				1=>'员工',
				2=>'全部',
			);
			$val['receive_type']=$typeArr[$val['receive_type']];
			
			if($user_type['user_type_id'] == 3){
				$val['tips']=count(explode(',', $val['receive_store'])).'家门店';
			}elseif($user_type['user_type_id'] == 4){
				$val['tips']=$user_type['member_truename'];
			}
		}
		//print_r($res);exit;
		
		return $res;
	}
	

	
	
}