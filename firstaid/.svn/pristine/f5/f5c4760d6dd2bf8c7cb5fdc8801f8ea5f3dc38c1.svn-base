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
require_once dirname(__FILE__) . "/common/MMSFileManager.php";
require_once dirname(__FILE__) .'/service/repos_save_rescue_scene_image.php';

$config = new Config();
$max_file_size = $config->getLocalConfig('max_file_size'); //获取配置文件中允许上传的文件最大值（这里只允许上传.zip的压缩包）
$uploadPath = $config->getLocalConfig('upload_path');
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
if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",$session));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}
$userId = (int)$sessionArr['user_id'];


if(!isset($_FILES['upload'])){
    $databaseManager->destoryConn();
    logger()->error(sprintf("no file params error."));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}


if(0 != $_FILES['upload']['error']){
    $databaseManager->destoryConn();
    logger()->error(sprintf("no file params error."));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

if($_FILES['upload']['size'] > $max_file_size){
    $databaseManager->destoryConn();
    logger()->error(sprintf("File size exceeds the limit. max_file_size=%s",$max_file_size));
    ErrCode::echoErr(ErrCode::API_ERR_FILE_TOO_LARGE,1);
}


$uploadName = $_FILES['upload']['name'];
$uploadTempName = $_FILES['upload']['tmp_name'];
$uploadSize = $_FILES['upload']['size'];

$tempDir = '/tmp/'.$userId.rand(1000,9999);

if(mkdir($tempDir)){
    $uploadTempZip = $tempDir.'/'.$uploadName;
}

if(!move_uploaded_file($uploadTempName, $uploadTempZip)){  //$uploadTempName就是一个文件, $uploadTempZip就是一个文件夹
    $logger->error(sprintf("New Content move file error.fileTempName=%s , tFileName=%s",$uploadTempZip,$tempDir));
    ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
}
$zip = new ZipArchive;
if(!$zip->open($uploadTempZip)){
    $logger->error(sprintf("Open contact zip file error. filename=%s",$uploadTempZip));
    ErrCode::echoErr(ErrCode::FILE_SEND_ERR);
}

$imgNameArr = array();
for($i = 0; $i < $zip->numFiles; $i++) {  //$zip->numFiles 获取该压缩包中被压缩的文件的个数
    $name = $zip->getNameIndex($i);
    $imgNameArr[$i] = $name;
}

if(!$extract = $zip->extractTo($tempDir)) {
    $logger->error(sprintf("Unzip contact file error. filename=%s", $tempDir));
    ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
}
$zip->close();
unlink($uploadTempZip);


$uploadRescuesPath = $uploadPath.'/rescues/';

foreach ($imgNameArr as $name) {

    $rand = md5(time().mt_rand(0,1000));

    $pathInfo = pathinfo($tempDir.'/'.$name);

    $ext = '.'.$pathInfo['extension'];

    $fileName = getimagesize($tempDir.'/'.$name);

    $desc = $uploadRescuesPath.$rand.$ext;

    $imgType= $fileName['mime'];
    if('image' == substr($imgType,0,5)){
        $result = copy($tempDir.'/'.$name,$_SERVER['DOCUMENT_ROOT'].'/'.$desc);
        if($result){
            $res = saveRescueSceneImage($rsid,$desc);
            if(!$res){
                ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
            }
        }
        unlink($tempDir.'/'.$name);
    } else {
        ErrCode::echoJson(ErrCode::SYSTEM_ERR,'failed',array());
    }
}
rmdir($tempDir);

ErrCode::echoJson('1','success',array());

function logger()
{
    return Logger::getLogger(basename(__FILE__));
}

