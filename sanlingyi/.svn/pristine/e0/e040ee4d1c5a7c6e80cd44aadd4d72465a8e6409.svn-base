<?php 
function getNewID()
{
	$ID="1".rand(100, 999).rand(100, 999).rand(100, 999);
	$ID=$ID*1;
	return $ID;
}
//取医院数组
function getHospitalArr($doctor_id){	

	$opt=D('Common');
	$dbs=$opt->getDB();
	$db=$dbs['db'][1]['link'];
	
	$data=M('com_dic_doctor_info','',$db)->field('province,city,district,hospital')->where('id='.$doctor_id)->find();
	
	$result['province']=M('com_sic_region_info','',$db)->where('id = '.$data['province'])->getField('name');		
	$result['city']=M('com_sic_region_info','',$db)->where('id = '.$data['city'])->getField('name');
	$result['area']=M('com_sic_region_info','',$db)->where('id = '.$data['district'])->getField('name');
	$result['hospital_name']=$data['hospital'];
	$result['province_id']=$data['province'];
	$result['city_id']=$data['city'];
	$result['district_id']=$data['district'];
	
	$result['hospital']=$result['province'].'_'.$result['city'].'_'.$result['area'].'_'.$result['hospital_name'];
	if($result['hospital_name']==''){
		$result['hospital']='';
	}		
	return $result;
}
//取医院数组
function getHospitalArr_bak($hospital){
	$opt=D('Common');
	$dbs=$opt->getDB();
	$db=$dbs['db'][1]['link'];
	if($hospital != ''){
		$hosArr=explode('-',$hospital);
		$result['province']=M('hat_province','',$db)->where('provinceID = '.$hosArr[0])->getField('province');
		$result['city']=M('hat_city','',$db)->where('cityID = '.$hosArr[1])->getField('city');
		$result['area']=M('hat_area','',$db)->where('areaID = '.$hosArr[2])->getField('area');
		$result['province_id']=$hosArr[0];
		$result['city_id']=$hosArr[1];
		$result['area_id']=$hosArr[2];
		$result['hospital_name']=$hosArr[3];
		$result['hospital']=$result['province'].'_'.$result['city'].'_'.$result['area'].'_'.$result['hospital_name'];
	}else{
		$result['hospital']='';
	}
	return $result;
}
//解析地址数据
function getAddressArr($data){

	$opt=D('Common');
	$dbs=$opt->getDB();
	$db=$dbs['db'][1]['link'];
	$dbc=M('com_sic_region_info','',$db);
		
	$address=explode('-', $data);	
	$result['province']=$dbc->where('id = '.$address[0])->getField('name');
	$result['city']=$dbc->where('id = '.$address[1])->getField('name');
	$result['area']=$dbc->where('id = '.$address[2])->getField('name');
	$result['address_name']=$address[3];
	$result['province_id']=$address[0];
	$result['city_id']=$address[1];
	$result['district_id']=$address[2];

	$result['address']=$result['province'].'_'.$result['city'].'_'.$result['area'].'_'.$result['address_name'];
	if($result['address_name']==''){
		$result['address']='';
	}
	return $result;
}
//字符串截取
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
	if(function_exists("mb_substr")){
		if ($suffix && strlen($str)>$length)
			return mb_substr($str, $start, $length, $charset)."...";
		else
			return mb_substr($str, $start, $length, $charset);
	}
	elseif(function_exists('iconv_substr')) {
		if ($suffix && strlen($str)>$length)
			return iconv_substr($str,$start,$length,$charset)."...";
		else
			return iconv_substr($str,$start,$length,$charset);
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice."…";
	return $slice;
}
//操作记录
function actionLog($level=0,$info='',$remark=''){	
	
	$opt=D('Common');
	$dbs=$opt->getDB();
	$db=$dbs['db'][1]['link'];
	
	$dbc=M('action_log','',$db);	
	$data['create_time']=date('Y-m-d H:i:s',time());
	$data['level']=$level;
	$data['info']=$info;
	$data['remark']=$remark;
	$data['employee_id']=$_SESSION['SESS_EmployeeInfo']['id'];	
	$dbc->add($data);
	//echo $dbc->getLastSql();
}



?>
