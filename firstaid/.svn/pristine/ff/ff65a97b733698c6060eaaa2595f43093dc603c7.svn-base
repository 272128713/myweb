<?php
/*
 * 1.7.7. get_postulant_by_province.php （按省份查询志愿者信息）
*/

function get_postulant_by_province($userID,$province){
	$obj = new DatabaseManager();
	$db = $obj->getConn();
	$province=substr($province, 0, 2);
	$sql="select * from (select a.user_id,ifnull(left(a.live_province_id,2),'' ) as provin,a.sex_id as sex,a.skills,a.live_place,b.user_name as name,c.createDate,d.image_ver as pav,d.base_ver as piv
			from sky_user_data_master.sky_user_extend_info as a
            LEFT JOIN sky_user_data_master.user_base_info as b On a.user_id = b.user_id
			LEFT JOIN sky_user_data_master.sky_postulant_info as c On c.user_id = a.user_id
			LEFT JOIN sky_user_data_master.user_version_info as d On d.user_id = a.user_id where b.privilege_id='1001000000' ) as c
			where  c.provin='$province' ";
	 return $db->getAll($sql);

	

}

?>