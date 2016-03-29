<?php
/**
 * 1.7.5. get_public_welfare_activity_people.php (获取参加公益活动人员)
 */
function getPublicWelfareActivityPeople($userId,$activity_id){
	$obj = new DatabaseManager();
	$db = $obj->getConn();
	$sql="select  a.welfare_id,a.user_id,a.createDate,b.user_name as name,c.thumbnail_image_url as url,c.image_ver as pav,c.base_ver as piv,d.sex_id as sex,d.skills,d.live_place
	   from  sys_public_welfare_people_info as a
	   LEFT JOIN sky_user_data_master.user_base_info as b  On a.user_id=b.user_id
	   LEFT JOIN sky_user_data_master.user_version_info as c On a.user_id=c.user_id
	   LEFT JOIN sky_user_data_master.sky_user_extend_info as d On a.user_id=d.user_id
	   where welfare_id='$activity_id'
		ORDER BY a.createDate DESC
	";

	  return $db->getAll($sql);
	

}

?>