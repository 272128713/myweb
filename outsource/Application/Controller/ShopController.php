<?php
class  ShopController extends CommonController
{
	/**
	 * 设置店长
	 */
	public  function  setManage(){
		$logger = Logger::getLogger(basename(__FILE__));
		$this->logger=$logger;
		$params = array(array("ss",true),array("shop_id",true),array("member_id",false));
		$params = Filter::paramCheckAndRetRes($_POST,$params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		check_role($user, 4);
		$conn=M('Agency')->conn;
		$this->conn=$conn;
		$shop_id=$params['shop_id'];
		//设置其他人为店长
		if(isset($params['member_id'])){
			$conn->startTrans();
			//查询所设置的人是否为本店非店长员工
			$sql="select member_id from mall_member where store_id=$shop_id and user_type_id=2";
			if(!$conn->getOne($sql)){
				ErrCode::echoErr(ErrCode::API_ERR_NOT_SHOP_WORKER,1);
			}
			if($user['is_manage']==1){
				//干掉自己
				
				$sql="update mall_member set is_manage=0 where member_id=".$user['member_id'];
				if(!$this->conn->execute($sql)){
					$this->conn->completeTrans(false);
					$this->logger->error(sprintf("update manage fail".$sql));
					ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
				}
				
			}else{
				$sql="update mall_member set user_type_id=2 where store_id=$shop_id and user_type_id=4";
				//干掉原理的店长
				if(!$this->conn->execute($sql)){
					$this->conn->completeTrans(false);
					$this->logger->error(sprintf("kill other fail".$sql));
					ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
				}
				
			}
			//设置新的的店长
			$sql="update mall_member set user_type_id=4 where member_id=".$params['member_id'];
			if(!$this->conn->execute($sql)){
				$this->conn->completeTrans(false);
				$this->logger->error(sprintf("kill other fail".$sql));
				ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
			}
			$this->conn->completeTrans(true);
			ErrCode::echoJson(1,'执行成功');
			
		}else{
			//想设置自己为店长
			$conn->startTrans();
			$sql="update mall_member set user_type_id=2 where store_id=$shop_id and user_type_id=4";
			//干掉原来的店长
			if(!$this->conn->execute($sql)){
				$this->conn->completeTrans(false);
				$this->logger->error(sprintf("kill other fail".$sql));
				ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
			}
			//设置自己
			$sql="update mall_member set is_manage=1,store_id=$shop_id where member_id=".$user['member_id'];
			
			if(!$this->conn->execute($sql)){
				$this->conn->completeTrans(false);
				$this->logger->error(sprintf("kill other fail".$sql));
				ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
			}
			if($user['store_id']!=$shop_id){
					//新店加人数
				$sql="update mall_shop set shop_woker_num= shop_woker_num+1 where shop_id=".$shop_id;
				if(!$this->conn->execute($sql)){
					$this->conn->completeTrans(false);
					$this->logger->error(sprintf("reduce num fail".$sql));
					ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
				}
				if(is_null(store_id)){
					//老店减人数
					$sql="update mall_shop set shop_woker_num= shop_woker_num-1 where shop_id=".$user['store_id'];
					if(!$this->conn->execute($sql)){
						$this->conn->completeTrans(false);
						$this->logger->error(sprintf("reduce num fail".$sql));
						ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
					}
				}
			}
			
			$this->conn->completeTrans(true);
			ErrCode::echoJson(1,'执行成功');
		}
		
		
		
	}
	
	/**
	 * 获取店铺列表
	 */	
	public function lists(){
		
		$logger = Logger::getLogger(basename(__FILE__));
		$this->logger=$logger;
		$params = array(array("ss",true));
		$params = Filter::paramCheckAndRetRes($_POST,$params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		check_role($user, 3);
		$conn=M('Agency')->getShop($user['member_id']);
		ErrCode::echoJson(1,'执行成功',$conn);
	}
	
	
	public  function  changeShop(){
		$logger = Logger::getLogger(basename(__FILE__));
		$this->logger=$logger;
		$params = array(array("ss",true),array("shop_id",true),array("member_id",false));
		$params = Filter::paramCheckAndRetRes($_POST,$params);
		if(!$params){
			$logger->error(sprintf("params is err. params is %s",v($_POST)));
			ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
		}
		$user=$this->user;
		check_role($user,3);
		$conn=M('Agency')->conn;
		$this->conn=$conn;
		$member_id=$conn->getRow("select store_id,user_type_id from mall_member where member_id=".$params['member_id']);
		if($member_id['store_id']==$params['shop_id']){
			ErrCode::echoErr(ErrCode::API_ERR_NOT_IPDATE_SHOP,1);
		}
		
		//开始掉职
		$shop_id=$params['shop_id'];
		$conn->startTrans();
		$sql="update mall_member set store_id= $shop_id where member_id=".$params['member_id']; 
		if(!$this->conn->execute($sql)){
			$this->conn->completeTrans(false);
			$this->logger->error(sprintf("update_shop".$sql));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		//减人数
		$sql="update mall_shop set shop_woker_num= shop_woker_num-1 where shop_id=".$member_id['store_id'];
		if(!$this->conn->execute($sql)){
			$this->conn->completeTrans(false);
			$this->logger->error(sprintf("reduce num fail".$sql));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		//加人数
		$sql="update mall_shop set shop_woker_num= shop_woker_num+1 where shop_id=".$params['shop_id'];
		if(!$this->conn->execute($sql)){
			$this->conn->completeTrans(false);
			$this->logger->error(sprintf("reduce num fail".$sql));
			ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
		}
		$this->conn->completeTrans(true);
		ErrCode::echoJson(1,'执行成功');
	}
	
	
}