<?php
/*
 * 获取公益活动详情
*/
function getPublicWelfareActivityInfo($activity_id,$userID){
	$obj = new DatabaseManager();
	$db = $obj->getConn();
	
	$sql="select  a.welfare_id,a.user_id,a.createDate,b.user_name as name,c.image_ver as pav,c.base_ver as piv,d.sex_id as sex,d.skills,d.live_place
	from  sys_public_welfare_people_info as a
	LEFT JOIN sky_user_data_master.user_base_info as b  On a.user_id=b.user_id
	LEFT JOIN sky_user_data_master.user_version_info as c On a.user_id=c.user_id
	LEFT JOIN sky_user_data_master.sky_user_extend_info as d On a.user_id=d.user_id
	where welfare_id='$activity_id'
	ORDER BY a.createDate DESC
	";

	$res = $db->getAll($sql);
	$now_people = count($res);
	$sql_my = "select id from sys_public_welfare_people_info where user_id='$userID' and welfare_id='$activity_id'";
	$my_res = $db->getOne($sql_my);
	
	$sql_n="select * from sys_public_welfare_info where id='$activity_id'";
	$result =  $db->getRow($sql_n);
	if($my_res){
		$result['joined']=1;
	}else{
		$result['joined']=0;
	}

     $result['now_people']=$now_people;
	
	return $result;
	
	
	
	
}

?>