<?php
/**
 * 获取医院信息
 *
 * @param
 *        	string section
 */
 function get_sys_hospital_info($section, $type) {
 	
 	$obj = new DatabaseManager();
 	$db = $obj->getConn();
 	
	if ($type == 0) {
		$sql = "select province,city,section,name from sky_user_data_master.hospital_base_info_sys where province='$section'";
	}
	if ($type == 1) {
		$sql = "select province,city,section,name from sky_user_data_master.hospital_base_info_sys where city='$section'";
	} else {
		$sql = "select province,city,section,name from sky_user_data_master.hospital_base_info_sys where section='$section'";
	}

	$result =$db->getAll ( $sql );
	return $result;
}
 
?>