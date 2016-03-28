<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/20
 * Time: 16:56
 */
class UrgencyController extends  CommonController{

    private  $num=10;   //紧急联系人上限
    /**
     *增加紧急联系人
     */
    public  function  add(){
        $logger = Logger::getLogger(basename(__FILE__));
        $user=$this->user;
        $params = array(array("name",true),array("mobile",true));
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
        $name=$params['name'];
        $mobile=$params['mobile'];
        $member_id=$user['member_id'];
        //判断已经有几个紧急联系人
        $model=M();
        $num=$model->conn->getOne("select count(*) from mall_urgency WHERE member_id=$member_id");
        if($num>=$this->num){
                ErrCode::echoErr(ErrCode::API_HAS_TOO_MANY_URGENCY,1);
        }
        $sql="insert into mall_urgency (urgency_mobile,urgency_name,member_id) VALUES ('$mobile','$name',$member_id)";
        $db=$model->conn;
        if($db->execute($sql)){
        	    $eid=$db->getInsertID();
                ErrCode::echoJson(1,'添加成功',array('urgency_id'=>$eid));
        }else{
                ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
    }

    /**
     * 显示紧急联系人
     */

    public  function  show(){
        $user=$this->user;
        $member_id=$user['member_id'];
        //判断已经有几个紧急联系人
        $model=M();
        $result=$model->conn->getAll("select urgency_mobile,urgency_id,urgency_name,'$this->msgText' as msg from mall_urgency WHERE member_id=$member_id");
        if($result){
            ErrCode::echoJson(1,'获取成功',$result);
        }else{
            ErrCode::echoJson(1,'无紧急联系人',array());
        }
    }

    /**
     * 删除紧急联系人
     */
    public  function  delete(){
        $model=M();
        $logger = Logger::getLogger(basename(__FILE__));
        $user=$this->user;
        $params = array(array("eid",true));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        $eid=$params['eid'];
        $sql="delete from  mall_urgency WHERE  urgency_id=$eid";
        if($model->conn->execute($sql)){
            ErrCode::echoJson(1,'删除成功');
        }else{
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
    }
    /**
     * 呼叫健康管理师   getHealthNumber
     */
    public  function  getHealthNumber(){
    	$model=M();
    	$logger = Logger::getLogger(basename(__FILE__));
    	$user=$this->user;
    	$params = array(array("ss",true));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
    	//获取用户的对应的会员id     从mall_member 这个表中找出对应关系
    	$memberid=$user['member_id'];
    	$sql="select member_name from mall_member 
    			where member_id=(select parent_id from mall_member where member_id=$memberid) ";
    	
    	$result=M()->conn->getOne($sql);
    	if($result){
    		ErrCode::echoJson(1,'成功',array('mobile'=>$result,'msg'=>$this->msgText));
    	}else{
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	}
    }
    
    /**
     * 是否有已经开启的失联防范
     */
    public  function  isHave(){
    	$model=M()->conn;
    	$logger = Logger::getLogger(basename(__FILE__));
    	$user=$this->user;
    	$params = array(array("ss",true));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
    	$memberid=$user['member_id'];
    	$sql="select * from mall_lose
    	where state=1 and  member_id=".$memberid;
    	 
    	$result=$model->getRow($sql);
    	$return =array();
    	if($result){
    		//返回人
    		$return['lose_id']=$result['lose_id'];
    		$return['create_time']=$result['create_time'];
    		$return['time_inerval']=$result['time_inerval'];
    		$return['people']=$model->getAll("select mobile,name from mall_lose_people where lose_id=".$result['lose_id']);
    		$return['location']=
    		$model->getAll("select lfix as longitude,rfix as latitude,create_time from mall_lose_location 
    		 		       where lose_id=".$result['lose_id']);
    		ErrCode::echoJson(1,'成功',$return);
    	}else{
            ErrCode::echoErr(ErrCode::HAVE_NO_LOSE,1);
    	}
    }
    
    /**
     * 开启失联
     */
    public  function  start(){
    	$model=M()->conn;
    	$logger = Logger::getLogger(basename(__FILE__));
    	$user=$this->user;
    	$params = array(array("ss",true),array("uid",true),array("interval",true));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
    	$memberid=$user['member_id'];
    	$sql="select * from mall_lose
    	where state=1 and  member_id=".$memberid;
    	 
    	$result=$model->getRow($sql);
    	$return =array();
    	if($result){
    		//返回人
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	}
    	//开始添加
    	$uid=$params['uid'];
    	$interval=$params['interval'];
    	$model->startTrans();
        $sql="insert into mall_lose values (null,now(),$interval,$memberid,1) ";
        
        if(!$model->execute($sql)){
        	$model->completeTrans(false);
        	$logger->error('add mall_lose fail'.$sql);
        	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        $lid=$model->getInsertID();
    	$arr=$model->getAll("select * from mall_urgency where urgency_id in ($uid) and member_id=$memberid");
    	
    	$sql="insert into mall_lose_people values ";
    	foreach ($arr as $k=>$v){
    	
    		$sql.="(null,$lid,'".$v['urgency_mobile']."','".$v['urgency_name']."'),";
    	
    	}
    	$sql=substr($sql,0,-1);
    	if(!$model->execute($sql)){
    		$model->completeTrans(false);
    		$logger->error('add people fail'.$sql);
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	}
    	
    	$model->completeTrans(true);
    	ErrCode::echoJson(1,'获取成功');
    	
    	
    }
    /**
     * 停止失连
     */
    public  function  stop(){
    	$model=M()->conn;
    	$logger = Logger::getLogger(basename(__FILE__));
    	$user=$this->user;
    	$params = array(array("ss",true));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
    	$memberid=$user['member_id'];
    	$sql="delete  from mall_lose
    	where state=1 and  member_id=".$memberid;
    
    	$result=$model->execute($sql);
    	$return =array();
    	if($result){
    		//返回人
    		ErrCode::echoJson(1,'停止成功');
    		
    	}
    	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	
    	 
    	 
    }
    
    /**
     * 失连上传地址
     */
    public  function  upload(){
    	$model=M()->conn;
    	$logger = Logger::getLogger(basename(__FILE__));
    	$user=$this->user;
    	$params = array(array("ss",true),array("longitude",true),array("latitude",true));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
    	$memberid=$user['member_id'];
    	$sql="select lose_id from mall_lose
    	where state=1 and  member_id=".$memberid;
    	$result=$model->getOne($sql);
    	$return =array();
    	
    	$data=array(
    		'lfix'=>$params['longitude'],
    		'rfix'=>$params['latitude'],
    		'create_time'=>date('Y-m-d H:i:s'),
    		'lose_id'=>$result			
    	);
    	
    	$sql=insert_sql('mall_lose_location',$data);
    	
    	$result=$model->execute($sql);
    	if($result){
    		//返回人
    		ErrCode::echoJson(1,'执行成功');
    	
    	}
    	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	 
    
    
    }
    
    
    
}
?>