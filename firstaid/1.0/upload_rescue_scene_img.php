<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-23
 * Time: 上午9:03
 */
include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/RKMongo.php";
require_once dirname(__FILE__) .'/service/repos_save_rescue_scene_image.php';
require_once dirname(__FILE__) . "/common/MMSFileManager.php";

$config = new Config();
$max_file_size = $config->getLocalConfig('max_file_size');
$databaseManager = new DatabaseManager();
$fileManager = new MMSFileManager();
$rkMongo = new RKMongo();

//数据库链接
if (!$db = $databaseManager->getConn()) {
    logger()->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}

//参数过滤
$params = array(array("ss",true),array("rsid",true));
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){

    logger()->error(sprintf("params error. params is %s",v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

$session = trim($params["ss"]);
$rsid = trim($params["rsid"]);



//验证session{}
$sessionArr = $databaseManager->checkSession($session);

//var_dump($sessionArr);

if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",$session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}
$userId = (int)$sessionArr['user_id'];

//var_dump($_FILES);
//上传文件部分
if(!isset($_FILES['file'])){
    $databaseManager->destoryConn();
    logger()->error(sprintf("no file params error."));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

if('image' != substr($_FILES['file']['type'],0,5)){

    $databaseManager->destoryConn();
    logger()->error(sprintf("no file params error."));
}
if(0 != $_FILES['file']['error']){
    $databaseManager->destoryConn();
    logger()->error(sprintf("no file params error."));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

if($_FILES['file']['size'] > $max_file_size){
    $databaseManager->destoryConn();
    logger()->error(sprintf("File size exceeds the limit. max_file_size=%s",$max_file_size));
    ErrCode::echoErr(ErrCode::API_ERR_FILE_TOO_LARGE,1);
}

$fileName = $_FILES['file']['name'];
$fileTempName = $_FILES['file']['tmp_name'];
$fileSize = $_FILES['file']['size'];

$saveFileResult = $fileManager->uploadFile(
    $_FILES['file']['tmp_name'],
    $_FILES['file']['name'],
    array('mime'=>'exam_image','user_id'=>$userId)
);

if(!$saveFileResult){
    $databaseManager->destoryConn();
    logger()->error(sprintf("uploadFile():Upload file failed. filename=%s, filesize=%s",$fileName,$fileSize));
    ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
}

//var_dump($saveFileResult);

//$result = saveRescueSceneImage($rsid,$saveFileResult);

//var_dump($result);
$databaseManager->destoryConn();

if(!saveRescueSceneImage($rsid,$saveFileResult)){
    //$logger->error(sprintf("saveRescueSceneImage to DB file failed. filename=%s, filesize=%s",$_FILES['file']['name'],$_FILES['file']['size']));
    ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
}

echo json_encode(array('code'=> 1,'msg'=>'','result'=>array()));

//$logger->info("uploadFile:Upload file success. filename:{$_FILES['file']['name']}, filesize:{$_FILES['file']['tmp_name']}");

function logger()
{
    return Logger::getLogger(basename(__FILE__));
}

