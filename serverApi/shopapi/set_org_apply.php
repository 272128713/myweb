<?php
/*
 * 空中急救模块API
 * 1.3.6. set_org_apply.php (提交机构申请表)
 */
include(dirname(__FILE__) . "/common/inc.php");
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("ss",true),array("name",true),array("logo_url",true),array("juridical_person",true),array("build_time",true),array("features",true),array("summary",true),array("address",true),array("phone",true),array("official_site",true));

//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

//获取参数
$ss = $params['ss'];
$name = $params['name'];
$logo_url = $params['logo_url'];
$juridical_person = $params['juridical_person'];
$build_time = $params['build_time'];
$features = $params['features'];
$summary = $params['summary'];
$address = $params['address'];
$phone = $params['phone'];
$official_site = $params['official_site'];


$databaseManager = new DatabaseManager();
$dbMaster = $databaseManager->getConn(); //连接sky_first_aid



//数据库链接失败

if(!$dbMaster){
	$logger->error(sprintf("Database sky_first_aid connect fail."));
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
//验证session{}
$sessionArr = $databaseManager->checkSession($ss);

if(!$sessionArr){
	$logger->error(sprintf(" Session fail."));
	ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);

}

$userId = (int)$sessionArr['user_id'];

//数据逻辑
$sql = "insert into sky_organization_apply (user_id, name, juridical_person, logo_url, build_time, features, summary, address, phone, official_site, apply_time) VALUES ('$userId', '$name', '$juridical_person', '$logo_url', '$build_time', '$features', '$summary', '$address', '$phone', '$official_site', NOW())";
//echo $sql;
$result = $dbMaster->execute($sql);
if(!$result){
    $logger->error(sprintf(" insert fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);

}

ErrCode::echoJson('1','success',array());

?>
































