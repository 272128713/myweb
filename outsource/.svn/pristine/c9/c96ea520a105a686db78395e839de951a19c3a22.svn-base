<?php
class CommonController extends  Controller{
	//用户变量
	public $user;
	//检查用户session
	public  function __construct(){
		parent::__construct();
		$logger = Logger::getLogger(basename(__FILE__));
		if($_SERVER['REQUEST_METHOD']!="POST"){
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		//验证用户session
		if(isset($_POST['ss'])){
			$session=$_POST['ss'];
		}else{
			$logger->error(sprintf("params error. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=M('User')->checkSession($session);
		if($user){
			$this->user=$user;
			//开始送积分
			if($user['user_type_id']==1){
				//今天已经送多少
				$conn=M()->conn;
				$config=new Config();
				$ntegral=C('ntegral');
				$allMoney=C('gava_limit')/$ntegral;
				$points=C('gave_point')/$ntegral;
				$conn->startTrans();
				$sql="select points from mall_points where date_format(create_date,'%Y-%m-%d')
                          =date_format(now(),'%Y-%m-%d') and member_id=".$user['member_id'];
				
				$nowPoints=$conn->getOne($sql);
				
				if(!$nowPoints){
					$member_id=$user['member_id'];
					$date=date('Y-m-d');
					$sql="insert into mall_points (member_id,points,create_date,time_date) 
							values($member_id,'$points',now(),'$date')";
				
					$conn->execute($sql);
					//更新表
					$sql="update  mall_member set member_points=member_points+$points where  member_id=".$user['member_id'];
					$conn->execute($sql);
						
				}else{
					
					if ($nowPoints<$allMoney){
					
						$sql="update  mall_points set points=points+$points where date_format(create_date,'%Y-%m-%d')
                          =date_format(now(),'%Y-%m-%d') and member_id=".$user['member_id'] ." and points<$allMoney";
						$conn->execute($sql);
						$num=$conn->getOne('SELECT ROW_COUNT()');
						if($num>0){
							//开始加积分
							$sql="update  mall_member set member_points=member_points+$points where  member_id=".$user['member_id'];
							$conn->execute($sql);
						}
						
						
					}
				}
					if ($conn->hasFailedTrans ()) {
						$conn->completeTrans (false);
					} else {
						$conn->completeTrans (true);
					}
			}
		}else{
			$logger->error(sprintf("Session check is fail. Error session is [%s]",$session));
			Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
		}
       
		$this->initCofig();
	}
    
	/**
	 * 初始化配置文件
	 */
	private  function  initCofig(){
		$this->page=C('page_num');
		$this->ntegral=C('ntegral');
		$this->webUrl=C('webUrl');
		$this->domain=C('domain');
		$this->msgText=C('msgText');
		$this->img=C('img_path');
		$this->yixin=C('yixin_api_path');
		
	}
    //分页
    public  $page;
    
    //积分兑换率
    public  $ntegral;
    
	//网页地址
    public  $webUrl;
    
    //内嵌网页存在地址
    public  $domain;
    //短信内容
    public  $msgText;
    
    //图片地址
    public $img;
    
    //重置赠送比例
    public $scaleMoney=array(
    	'100'=>0,
    	'200'=>0,
    	'500'=>0,
    	'1000'=>0,
    	'2000'=>0,
    	'5000'=>0,
    );
    
    //空中医院接口地址
    public $yixin;
    
	
	 
}