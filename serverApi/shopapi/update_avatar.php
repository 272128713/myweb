<?php
/**
* 1.2.13. update_avatar.php（更新图像）
*
*Params:
*ss：session（必填）
*file: 上传的文件    (必填)
*
*/
 include dirname(__FILE__) . "/common/inc.php";
include dirname(__FILE__) .'/common/MMSFileManager.php';
$challengeStep = false;
//参数过滤
$params = array(array("ss",true));
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){

    logger()->error(sprintf("params error. params is %s",v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

$session = trim($params["ss"]);
$config = new Config();
$databaseManager = new DatabaseManager();

//数据库链接
if (!$db = $databaseManager->getConn()) {
    logger()->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}

//验证session{}
$sessionArr = $databaseManager->checkSession($session);
if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",$session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}
 $user_id =$sessionArr['user_id'];
 $sso_url=$config->getConfig("sso_url");
 //把客户端的图片上传到中心服务器
$data = array('user_id'=>$sessionArr['user_id'],'file'=>'@'.$_FILES['file']['tmp_name']);
echo $databaseManager->posters_img($sso_url,'update_avatar.php', $data);
	                    
   

?>