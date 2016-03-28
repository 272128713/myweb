<?php
class AgencyModel extends Model{
	/**
	 * 获取地区名字
	 */
	public  function getArea($id){
		$sql="select name from mall_com_sic_region_info where id=$id";
		return  $this->conn->getOne($sql);
	}
	
	/**
	 * 根据经销商返回店铺
	 */
	public  function getShop($id){
		$sql="select shop_id,member_truename as manage_name,shop_address,shop_name,shop_money,shop_member_num,shop_woker_num,FROM_UNIXTIME(shop_time,'%Y-%m-%d') as shop_time from mall_shop_view where agency_id=$id and
			  shop_state=1 order by shop_money desc";
		
		return  $this->conn->getAll($sql);
	}
	
	/**
	 * 获取仅销售前10
	 */
	public  function getAgency(){
		$sql="select member_truename,member_address,agency_money from mall_member where user_type_id=3 order by agency_money desc limit 10";
		return  $this->conn->getAll($sql);
	}

    /**
     * 添加员工
     */
    public  function  addWorker($worker,$lid,$name){
        $pid=$worker['member_id'];
        $this->conn->startTrans();
        $times=time();
        $sql="insert into mall_member (user_type_id,parent_id,member_name,store_id,member_time)
                  VALUES(2,$pid,$name,$lid,$times)";
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
        $sql="select shop_woker_num from mall_shop WHERE  shop_id=".$lid;
        $oldnum=$this->conn->getOne($sql);
        $new_num=$oldnum+1;
        $sql="update mall_shop set shop_woker_num=$new_num WHERE shop_id=". $lid;
        //更新员工数量
        if(!$this->conn->execute($sql)){
            $this->conn->completeTrans(false);
            $this->logger->error(sprintf("update num fail.".$sql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }

        $this->conn->completeTrans(true);


    }

    /**
     * 提现
     */
    public  function  applyMoney($user,$money){
        $agency_id=$user['member_id'];
        $bank_name=$user['bank_name'];
        $bank_num=$user['bank_num'];
        $account_name=$user['account_name'];
        $apply_time=date('Y-m-d H:i:s');
        $sql="insert into mall_apply_money (agency_id,money,bank_name,bank_num,account_name,apply_time,apply_status)
              VALUES($agency_id,$money,'$bank_name','$bank_num','$account_name','$apply_time',0)";
        if(!$this->conn->execute($sql)){
            $this->conn->completeTrans(false);
            $this->logger->error(sprintf("add change_log fail.".$sql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        $sql="update mall_member set agency_money=agency_money-$money where member_id=$agency_id";
        if(!$this->conn->execute($sql)){
            $this->conn->completeTrans(false);
            $this->logger->error(sprintf("add money fail.".$sql));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        $this->conn->completeTrans(true);
    }
	
	
}