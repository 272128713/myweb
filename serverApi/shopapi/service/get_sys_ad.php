<?php
/*
 *  1.2.25. get_sys_ad.php （获取系统广告）
*/
function get_sys_ad($userID,$type){
	$obj = new DatabaseManager();
	$db = $obj->getConn();
	//首先查看该用户是否具有志愿者资质
	$sql_sel = "select id as ad_id, img_url,target_url  from sky_first_aid.first_aid_ad  where type='$type'";
	return $db->getAll($sql_sel);
	
	

}

?>