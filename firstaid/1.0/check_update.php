<?php
/*check_update.php
* 功能：检查客户端软件更新
*/
include(dirname(__FILE__) . "/common/inc.php");
$params = array(array("lg",true),array("os",true),array("cv",true),array("flag",false));
//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

$os_language = $params["lg"];     //OS语言
$os_type = $params["os"];         //OS类型，如android,android_extend,iphone
$client_version = $params["cv"];  //客户端软件版本号
if(isset($params["flag"])){
	$flag = $params["flag"];
}else{
	$flag = 0;
}
$databaseManager = new DatabaseManager();
$ipphone_db_link = $databaseManager->getConn();
//数据库链接失败
if(!$ipphone_db_link){
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
// Find IPONE update information
if($os_type=="ios"){
	$sql = "select * from updateinfo where os_language = '$os_language' and os_type = '$os_type' order by upload_date desc";
	$result = mysql_query($sql); 
	if($result!=FALSE){
		if(mysql_num_rows($result)!=0){   // Find macthed updates
			$echo_arr = array();
			while($row = mysql_fetch_array($result)){
				if(version_compare($row['update_version'],$client_version) > 0){
					$echo_arr = array("download_url"=>$row['download_url'],"update_version"=>$row['update_version'],"update_description"=>$row['update_description'],"file_name"=>$row['file_name'],"file_size"=>$row['file_size'],"upload_date"=>$row['upload_date'],"min_version"=>$row['minimal_version'],"apk_size"=>$row['apk_size']);
					break;
				}
			}
			if(count($echo_arr) > 0)
				ErrCode::echoOkArr("1","操作成功",$echo_arr);
			else
				ErrCode::echoErr(ErrCode::API_ERR_NO_UPDATE,1);
		}else{
			ErrCode::echoErr(ErrCode::API_ERR_NO_UPDATE,1);
		}
	}
	else{
		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	} 
}
// Find Android update information
if($os_type=="android"||$os_type=="android_extend"){
	$sql = "select * from updateinfo where os_language = '$os_language' and os_type = 'android' order by upload_date desc";
	$sql_extend = "select * from updateinfo where os_language = '$os_language' and os_type = 'android_extend' and accord_version = '$client_version' order by upload_date desc";
	$result = mysql_query($sql); //普通整包升级检索
	$result_extend = mysql_query($sql_extend);  //增量升级检索
	if($result_extend!=FALSE){
		if(mysql_num_rows($result_extend)!=0){   // Find macthed updates
			$echo_arr = array();
			while($row = mysql_fetch_array($result_extend)){
				if(version_compare($row['update_version'],$client_version) > 0){
					$echo_arr = array("download_url"=>$row['download_url'],"update_version"=>$row['update_version'],"update_description"=>$row['update_description'],"file_name"=>$row['file_name'],"file_size"=>$row['file_size'],"upload_date"=>$row['upload_date'],"min_version"=>$row['minimal_version'],"apk_size"=>$row['apk_size']);
					break;
				}
			}
			if(count($echo_arr) > 0){
				ErrCode::echoOkArr("1","操作成功",$echo_arr);
			}
		}
	}
	if($result!=FALSE){
		if(mysql_num_rows($result)!=0){   // Find macthed updates
			$echo_arr = array();
			while($row = mysql_fetch_array($result)){
				if(version_compare($row['update_version'],$client_version) > 0){
					$echo_arr = array("download_url"=>$row['download_url'],"update_version"=>$row['update_version'],"update_description"=>$row['update_description'],"file_name"=>$row['file_name'],"file_size"=>$row['file_size'],"upload_date"=>$row['upload_date'],"min_version"=>$row['minimal_version'],"apk_size"=>$row['apk_size']);
					break;
				}
			}
			if(count($echo_arr) > 0)
				ErrCode::echoOkArr("1","操作成功",$echo_arr);
			else
				ErrCode::echoErr(ErrCode::API_ERR_NO_UPDATE,1);
		}else{
			ErrCode::echoErr(ErrCode::API_ERR_NO_UPDATE,1);
		}
	}
	else{
		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	}
}	
?>
