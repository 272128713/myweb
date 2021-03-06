<?php
/*
 * 获取公益活动详情
*/
function joinPublicWelfareActivity($userID,$activity_id){
	$obj = new DatabaseManager();
	$db = $obj->getConn();
	//首先查看该用户是否具有志愿者资质
	$sql_sel = "select privilege_id  from sky_user_data_master.user_base_info  where user_id='$userID'";
	$result1=$db->getOne($sql_sel);
	if(substr($result1,3,1) != '1'){  //0001000000 志愿者
		return 1;   //该用户没有志愿者资质
	}
	//判断该活动是否已经结束
	$sql_endtime = "select end_time from sys_public_welfare_info where id='$activity_id' ";
	$result2=$db->getOne($sql_endtime);
	if(strtotime($result2) <= strtotime(date("Y-m-d H:i:s"))){
		return 2;//该活动过期了
	}
	
	//判断该参加的人数是否已经满了
	$sql_num="select  a.welfare_id,a.user_id,a.createDate,b.user_name as name,c.image_ver as pav,c.base_ver as piv,d.sex_id as sex,d.skills,d.live_place
	from  sys_public_welfare_people_info as a
	LEFT JOIN sky_user_data_master.user_base_info as b  On a.user_id=b.user_id
	LEFT JOIN sky_user_data_master.user_version_info as c On a.user_id=c.user_id
	LEFT JOIN sky_user_data_master.sky_user_extend_info as d On a.user_id=d.user_id
	where welfare_id='$activity_id'
	ORDER BY a.createDate DESC
	";
	$res_num = $db->getAll($sql_num);
	$now_people = count($res_num);
	$sql_n="select people_num from sys_public_welfare_info where id='$activity_id'";
	$result =  $db->getOne($sql_n);
	
	if($now_people+1 > $result ){
		
		return 3;  //该活动的人数已经满了
	}
	$sql_join = "select * from sys_public_welfare_people_info where  welfare_id='$activity_id' and user_id='$userID'";
	$res_join = $db->getRow($sql_join);
	if($res_join){
		return 5; //你已经参加该活动了，不能重复参加
	}
	//插入该人员信息进入活动列表
	$sql="replace into sys_public_welfare_people_info(welfare_id,user_id,createDate) values('$activity_id','$userID',now())";
	$res = $db->execute($sql);
    if($res){
    	
    	return 4;
    }


}

?>