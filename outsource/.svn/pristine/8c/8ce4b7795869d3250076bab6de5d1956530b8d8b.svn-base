<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/22
 * Time: 11:45
 */

class MsgModel extends  Model{
    /**
     * 获取健康圈会员
     */
    public  function  getMembers($user){
        $role=$user['user_type_id'];
        $uid=$user['member_id'];
        //会员
        if($role == 1){
            $parent=$user['parent_id'];
            $sql = "select member_id  as friend_id from mall_member where parent_id=$parent and user_type_id=1
                    and member_id!=$uid
                    ";
        }else{
            //员工
            $sql = "select member_id  as friend_id from mall_member where parent_id=$uid and user_type_id=1";

        }
        $result = $this->getGroupConn()->getAll($sql);
        $returnArr =array();
        foreach($result as $line)
        {
            $tmpArr = $line['friend_id'];
            array_push($returnArr,$tmpArr);
        }

        if($role==1){
            $returnArr[]=$parent;
        }
        return $returnArr;
    }
    
    /**
     * 获取健康圈会员
     */
    public  function  getMembersM($user){
    	$role=$user['user_type_id'];
    	$uid=$user['member_id'];
    	//会员
    	if($role == 1){
    		$parent=$user['parent_id'];
    		$sql = "select member_id  as friend_id from mall_member where parent_id=$parent and user_type_id=1
    		
    		";
    	}else{
    	//员工
    		$sql = "select member_id  as friend_id from mall_member where agency_id=$uid and user_type_id=1";
    		
            
    	}
    	$result = $this->getGroupConn()->getAll($sql);
    	$returnArr =array();
    	foreach($result as $line)
    	{
    	$tmpArr = $line['friend_id'];
    	array_push($returnArr,$tmpArr);
    	}
    
        if($role==3){
    		$returnArr[]=$user['member_id'];
    	}
    	return $returnArr;
    	}

    /**
     * 检查是否有权限发消息
     */

    public  function  checkGroupMember($uid){
        $arr=$this->getMembers($uid);
        if(in_array($uid,$arr)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取健康圈人
     */
    public  function  getFriends($user){
        $role=$user['user_type_id'];
        $uid=$user['member_id'];
        //会员
        if($role == 1){
            $parent=$user['parent_id'];
            $sql = " select member_id  as friend_id from mall_member where parent_id=$parent and user_type_id=1 and member_id!=$uid ";

        }else{
            //员工
            $sql = "select member_id  as friend_id from mall_member where parent_id=$uid and user_type_id=1";

        }
        $result = $this->getGroupConn()->getAll($sql);
        $returnArr =array();
        foreach($result as $line)
        {
            $tmpArr = $line['friend_id'];
            array_push($returnArr,$tmpArr);
        }

        return implode(',',$returnArr) ;
    }

    /**
     * 查询健康圈数据
     */
    public  function checkUser($user){
        $frend=$this->getFriends($user);
        $role=$user['user_type_id'];
        $uid=$user['member_id'];
         //会员
        if($role == 1){
                $parent=$user['parent_id'];

                $return=$this->checkByid($frend);
                $selfs=$this->checkByid($uid,0);
                $return[]=$selfs[0];
                $worekr=$this->checkByid($user['parent_id'],2);
                $worekr=$worekr[0];
                $sql="select a.shop_name from mall_shop as a, mall_member as b where b.member_id=$parent and a.shop_id=b.store_id";
                $worekr['shop_name']=$this->conn->getOne($sql);
                $return[]=$worekr;

        }else{

            $return=$this->checkByid($frend);
            $selfs=$this->checkByid($uid,0);
            $return[]=$selfs[0];

        }

        return $return;
    }

    /**
     * 查询自己的健康圈
     * @param $mode
     * @param int $type
     * @return array
     * @throws Exception
     */
    public  function  checkByid($mode,$type=1){
        $sql="select a.member_truename,a.member_id,a.member_name as mobile ,a.user_type_id as role,$type as check_type,'' as shop_name, b.base_ver piv,b.image_ver pav,b.thumbnail_image_url from mall_member
                as a LEFT JOIN  mall_user_version_info as b
                on b.user_id=a.member_id where a.member_id IN ($mode) ";
        return $this->conn->getAll($sql);
    }
}