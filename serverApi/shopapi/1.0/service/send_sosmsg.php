<?php
/*
 * 1.2.18. send_sosmsg.php（发送急救信息）
*/
//获取救命稻草
function send_sosmsg($user_id,$mobile){ //这里的$user_id是求救者的id
	$obj = new DatabaseManager();
	$db = $obj->getConn();
// 	$sql="select user_id  from sky_user_data_master.user_base_info 
// 		     where mobile in (
//                      select contact_number  from  mobile_contact  where user_id in
//                      (select friend_id from user_friend_list where user_id='$user_id')
//                  )";

	
 	$sql="select friend_id as user_id from sky_first_aid.user_friend_list where user_id='$user_id' and is_friend=1";

	return  $db->getAll($sql);
	
// 	$sql="select c.user_id  from sky_user_data_master.user_base_info as c  
// 	     where c.mobile in (select b.contact_number from sky_user_data_master.user_base_info as a
// 			LEFT JOIN mobile_contact as b On a.user_id=b.user_id
// 			LEFT JOIN user_friend_list as c On a.user_id=c.user_id
// 			where a.user_id='$user_id')
// 			";
	

}

//获取用户的头像版本号
function  send_sosmsg_getpav($user_id){;
	$obj = new DatabaseManager();
	$db = $obj->getConn();
	$sql="select image_ver  as pav from sky_user_data_master.user_version_info  where user_id='$user_id'";
	return  $db->getOne($sql);

}



//获取紧急联系人
function  send_sosmsg_linkman($user_id,$mobile){
	$obj = new DatabaseManager();
	$db = $obj->getConn();
	$sql="select linkman_id as user_id from user_rescue_card_linkman_info where user_id='$user_id'";
	return  $db->getAll($sql);
	
}


?>