// <?php
// /*
//  * 1.2.23. get_user_apply_auth_info.php(获取用户申请资质状态)
//  */
// function getUserApplyAuthInfo($userID,$flag){
// 	$obj = new DatabaseManager();
// 	$db = $obj->getConn();
// 	$sql="select  state from   sky_user_data_master.user_apply_for_aptitude_info  where user_id='$userID' and type='$flag'";
// 	return $db->getOne($sql);
   


// }
// function getPrivilege($userID){
// 	$obj = new DatabaseManager();
// 	$db = $obj->getConn();
// 	$sql="select privilege_id from  sky_user_data_master.user_base_info where user_id='$userID' ";
// 	return $db->getOne($sql);
	 


// }

// function  updatePrivilege($user_id){
	
// 	$obj = new DatabaseManager();
// 	$db = $obj->getConn();
// 	//查询该用户的权限
// 	$sql_privilege = "select privilege_id from sky_user_data_master.user_base_info  where user_id='$user_id'";
// 	$res_privilege = $db->getOne($sql_privilege);
// 	echo $res_privilege;die();
// 	 //申请类型格式：0100 医生   0100000000
// 	$res_privilege = substr($res_privilege,2,8);
// 	$sql_update = "update  user_base_info  set   privilege_id='11".$res_privilege."'  where user_id='$user_id' ";
// 	echo $sql_update;die();
// 	$db->execute($sql_update);
	
// }

// ?>