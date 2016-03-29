<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-23
 * Time: 上午9:02
 */

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
include_once dirname(__FILE__) . "/common/TcpConnection.php";
require_once dirname(__FILE__) . "/common/MMSFileManager.php";
include_once dirname(__FILE__) . "/service/repos_create_rescue_scene.php";

//获取参数
$params = array(
    array("ss",true),array("title",false),array("ct",true),
    array("longitude",true),array("latitude",true),
    array("address",true),array("upload",false)
);

$params = Filter::paramCheckAndRetRes($_POST, $params);

if(!$params){
    logger()->error(sprintf("params error. params is %s",v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

$config = new Config();
$max_file_size = $config->getLocalConfig('max_file_size');
$uploadPath = $config->getLocalConfig('upload_path');
$databaseManager = new DatabaseManager();
$fileManager = new MMSFileManager();

//数据库链接
if (!$db = $databaseManager->getConn()) {
    logger()->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}

//session处理
$sessionArr = $databaseManager->checkSession(trim($params["ss"]));

//echo 'SessionArr返回值为:'.$sessionArr;

if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",$params["ss"]));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}
$userId = (int)$sessionArr['user_id'];

//获取参数
$paramArr['user_id'] = $userId;
$paramArr['title'] = trim($params['title']) ?: '';
$paramArr['ct'] = removeEmoji(strip_tags(trim($params["ct"])));
$paramArr['longitude'] = trim($params['longitude']);
$paramArr['latitude'] = trim($params['latitude']);
$paramArr['address'] = trim($params['address']);

if (isset($_FILES['upload']) && !$_FILES['upload']['error'] &&
    ($_FILES['upload']['size'] < $max_file_size ) ) {

    $uploadName = $_FILES['upload']['name'];
    $uploadTempName = $_FILES['upload']['tmp_name'];
    $uploadSize = $_FILES['upload']['size'];

    $tempDir = '/tmp/'.$userId.rand(1000,9999);

    if(mkdir($tempDir)){
        $uploadTempZip = $tempDir.'/'.$uploadName;
    }

    move_uploaded_file($uploadTempName, $uploadTempZip);

    $zip = new ZipArchive;


    if($zip->open($uploadTempZip)){
        $imgNameArr = array();

        for($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            $imgNameArr[$i] = $name;
        }
        $zip->extractTo($tempDir);
    }

    $zip->close();

    unlink($uploadTempZip);

}

$uploadRescuesPath = $uploadPath.'/rescues/';

$rid = createRescueScene($paramArr);

//上传图片压缩包逻辑
if($rid && $imgNameArr){

    foreach ($imgNameArr as $name) {

        $rand = md5(time().mt_rand(0,1000));
        $pathInfo = pathinfo($tempDir.'/'.$name);

        $ext = '.'.$pathInfo['extension'];
        $fileName = getimagesize($tempDir.'/'.$name);

        $desc = $uploadRescuesPath.$rand.$ext;

        $imgType= $fileName['mime'];
        if('image' == substr($imgType,0,5)){
            $res1 = copy($tempDir.'/'.$name,$_SERVER['DOCUMENT_ROOT'].'/'.$desc);

            if (!$res1) {
                ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
            }
            $res2 = saveRescueSceneImage($rid,$desc);
            if(!$res2){
                delRescueScene($rid);
                ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
            }

            unlink($tempDir.'/'.$name);
        }
    }

    rmdir($tempDir);
}


ErrCode::echoJson('1','success',array());

//函数部分
function logger()
{
    return Logger::getLogger(basename(__FILE__));
}

function removeEmoji($text) {

    $clean_text = "";

    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);

    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);

    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);

    return $clean_text;
}
