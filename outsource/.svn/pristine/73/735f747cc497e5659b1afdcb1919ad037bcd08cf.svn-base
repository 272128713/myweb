<?php
/**
 * 用户控制器
 */
class MemberController extends CommonController{
	/**
	 * 用户首页
	 */
	public  function index(){
		$logger = Logger::getLogger(basename(__FILE__));
		$return=array();
		$model=M('User');
		$conn=$model->conn;
		//查询视频
		$sql='select video_title,video_img,video_id,video_type,video_goods_id,video_url from mall_video where video_status=1 order by video_sort asc limit 1';
		$video=$model->conn->getRow($sql);
		if(!$video){
			$logger->error(sprintf("vedio check error"));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		$return=array_merge($return,$video);
		$user=$this->user;
		$user_arr=array();
		$user_arr['uid']=$user['member_id'];
		$user_arr['member_truename']=$user['member_truename'];
		$user_arr['member_level']=$user['member_level'];
		$user_arr['member_points']=$user['member_points'];
		$user_arr['money']=$user['agency_money'];
		$return=array_merge($return,$user_arr);
		
		//查询会员头像
		$return=array_merge($return,$model->getVersion($user['member_id']));
        //是否有健康圈
        if($user['parent_id']==1111111111111){
            $return['is_circle']=0;
        }else{
            $return['is_circle']=1;
        }
		//健康圈人数
		$return['member_num']=$model->getMemberNum($user['parent_id']);
		//查询有几个订单
		$ordernum=$conn->getOne('select count(*) as num from mall_order where buyer_id='.$user['member_id']);
		$return['order_num']=$ordernum;
		//是否有未读消息
		$uid=$user['member_id'];
		$sql="select  member_id from mall_msg where member_id=$uid and msg_status=0";
		if(M()->conn->getOne($sql)){
			$return['msg_status']=1;
		}else{
			$return['msg_status']=0;
		}
		
		ErrCode::echoJson(1,'执行成功',$return);
	}

    /**
     * 获取个人信息
     */
    public  function  getInfo(){
        $user=$this->user;
        $return =array();
        $return['member_truename']=$user['member_truename'];
        $return['member_sex']=$user['member_sex'];
        $return['card_id']=$user['card_id'];
        //获取头像版本等等
        $version=M('User')->getVersion($user['member_id']);
        $return=array_merge($return,$version);
       
        $return['member_birthday']=$user['member_birthday'];
       
        $return['member_address']=$user["member_address"];
       
        $return['disease']=$user["disease"];
        
        $return['d_address']=$user['d_address'];
        $return['p_address']=$user['p_address'];
        ErrCode::echoJson(1,'获取成功',$return);

     }
    /**
     * 修改个人头像
     */
    public  function  changePic(){
        header("content-type:text/html; charset=utf-8");
        include (ROOT_PATH . "/common/MMSFileManager.php");
        $config = new Config();
        $logger = Logger::getLogger(basename(__FILE__));
        $challengeStep = false;
        $params = array(array("ss",true));

        //print_r($_POST);
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params error. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }

        $databaseManager=M('User');
        $sessionCheck=$this->user;
        $user_id = $sessionCheck['user_id'];
        $role = $sessionCheck["role"];
        $old_img_urls = $databaseManager->GetAvatarImgUrl($user_id);
        if(is_array($old_img_urls)){
            $fileManagerObj = new MMSFileManager();
            $fileManagerObj->deleteFileByFastDFS($old_img_urls["source_image_url"]);
            $fileManagerObj->deleteFileByFastDFS($old_img_urls["thumbnail_image_url"]);
        }
        $logger->error(sprintf("FILE  %s",v($_FILES)));
        $fileName = $_FILES['file']['name'];
        $fileTempName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];

        $config = new Config();
        $max_file_size = $config->getLocalConfig('max_file_size');

        $file_params = array();
        $file_params["id"] = "$user_id";
        $file_params["mime"] = "avatar";
        //upload file
        if(isset($_FILES['file'])){
            if($_FILES['file']['error'] == 0){
                if($_FILES['file']['size'] <= $max_file_size){
                    $fileManager = new MMSFileManager();
                    $saveFileResult = $fileManager->uploadFile($fileTempName, $fileName, $file_params);
                    if(!$saveFileResult){
                        $logger->error(sprintf("uploadFile():Upload file failed. filename=%s, filesize=%s",$fileName,$fileSize));
                        ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
                    }
                    
                    //是否存在医信
                    $conns=M()->getYixinConn();                    
                    $mobile=$sessionCheck['member_name'];
                    if($conns->getOne("select id from com_dic_sso_info where mobile='$mobile'")){
	                    $data = array('mobile'=>$sessionCheck['member_name'],'file'=>'@'.$_FILES['file']['tmp_name']);
	                    $result=posters('update_avatar_no.php', $data);
	                    
                    }else{
                    	
                    }
                    //是否存在
                    $logger->info("uploadFile():Upload file success. filename:$fileName, filesize:$fileSize");
                    ErrCode::echoJson("1",'更换成功',array("pav"=>$saveFileResult));
                }else{
                    $logger->error(sprintf("File size exceeds the limit. max_file_size=%s",$max_file_size));
                    ErrCode::echoErr(ErrCode::FILE_TOO_LARGE,1);
                }
            }else{
                $logger->error(sprintf("no file params error."));
                ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
            }
        }else{
            $logger->error(sprintf("no file params error."));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
    }
    
    /**
     * 修改个人信息
     */

    public  function  update()
    {
        $logger = Logger::getLogger(basename(__FILE__));
        $params = array(
        		array("ss", true),           //session
        		array("member_id", false),           //用户id
        	array("member_truename", false),            //真实姓名
            array("member_name", false),            //真实姓名
            array("member_sex", false),            //性别
            array("card_id", false),            //血型，格式为编码
            array("member_birthday", false),            //出生时间，格式为年月日时
            array("member_address", false),            //出生地详细地址
        	array("member_provinceid", false),        //省份ID
        	array("member_cityid", false),            //城市ID
        	array("member_areaid", false),            //地区ID
        	array("d_address", false),            //出生地详细地址
        	array("p_address", false)            //出生地详细地址
        	
   
        );
        //print_r($_POST);
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if (!$params) {
            $logger->error(sprintf("params error. params is %s", v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER, 1);
        }
        $session = $params["ss"];
        
        /**
         * 修改手机号验证重复
         */
        if(isset($params['member_name'])){
        	M('User')->isMember($params['member_name']);
        }
        
        /**
         * 是否有权限
         */
        if(isset($params['member_id'])){
        	check_role($this->user);
        	$cuid=$params['member_id'];
        	unset($params['member_id']);
        }
        //处理参数和表字段的对应关系
        $newparams = array(
            "member_truename" => "member_truename",
        	"member_name"=>"member_name",
            "member_sex" => "member_sex",
            "card_id" => "card_id",
            "member_birthday" => "member_birthday",
            "member_address" => "member_address",
        	"member_provinceid" => "member_provinceid",
        	"member_cityid" => "member_cityid",
        	"member_areaid" => "member_areaid",
        	"d_address" => "d_address",
        	"p_address" => "p_address",

        );

        $sqlValue = array();
        $sqlString = "";
        foreach ($_POST as $key => $value) {
            if ($key == "ss") continue;
            if (isset($newparams[$key]) && $value!='' && !is_null($value)) {
                array_push($sqlValue, $newparams[$key] . "='" . $value . "'");
            }
        }
        $model = M('User');
        $sessionInfo = $this->user;
        if(isset($cuid)){
        	$user_id = $cuid;
        }else{
        	$user_id = $sessionInfo["user_id"];
        }
      
        $updateSql = "update mall_member set " . implode(",", $sqlValue) . " where member_id = '$user_id'";  //拼装的sql语句
        $updateInfo = $model->updatePersonalInfo($updateSql, $user_id);
        if (!$updateInfo) {
            $logger->error(sprintf("update personal info failure.user_id is %s,sql=%s ", $user_id, $updateSql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR, 1);
        } else {
            $version = $model->getVersion($user_id);  //获取版本
            if (!$version) {
                $logger->error(sprintf("get info version failure.user_id is %s", $user_id));
                ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
            } else {
            	//是否存在医信
            	$conns=M()->getYixinConn();
            	$mobile=$sessionInfo['member_name'];
            	if($conns->getOne("select id from com_dic_sso_info where mobile='$mobile'")){
            		
	            	if($_POST['member_sex']=='男'){
	            		$sex=1;
	            	}else if($_POST['member_sex']=='女'){
	            		$sex=2;
	            	}
	            	$data=array(
	            		'mobile'=>$mobile,	
	            		'nm'=>$_POST['member_truename'],
	            		'sex'=>$sex,
	            		'bd'=>$_POST['member_birthday'],
	            		'rpo'=>$_POST['member_areaid'],
	            	);
	            	
	            	$result=poster('update_personal_info_no.php',$data);
	            	
            		 
            	}
            	
                $echo_arr = array("piv" => $version["piv"]);
                ErrCode::echoJson(1,'修改成功',$echo_arr);
            }
        }
    }

    /**
     * 获取访谈列表
     */

    function  getVedio(){
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

        $sql="select * from mall_video WHERE video_status=1 order by video_sort asc ,video_id desc limit $page,$this->page";
        $result=M()->conn->getAll($sql);
        if($result){
            ErrCode::echoJson(1,'成功',$result);
        }else{
            ErrCode::echoJson(1,'无数据或者查询失败',array());
        }
    }

    /**
     * 获取商品分类
     */
    public  function  getClass(){

        $sql="select gc_name,gc_id from mall_goods_class WHERE gc_show=1 order by gc_sort asc";
        $result=M()->conn->getAll($sql);
        if($result){
            ErrCode::echoJson(1,'成功',$result);
        }else{
            ErrCode::echoJson(1,'无数据或者查询失败',array());
        }
    }

   
/********************************xu****************************************************************/
    
    /**
     * 获取收益记录
     */
    public  function getMoneyLog(){
    	
    	$logger = Logger::getLogger(basename(__FILE__));
    	$params = array(array("ss",true),array("page",false),array("member_id",false));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	//获取参数
    	$session = $params["ss"];    //用户sesssion
    	if(isset($params['page'])){
    		$page=($params['page']-1)*($this->page);
    	
    	}else{
    		$page=0;
    	}
    	if(isset($params['member_id'])){
    		$uid=$params['member_id'];
    	}else{
    		$uid=$user['member_id']; //用户id
    	}
    	//这个类是继承了session验证那个类的，所以下面的代码是session中存放的所有数据
    	$user=$this->user;   
    	//获取用户的对应的会员id     从mall_member 这个表中找出对应关系
    	$memberid=$user['member_id'];
    	//FROM_UNIXTIME(shop_time,'%Y-%m-%d %H:%i:%s') as shop_time  输出结果:2015-08-22 16:06:03 这个函数是把数据库中存储的时间戳格式直接查询的时候就转成日期格式
    	$sql="select FROM_UNIXTIME(pdr_add_time,'%Y-%m-%d %H:%i:%s') as pdr_add_time,pdr_id,pdr_type,pdr_payment_name,pdr_amount from mall_pd_recharge 
    			where pdr_member_id=$memberid and pdr_amount>0 order by pdr_add_time desc limit $page,$this->page";
    	$result=M()->conn->getAll($sql);
    	// 拼接字符串
    	//foreach ( $user1 as $k => $v ) {
    	//	$user1 [$k] = array_merge ( $v, $user2 );
    	//}
    	if($result){
    		ErrCode::echoJson(1,'成功',$result);
    	}else{
            ErrCode::echoJson(1,'失败/查找的数据为空',array());
    	}
    	
    }
    /**
     *  1.2.11. Member/goodsList（商品列表）
     */
    public function goodsList(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$params = array(array("ss",true),array("cid",false),array("page",false));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	//获取参数
    	if(isset($params["cid"])){
    		$cid = $params["cid"];  //商品分类id 不传为所有产品
    	}else{
    		$cid=0;
    	}
    	
    	
    	if(isset($params['page'])){
    		$page=($params['page']-1)*($this->page);
    	}else{
    		$page=0;
    	}
    	//实现业务逻辑 ，cid是商品分类id 不传为所有产品（gc_id 标记商品分类id）
		if($cid){

    	$sql="select goods_id,goods_name,goods_marketprice,goods_price,goods_image
    			from mall_goods
    			where gc_id=$cid and goods_state=1 order by goods_id desc limit $page,$this->page";

		    
    	}else{
    		
    		
    		//查询套餐
    		$sql="select bl_id as goods_id,bl_name as goods_name ,bl_old_price as goods_marketprice,
    			 bl_discount_price as goods_price,bunding_img as goods_image
    			from mall_p_bundling
    			where  bl_state=1 order by bl_id desc limit $page,$this->page";	
    	}
        
    	$result=M()->conn->getAll($sql);
    	if($result){
    		foreach($result as $k=>$v){
    			$result[$k]['goods_image']=formatImg($v['goods_image'],60);
    		}
    		ErrCode::echoJson(1,'成功',$result);
    	}else{
    		ErrCode::echoJson(1,'失败/查找的数据为空',array());
    	}
    	
    }
    /**
     *      1.2.19. Member/envelopeList（抢红包列表）
     */

   public  function   envelopeList(){
   	
   	$logger = Logger::getLogger(basename(__FILE__));
   	$params = array(array("eid",true),array("page",false));
   	$params = Filter::paramCheckAndRetRes($_POST, $params);
   	if(!$params){
   		$logger->error(sprintf("params is err. params is %s",v($_POST)));
   		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
   	}
   	//获取参数
   	$eid = $params["eid"];   //红包id
   	if(isset($params['page'])){
   		$page=($params['page']-1)*($this->page);
   	}else{
   		$page=0;
   	}
   	$user=$this->user;
   	check_role($user,1); //验证是否是会员操作
   	//实现业务逻辑
   	$sql="select a.money,a.member_id as uid ,a.create_time,b.member_truename,c.thumbnail_image_url,c.base_ver as piv,c.image_ver as pav
   			from mall_bonus_record  as a
   			LEFT JOIN mall_member as b On a.member_id=b.member_id
   			LEFT JOIN mall_user_version_info as c On a.member_id=c.user_id
   			where  a.bonus_id=$eid and a.member_id is not null
   			order by a.create_time desc
   			limit $page,$this->page";
   	$result=M()->conn->getAll($sql);
   	if($result){
   		ErrCode::echoJson(1,'成功',$result);
   	}else{
   	    ErrCode::echoJson(1,'失败/查找的数据为空',array());
   	}
   	
   	
   }
   
   /**
    *   1.3.30. Member/getBalanceTotalWealth （获取财富总余额）
    */
   public function getBalanceTotalWealth(){
   
   	$logger = Logger::getLogger(basename(__FILE__));
   	$params = array(array("ss",true));
   	$params = Filter::paramCheckAndRetRes($_POST, $params);
   	if(!$params){
   		$logger->error(sprintf("params is err. params is %s",v($_POST)));
   		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
   	}
   	$user=$this->user;
   	check_role($user,1); //检查登陆的账户是不是会员
   	$wid=$user['member_id']; //获取该登陆的会员的id
   	$sql="select agency_money from mall_member where member_id='$wid'";
   	$result=M()->conn->getOne($sql);
   	$res=Array();
   	$res['balance']=$result;
   	if($res){
   		ErrCode::echoJson(1,'成功',$res);
   	}else{
   		ErrCode::echoJson(1,'没有查找到数据/失败',array());
   	}
   
   }
   
   /**
    *  1.2.20. Member/updateVideo（更新播放次数）
    */
   public function updateVideo(){
   	 
   	$logger = Logger::getLogger(basename(__FILE__));
   	$params = array(array("ss",true),array("vid",true));
   	$params = Filter::paramCheckAndRetRes($_POST, $params);
   	if(!$params){
   		$logger->error(sprintf("params is err. params is %s",v($_POST)));
   		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
   	}
   	$vid=$params['vid']; //视频id
   	$user=$this->user;
   	check_role($user,1); //检查登陆的账户是不是会员
   	
   	$sql="select video_num  from mall_video where video_id='$vid'";
   	$result=M()->conn->getOne($sql);
   	$temp=++$result;
   	$sql2="update mall_video set video_num='$temp' where video_id='$vid'";
   	$res=M()->conn->execute($sql2);
  
   	if($res){
   		ErrCode::echoJson(1,'成功');
   	}else{
   		ErrCode::echoJson(0,'更新失败');
   	}
   	 
   }
   
   
   
   
   
    /***************************************xu****************************************************************/

    /**
     * 会员签到
     */ 
    public  function  signIn(){
        $user=$this->user;
        $uid=$user['member_id'];
        check_role($user,1);
        $model=M('User');
        //今天是否已经签到
        $sql="select member_id from mall_sign_record where member_id= $uid
            and date_format(sign_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d') ";
        if($model->conn->getOne($sql)){
            //已经签到了todo
            ErrCode::echoErr(ErrCode::API_YOU_HAVE_SIGN,1);
        }
        //进行签到
        $code=$model->signIn($uid);
        ErrCode::echoJson(1,'签到成功',array('money'=>$code));
    }

    /**
     * 会员签到首页
     */
    public  function  signInfo(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$params = array(array("ss",true),array("uid",false));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	$user=$this->user;
        //$uid=$user['member_id'];
        isset($params['uid']) ?  $uid=$params['uid'] :  $uid=$user['member_id'];
        check_role($user,1);
        $model=M('User')->conn;
        $return=array();
        $sql="select count(*) from mall_sign_record where date_format(sign_time,'%Y-%m')
                          =date_format(now(),'%Y-%m') and member_id=$uid";
        //本月总共签到
        $return['month_total_num']=$model->getOne($sql);
        //本月连续签到天数
        $sql="select month_nums from mall_sign_record where date_format(sign_time,'%Y-%m')
                          =date_format(now(),'%Y-%m') and member_id=$uid order by sign_time desc";

        $return['month_continue_num']=$model->getOne($sql);

        $sql="select count(*) from mall_sign_record where date_format(sign_time,'%Y')
                          =date_format(now(),'%Y') and member_id=$uid";
        //本年总共签到
        $return['year_total_num']=$model->getOne($sql);

        //累计签到次数
        $sql="select count(*) from mall_sign_record where  member_id=$uid";
        $return['total_num']=$model->getOne($sql);

        ErrCode::echoJson(1,'获取成功',$return);
    }

    /**
     * 会员当月签到情况
     */
    public  function  signMonth(){
        $logger = Logger::getLogger(basename(__FILE__));
        $params = array(array("date",true),array("uid",false));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        //获取参数
        $date = $params["date"];
        $user=$this->user;
        isset($params['uid']) ?  $uid=$params['uid'] :  $uid=$user['member_id'];
        check_role($user,1);
        $model=M('User')->conn;
        $sql="select date_format(sign_time,'%Y-%c-%e') as sign_time from mall_sign_record where date_format(sign_time,'%Y%m')
                          ='$date' and member_id=$uid";
        $rsult=$model->getAll($sql);
        ErrCode::echoJson(1,'成功',$rsult);
    }

    /**
     * 商品详情页
     */
    public  function  goodDetail(){
        $logger = Logger::getLogger(basename(__FILE__));
        $params = array(array("gid",true),array('isbunding',true),array("ss",false));
        $params = Filter::paramCheckAndRetRes($_POST,$params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        $user=$this->user;
        $uid=$user['member_id'];
        $gid=$params['gid'];
        check_role($user,1);
        $model=M('User')->conn;
        $return=array();
       
      
        if($params['isbunding']==0){
         $sql="select goods_id,points as points_use,goods_commonid,goods_name,goods_marketprice,goods_price
              from mall_goods WHERE goods_id=$gid;            ";
        }else{
        	$sql="select bl_id as goods_id,bl_name as goods_name ,bl_old_price as goods_marketprice,0 as points_use,body,
        	bl_discount_price as goods_price,bunding_img as goods_image
        	from mall_p_bundling where bl_id=$gid";
        
        }
        $product=$model->getRow($sql);
        $return['goods_name']=$product['goods_name'];
        $return['goods_marketprice']=$product['goods_marketprice'];
        $return['goods_price']=$product['goods_price'];
        $return['goods_id']=$product['goods_id'];
        $return['points_use']=$product['points_use'];
        //查询对应的产品图片
        if($params['isbunding']==0){
	        $cid=$product['goods_commonid'];
	        $sql="select goods_image  as img_url from  mall_goods_images WHERE goods_commonid=$cid order by is_default desc ";
	        $all_img=$model->getAll($sql);
	      
        }else{
        	$all_img=array(0=>array('img_url'=>$product['goods_image']));
        }
        foreach($all_img as $keys=>$values){
        	$all_img[$keys]['img_url']=formatImg($values['img_url']);
        }
        $return['goods_img_list']=$all_img;
        //会员积分
        $return['member_points']=$user['member_points'];
        $return['member_points_money']=$user['member_points']*$this->ntegral;
        //对应员工
        $return['worker_id']=$user['parent_id'];
        $return['worker_mobile']=$model->getOne("select member_name from mall_member where member_id=".$user['parent_id']);
        if($params['isbunding']==0){
        // 手机商品描述        
        	$sql="select mobile_body
        	from mall_goods_common WHERE goods_commonid=$cid
        	";
        	$g_info=$model->getRow($sql);
	        $mobile_body_array = $this->mb_unserialize($g_info['mobile_body']);
	        if(empty($mobile_body_array)){
	        	$mobile_arr=array(array('type'=>'text','value'=>'此商品暂无介绍'));
	        	$return['mobile_body'] = $mobile_arr;
	        }else{
	        	$return['mobile_body'] = $mobile_body_array;
	        }
        }else{
        	
        	 $sql = "select a.goods_id,a.goods_name,a.bl_goods_price as
              goods_price_now ,a.goods_image as img_url,b.goods_price as goods_price_old from mall_p_bundling_goods as a,mall_goods as b
              where a.bl_id=$gid and b.goods_id=a.goods_id";
            $return['pakege_list'] = $model->getAll($sql);
        	$mobile_body_array = $this->mb_unserialize($product['body']);
        	if(empty($mobile_body_array)){
        		$mobile_arr=array(array('type'=>'text','value'=>'此商品暂无介绍'));
        		$return['mobile_body'] = $mobile_arr;
        	}else{
        		$return['mobile_body'] = $mobile_body_array;
        	}
        }
        //此会员所在店名
        $return['shop_name']=$model->getOne('select shop_name from mall_shop where shop_id='.$user['store_id']);
        
        ErrCode::echoJson(1,'获取成功',$return);


    }

    /**
     * 购买商品
     */
    public  function  buyGoods(){
        $logger = Logger::getLogger(basename(__FILE__));
        $params = array(array("gid",true),array("use_poionts",true),array("is_pakage",true));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        $user=$this->user;
       
        check_role($user,1);
        $model=M('User')->conn;
        $uid=$user['member_id']; //用户id
        $pg=$params['is_pakage'];
        $gid=$params['gid'];
        $pid=$gid;
        $p=$params['use_poionts'];
        $return=array();
        //查询产品
        $sql="select goods_id,points as points_use,goods_commonid,goods_name,goods_marketprice,goods_price
              from mall_goods WHERE goods_id=$gid;
             ";
        $product=$model->getRow($sql);
        $return['goods_name']=$product['goods_name'];
        $return['goods_marketprice']=$product['goods_marketprice'];
        $return['goods_price']=$product['goods_price'];
        $return['goods_id']=$product['goods_id'];
        //查询对应的产品图片
        $cid=$product['goods_commonid'];
        $sql="select goods_image  as img_url from  mall_goods_images WHERE goods_commonid=$cid order by is_default desc ";
        $return['goods_img_list']=$model->getAll($sql);
        //会员积分
        $return['member_points']=$user['member_points'];
        $return['member_points_money']=$user['member_points']*$this->ntegral;
        //对应员工
        $return['worker_id']=$user['parent_id'];
        $return['worker_mobile']=$model->getOne("select member_name from mall_member where member_id=".$user['parent_id']);
       
        if($pg) {
        	$return['pakage_id']=$pid;
            //套餐总价
            $sql="select bl_discount_price from mall_p_bundling where bl_id=$pid";
            $return['pakege_price'] = $model->getOne($sql);

            //根据套餐id查询相关产品
            $sql = "select a.goods_id,a.goods_name,a.bl_goods_price as
              goods_price_now ,a.goods_image as img_url,b.goods_price as goods_price_old from mall_p_bundling_goods as a,mall_goods as b
              where a.bl_id=$pid and b.goods_id=a.goods_id";
            $pprodcut = $model->getAll($sql);
            $save_price = 0.00;
            foreach ($pprodcut as $v) {
                $save_price += ($v['goods_price_old'] - $v['goods_price_now']);
            }

            //套餐节省的钱
            $return['pakege_save'] = $save_price;


        }else{
        	$return['pakage_id']=0;
            $pprodcut=array(array(
                'goods_id'=>$return['goods_id'],
                'goods_name'=>$return['goods_name'],
                'goods_price_now'=>$return['goods_price'],
                'goods_price_old'=>$return['goods_price'],
                'img_url'=>$return['goods_img_list'][0]['img_url'],
            ));
        }
        $return['pakege_list'] = $pprodcut;
        //订单价钱
        if($pg){

            $return['total_price']=$return['pakege_price'];
        }else{
            $return['total_price']=$return['goods_price'];
        }
        $order_id= M('User')->buyGoods($user,$return,$p,$pg,$this->ntegral,$product['points_use']);
       

        //给对应员工发信息
        $mec_str='用户'.$user['member_truename'].'购买商品';
        $mec_arr=array(
            'type'=>'UBW',
            'user_id'=>$user['member_id'],
            'mobile'=>$user['member_name'],
            'content'=>$mec_str,
            'accepters'=>M('User')->getUserInfoByUid($user['parent_id']),
        	'msg_id'=>$order_id	
        );

        send_msg($mec_arr);
        ErrCode::echoJson(1,'购买成功');

    }

   /**
    * 消费记录
    */
    public function consumeList(){
        $logger = Logger::getLogger(basename(__FILE__));
        $params = array(array("page",false),array("ss",true),array('type',true),array('member_id',false));
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
        if(isset($params['member_id'])){
        	$uid=$params['member_id'];
        }else{
       	 $uid=$user['member_id']; //用户id
        }
        $model=M('User')->conn;
        $type=$params['type'];
         //1返回待处理 2 返回已取消 3返回已经完成
        $arr=array(
        	'1'=>"20,30,50,60,70",
        	'2'=>"0",
        	'3'=>"40",
        	'4'=>'0,20,30,40,50,60,70'			
        );
        //查寻订单记录
        if($type==4){
        	$sql="select order_id,bunding_id,order_type,order_code,order_state,use_points,order_amount,add_time from mall_order
        	where buyer_id in(select member_id from mall_member where parent_id=$uid and user_type_id=1) order by add_time desc  limit $page,$this->page ";
        
        }else{
        	$sql="select order_id,bunding_id,order_type,order_code,order_state,use_points,order_amount,add_time from mall_order
        	where buyer_id=$uid  and order_state in(".$arr[$type].") order by add_time desc  limit $page,$this->page ";
        }
       
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
        
        //new
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
        	//是否是
        	$pid=$v['bunding_id'];
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
     * 抢红包
     */
    public  function  envelope(){
        $logger = Logger::getLogger(basename(__FILE__));
        $params = array(array("eid",true),array("ss",true));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        $user=$this->user;
        $uid=$user['member_id']; //用户id
        $model=M('User');
        check_role($user,1);
        $m=$model->envelope($user,$params['eid']);
        ErrCode::echoJson(1,'成功',array('money'=>$m));


    }
    
    /**
     * 积分收益记录
     * @param unknown $serial_str
     * @return mixed
     */
    public  function  PointLog(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$params = array(array("page",false),array("ss",true),array('type',true),array('member_id',false));
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
    	if(isset($params['member_id'])){
    		$uid=$params['member_id'];
    	}else{
    		$uid=$user['member_id']; //用户id
    	}
    	$model=M('User')->conn;
    	$type=$params['type'];
    	if($type==1){
    	    $sql="select * from mall_points_record where member_id=$uid and points>0 order by create_date desc limit $page,$this->page";
    	}else{
    		$sql="select * from mall_points_record where member_id=$uid and points<0 order by create_date desc limit $page,$this->page";
    		
    	}
    	ErrCode::echoJson(1,'成功',$model->getAll($sql));
    	
    }
    public function mb_unserialize($serial_str) {
    	@$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
    	return unserialize($out);
    }
    	
    
    /*
     * 1.2.7. Member/getActivityInfo（获取活动详情）
     */
    public function getActivityInfo(){
    	
    	$logger = Logger::getLogger(basename(__FILE__));
    	$params = array(array("ss",true),array("id",true),array("page",false));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	//获取参数
       $id = $params["id"];  //活动id
       //获取参数
       if(isset($params['page'])){
       	$page=($params['page']-1)*($this->page);
       	 
       }else{
       	$page=0;
       }
     
       //这个类是继承了session验证那个类的，所以下面的代码是session中存放的所有数据
    	$user=$this->user;
    	//获取用户的对应的会员id     从mall_member 这个表中找出对应关系
    	$memberid=$user['member_id'];
    	//实现业务逻辑 ，根据活动id获取活动的详细信息
    	$conn=M()->conn;
    	$id=$params['id'];
    	//查询对应的参加的人啊 
         $sql="select a.member_id, b.member_truename,c.thumbnail_image_url from mall_user_version_info as c , 
               mall_member as b,
    	       mall_activity_belong_member as a where a.activity_id=$id and b.member_id=a.member_id and c.user_id=b.member_id
    	       order by a.create_time desc  limit $page,$this->page";			 		
    	$result=$conn->getAll($sql);
    	ErrCode::echoJson(1,'成功',$result);
    

    }
    
    
    /*
     * 1.2.21. Member/activityList（获取活动列表）
     */
    public function activityList(){
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
    	//这个类是继承了session验证那个类的，所以下面的代码是session中存放的所有数据
    	$user=$this->user;
    	//获取用户的对应的会员id     从mall_member 这个表中找出对应关系
    	$memberid=$user['member_id'];
    	//实现业务逻辑 ，根据活动id获取活动的详细信息
    	$agency_id=$user['agency_id'];
    	$sql="SELECT
			activity_id,
			activity_title,
			CASE WHEN  activity_start_date>NOW() THEN 0
			WHEN   activity_end_date>NOW() AND activity_start_date<NOW() AND max_num>now_num THEN 1
			WHEN   activity_end_date>NOW() AND activity_start_date<NOW() AND max_num<=now_num THEN 2
			ELSE  3
			END AS state,
 			activity_banner,
 			activity_desc,
 			max_num,
 			use_point,
 			activity_type,
 			now_num
			FROM
			mall_activity_info		
    		where agency_id=$agency_id and activity_state=1 limit $page,$this->page";
    	$result=M()->conn->getAll($sql);
    	
    	ErrCode::echoJson(1,'成功',$result);
    	
    	
    	
    }
    
    /*
     * 1.2.22. Member/joinActivity（参加活动）
     */
    
   public function  joinActivity(){
    	$logger = Logger::getLogger(basename(__FILE__));
    	$params = array(array("ss",true),array("aid",true));
    	$params = Filter::paramCheckAndRetRes($_POST, $params);
    	if(!$params){
    		$logger->error(sprintf("params is err. params is %s",v($_POST)));
    		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
    	}
    	//获取参数
       $aid=$params['aid']; //要参加的活动的id
    	//这个类是继承了session验证那个类的，所以下面的代码是session中存放的所有数据
    	$user=$this->user;
    	//获取用户的对应的会员id     从mall_member 这个表中找出对应关系
    	$memberid=$user['member_id']; //该登陆用户的id
    	//实现业务逻辑 ，根据活动id获取活动的详细信息
    	//UNIX_TIMESTAMP()
    	$sql="SELECT
			CASE WHEN  activity_start_date>NOW() THEN 0
			WHEN   activity_end_date>NOW() AND activity_start_date<NOW() AND max_num>now_num THEN 1
			WHEN   activity_end_date>NOW() AND activity_start_date<NOW() AND max_num<=now_num THEN 2
			ELSE  3
			END AS state
			FROM
			mall_activity_info		
    		where activity_id=$aid ";
    	$conn=M()->conn;
    	$state=$conn->getOne($sql);
    	$arr=array(
    		'0'=>'ACTIVITY_NOT_START',
    		'2'=>'ACTIVITY_ISFULL_PEOPLE',
    		'3'=>'ACTIVITY_IS_ENDED'		
    	);
    	if(in_array($state,$arr)){
    		ErrCode::echoErr(ErrCode::$arr[$state],1);
    	}
    	$model=$conn;
    	$model->startTrans();
    	//开始报名了啊
    	$sql="update mall_activity_info set now_num=now_num+1 where activity_id=$aid";
    	if(!$model->execute($sql)){
    		$model->completeTrans(false);
    		$logger->error('update max_num fail'.$sql);
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	}
    	
    	//写活动表对应表哎
    	
    	//开始报名了啊
    	$sql=insert_sql('mall_activity_belong_member',
    		 array('member_id'=>$user['member_id'],'activity_id'=>$aid,'create_time'=>date('Y-m-d H:i:s')));
    	if(!$model->execute($sql)){
    		$model->completeTrans(false);
    		$logger->error('add people fail'.$sql);
    		ErrCode::echoErr(ErrCode::YOU_HAVE_JOIN_THIS,1);
    	}
    	//减去积分
    	$pints=$model->getOne("SELECT
    		use_point	
			FROM
			mall_activity_info		
    		where activity_id=$aid");
    	//积分够吗
    	if($user['member_points']<$pints){
    		ErrCode::echoErr(ErrCode::API_ERR_POINTS_IS_LOW,1);
    	}
    	$sql="update mall_member set member_points=member_points-$pints where member_id=".$user['member_id'];
    	if(!$model->execute($sql)){
    		$model->completeTrans(false);
    		$logger->error('update points fail'.$sql);
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	}
    	//积分记录
    	$sql=add_points_log($user['member_id'],-$pints,'参加活动消费',8,$aid);
    	if(!$model->execute($sql)){
    		$model->completeTrans(false);
    		$logger->error('update log fail'.$sql);
    		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
    	}
    	$model->completeTrans(true);
    	ErrCode::echoJson(1,'成功');
      
    	
    }
    
     
    
    
    
 }
