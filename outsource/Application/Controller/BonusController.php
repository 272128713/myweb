<?php
/**
 * 红包
 * @author Administrator
 *
 */
class BonusController extends CommonController{
	public  function __construct()
	{
		parent::__construct();
		$this->conn=M()->conn;
		$this->Logger=Logger::getLogger(basename(__FILE__));
	
	}
	
	/**
	 * 发出和收到的红包金额
	 */
	public  function  BonusGave(){
		$logger = Logger::getLogger(basename(__FILE__));
		$this->logger=$logger;
		$params = array(array("ss",true));
		$params = Filter::paramCheckAndRetRes($_POST,$params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		$uid=$user['member_id'];
		$sql="SELECT COUNT(*) as num ,IFNULL(SUM(money),0) as money 
		      from mall_bonus_manage where member_id=$uid ";
		$return=$this->conn->getRow($sql);
		//
		if($user['user_type_id']==1){
			//还要返回收到的红包
			$sql="SELECT COUNT(*) as rnum ,IFNULL(SUM(money),0) as rmoney 
		      from mall_bonus_record where member_id=".$uid;
			$return=array_merge($return,$this->conn->getRow($sql));
			//最佳的次数
			$return['mnum']=$this->conn->getOne("SELECT COUNT(*) as mnum from bonus_have where member_id=$uid");
		    $return['points']=$user['member_points']; 
		}
		ErrCode::echoJson(1,'执行成功',$return);
	}	
	/**
	 * 发出或者收到的红包的列表
	 */   
	  public function bonusList(){
	  	$logger = Logger::getLogger(basename(__FILE__));
	  	$this->logger=$logger;
	  	$params = array(array("ss",true),array("page",false),array("type",true));
	  	$params = Filter::paramCheckAndRetRes($_POST,$params);
	  	if(!$params){
	  		$logger->error(sprintf("params is err. params is %s",v($_POST)));
	  		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
	  	}
	  	$user=$this->user;
	  	$uid=$user['member_id'];
	  	if(isset($params['page'])){
	  		$page=($params['page']-1)*($this->page);
	  	}else{
	  		$page=0;
	  	}
	  	$type=$params["type"];
	  	$sql="select 
	  			  a.id,
	  			  a.title,
	  			  a.type,
	  			  a.money,
	  			  c.member_truename,
	  			  c.member_id,
	  			  a.create_time,
	  			  a.num as total_num,
	  			  b.b as   now_num
	  			  from mall_bonus_manage as a,
	  				bonus_step1 as b,
	  			    mall_member as c
	  			  where a.id=b.bonus_id	and a.member_id=c.member_id and ";
	  	if($type==1){
	  	  //发出
	  	  $sql.="a.member_id=$uid";
	  				
	  	}else{
	  	  //收到
	  	  $sql.=" a.id in (select bonus_id as id from mall_bonus_record  where member_id=$uid) ";	
	  	}
	  	
	  	 $sql.=" order by a.create_time desc limit $page,$this->page";
	  	 
	  	 ErrCode::echoJson(1,'执行成功',$this->conn->getAll($sql));
	  	
	  }	
	  
	  /**
	   * 红包详情
	   */
	  public  function  bonusDetail(){
	  	$logger = Logger::getLogger(basename(__FILE__));
	  	$this->logger=$logger;
	  	$params = array(array("ss",true),array("page",false),array("type",true),array("bid",true));
	  	$params = Filter::paramCheckAndRetRes($_POST,$params);
	  	if(!$params){
	  		$logger->error(sprintf("params is err. params is %s",v($_POST)));
	  		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
	  	}
	  	$user=$this->user;
	  	$uid=$user['member_id'];
	  	if(isset($params['page'])){
	  		$page=($params['page']-1)*($this->page);
	  	}else{
	  		$page=0;
	  	}
	  	$type=$params['type'];
	  	$return=array();
	  	$muser=M('Member');
	  	$bid=$params['bid'];
	  	$row=$this->conn->getRow('select * from mall_bonus_manage where id='.$params['bid']);
	  	$return['member_id']=$row['member_id'];
	  	$return['title']=$row['title'];
	  	$return['type']=$row['type'];
	  	if($type==1){
	  		//发出去的红包
	  	     $return['member_truename']=$user['member_truename'];
	  	     $return['money']=$row['money'];
	  	     $iuid=$uid;
	  	     $return['num']=$row['num'];
	  	}else{
	  	    //收到的红包详情
	  		$return['member_truename']=$this->conn->getOne("select member_truename 
	  				                    from mall_member where member_id=".$row['member_id']);
	  		$bonus=$this->conn->getRow("select money,is_best 
	  				                    from mall_bonus_record 
	  				                    where member_id=".$uid." and bonus_id=$bid");
	  		$return['money']=$bonus['money'];
	  		$return['is_best']=$bonus['is_best'];
	  		$iuid=$row['member_id'];
	  	}
	  	$img=$muser->getImg($iuid);	  	
	  	$return=array_merge($return,array('thumbnail_image_url'=>$img['thumbnail_image_url']));
	  	//实现业务逻辑
    	$sql="select a.money,a.member_id as member_id ,a.is_best,a.create_time,b.member_truename,c.thumbnail_image_url,c.base_ver as piv,c.image_ver as pav
   			from mall_bonus_record  as a
   			LEFT JOIN mall_member as b On a.member_id=b.member_id
   			LEFT JOIN mall_user_version_info as c On a.member_id=c.user_id
   			where  a.bonus_id=$bid and a.member_id is not null
   			order by a.create_time desc
   			limit $page,$this->page";
   	     $result=M()->conn->getAll($sql);
   	     $return['record_list']=$result;
   	     ErrCode::echoJson(1,'执行成功',$return);
	  }
	  
	  /**
	   * 发红包
	   */
	  public  function  givaBonus(){
	  	$logger = Logger::getLogger(basename(__FILE__));
	  	include(ROOT_PATH . "/common/MecManager.php");
	  	include(ROOT_PATH . "/common/MMS_FileManager.php");
	  	$this->logger=$logger;
	  	$params = array(
	  			   array("ss",true),
	  			   array("points",true),
	  			   array("num",true),
	  			   array("title",true),
	  			   array("type",true),
	  			   array("id",true)
	  	 );
	  	$params = Filter::paramCheckAndRetRes($_POST,$params);
	  	if(!$params){
	  		$logger->error(sprintf("params is err. params is %s",v($_POST)));
	  		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
	  	}
	  	$user=$this->user;
	  	$uid=$user['member_id'];
	  	$pid=$user['parent_id'];
	  	//先计算是否符合要求
	  	if($user['user_type_id']==1){
	  		//判断积分够吗
	  		if($params['type']==1){
	  			$ttp=$params['points']*$params['num'];
	  		}else{
	  			$ttp=$params['points'];
	  		}
	  		if($user['member_points']<$ttp){
	  			ErrCode::echoErr(ErrCode::API_ERR_POINTS_IS_LOW,1);
	  		}
	  		$num=$this->conn
	  		 ->getOne("select count(*) from mall_member where user_type_id=1 and parent_id=$pid");
	  	}else{
	  		$num=$this->conn
	  		->getOne("select count(*) from mall_member where user_type_id=1 and agency_id=$uid");
	  	}
	  	
	  	$zk=C('ntegral');
	  	$int=0.1/C('ntegral');
	  	//计算所发积分是否满足要求
	  	//评价分配
	  	if($params['num']>$num){
	  		//比健康圈人数大不能发
	  		ErrCode::echoErr(ErrCode::API_ERR_NOT_HAVE_PEOPLE,1);
	  	}
	  	//红包数组
	  	$data=array(
	  			'title'=>$params['title'],
	  			'num'=>$params['num'],
	  			'create_time'=>date('Y-m-d H:i:s'),
	  			'status'=>1,
	  			'member_id'=>$uid,
	  			'send_id'=>$uid
	  	);
	  	if($params['type']==1){
	  		if($params['points']<$int){
	  			//金额不符合要求
	  			ErrCode::echoErr(ErrCode::API_ERR_BONUS_IS_LOW,1);
	  		}
	  		//符合了要求开发
	  		$data['type']=0;
	  		$data['money']=$params['num']*$params['points']*$zk;
	  		//要发的数组
	  		$arr=array_fill(0,$params['num'],$params['points']);
	  		
	  	}else if($params['type']==2){
	  	    //随机分配
		  	if($params['points']/$params['num']<$int){
		  		//不符合要求
		  		ErrCode::echoErr(ErrCode::API_ERR_BONUS_IS_LOW,1);
		  	}
		  	$data['type']=1;
		  	$data['money']=$params['points']*$zk;
		  	//要发的数组
		  	$arr=randmoney($data['money'], $params['num'], $zk);
		  	
	  	}
	  	
	  	$this->conn->startTrans();
	  	$sql=insert_sql("mall_bonus_manage",$data);
	  	if(!$this->conn->execute($sql)){
	  		$this->conn->completeTrans(false);
	  		$this->logger->error(sprintf("add mall_bonus_manage fail.".$sql));
	  		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	  	}
	  	$bid=$this->conn->getInsertID();
	  	 $sql="insert into mall_bonus_record values ";
	  	 foreach ($arr as $k=>$v){
	  	 	
	  	 		$sql.="(null,$bid,null,null,'".$v."',0 ),";
	  	 	
	  	 }
	  	 if(!$this->conn->execute(substr($sql,0,-1))){
	  	 	$this->conn->completeTrans(false);
	  	 	$this->logger->error(sprintf("add mall_bonus_record fail.".$sql));
	  	 	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	  	 }
	  	 if($user['user_type_id']==1){
	  	 	//减去积分
	  	 	$use_pints=$data['money']/$zk;
	  	 	$points=$user['member_points']-$use_pints;
	  	 	
	  	 	$sql="update mall_member set member_points=$points where member_id=$uid";
	  	 	if(!$this->conn->execute($sql)){
	  	 		$this->conn->completeTrans(false);
	  	 		$this->logger->error(sprintf("update points fail.".$sql));
	  	 		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	  	 	}
	  	 	//写记录
	  	 	$sql=add_points_log($uid,-$use_pints,'发红包消费',7,$bid);
	  	 	if(!$this->conn->execute($sql)){
	  	 		$this->conn->completeTrans(false);
	  	 		$this->logger->error(sprintf("add ponits_log fail.".$sql));
	  	 		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	  	 	}
	  	 }
	  
	  	 $msgObj = array(
	  	 		"id" => $params["id"],
	  	 		"type" => "BONUS",
	  	 		"mime" => "bonus/plain",
	  	 		"gt" => 1,
	  	 		//"groupid"=>$groupid,
	  	 		"srcm" => $user['member_name'],
	  	 		"src" => $uid,
	  	 		"text" => $bid,
	  	 		"money"=>$data['money'],
	  	 		"title"=>urlencode($params['title']),
	  	 		"time" => time());
	  	 
	  	 $logger->info("BID:".$bid);
	  	 $databaseManager=M('Msg');
	  	 //获取group对应的群人员信息
	  	 $attendeeArray = $databaseManager->getMembersM($user);
	  	
	  	 $notifyMembers = implode(",", $attendeeArray);
	  	 $accepters = M('User')->getUserInfoByUid($notifyMembers);
	  	 if(!$accepters){
	  	 	$logger->error(sprintf("accepter not exists ,group members is  %s",$notifyMembers));
	  	 	ErrCode::echoErr(ErrCode::API_ERR_MESSAGE_SEND_ERROR,1);
	  	 }
	  	 	 
	  	 $mecManager = new MecManager($uid,$msgObj,$accepters);
	  	 if($mecManager->sendMessage())
	  	 {  
	  	 	
	  	 	$this->conn->completeTrans(true);
	  	 	ErrCode::echoJson(1,'执行成功');
	  	 
	  	 }else{
	  	 	$this->conn->completeTrans(false);
	  	 	$logger->error("mec send fail");
	  	 	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	  	 }
	  	 
	  	
	  }
		
	  
	
	
}