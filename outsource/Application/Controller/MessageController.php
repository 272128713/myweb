<?php
header("content-type:text/html; charset=utf-8");
/**
 * 发送消息
 * @author Administrator
 *
 */
class MessageController extends  CommonController{
    public  function  send(){
        include(ROOT_PATH . "/common/MecManager.php");
        include(ROOT_PATH . "/common/MMS_FileManager.php");
        $logger = Logger::getLogger(basename(__FILE__));
        $config = new Config();
        $params = array(array("ss",true),array("uid",true),array("mime",true),array("time",true),array("id",true));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        $tempParams = array_values(array_unique(explode(",", $params["uid"])));
        $uidCount = count($tempParams);
        if($uidCount == 0){
            $logger->error(sprintf("params error. error. member is %s",$params["uid"]));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        $params["uid"] = implode(",",$tempParams);
        $sessionInfo=$this->user;
        $user_id = $sessionInfo["member_id"];
        $mobile = $sessionInfo["member_name"];

        if($params["mime"] == "text/plain"){
            $mime = "text";
            if(!isset($_POST["text"])){
                $logger->error("text is null.");
                ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
            }
            $textContent = trim($_POST["text"]);
            $msgObj = array(
                "id" => $params["id"],
                "type" => "MMS",
                "mime" => $params["mime"],
                "srcm" => $mobile,
                "src" => $user_id,
                "text" => rawurlencode($textContent),
                "time" => time());

            $logger->info("MMS-TYPE:TEXT,MMS-LENGTH:" . strlen(trim($_POST["text"])) . ",MMS-SENDER:" . $mobile . ",MMS-CONTENT:" . rawurlencode($_POST["text"]) . ",MMS-ID:" . $params["id"]);
        }else{

            $params2 = Filter::paramCheckAndRetRes($_POST, array(
                "filename",
                "id",
                "size"
            ));
            if(!$params2){
                $logger->error(sprintf("file mms params error. params=%s",v($_POST)));
                ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
            }
            if(strpos($params["mime"],"video") !== false || strpos($params["mime"],"audio") !== false ){
                if(!isset($_POST["duration"]) || trim($_POST["duration"]) == ""){
                    $logger->error(sprintf("file mms duration error."));
                    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
                }
                $params2["duration"] = $_POST["duration"];
            }
            if(strpos($params["mime"],"audio") !== false){
                $mime = "audio";
            }elseif(strpos($params["mime"],"video") !== false){
                $mime = "video";
            }elseif(strpos($params["mime"],"image") !== false){
                $mime = "image";
            }else{
                $mime = "file";
            }
            //处理文件上传
            if(!isset($_FILES['file']) || !isset($_FILES['file']['error']) || $_FILES['file']['error'] != 0){
                $logger->error(sprintf("no file params error."));
                ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
            }
            if($_FILES['file']['size'] != $params2["size"]){
                $logger->error(sprintf("file size is not equal to size param! file size:".$_FILES['file']['size']." size param:".$params2["size"]));
                ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
            }

            $max_file_size = $config->getConfig("max_file_size");
            if($_FILES['file']['size'] > $max_file_size){
                $logger->error(sprintf("Mms file size exceeds the limit.max_file_size:".$max_file_size));
                ErrCode::echoErr(ErrCode::API_ERR_FILE_TOO_LARGE,1);
            }
            /*****************************得到上传文件的信息********************************************/
            $fileTempName = $_FILES['file']['tmp_name'];//得到临时文件
            $mmsFileManager = new MMS_FileManager();
            $saveFileResult = $mmsFileManager->uploadFile($fileTempName, $params2["id"],array_merge($params,$params2));
            if(!$saveFileResult){
                $logger->error(sprintf("file mms save error."));
                ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
            }
            $fileNameEncode = rawurlencode($params2["filename"]);

            //发送消息通知
            $msgObj = array("id" => $params["id"],
                "type" => "MMS",
                "mime" => $params["mime"],
                "srcm" => $mobile,
                "src" => $user_id,
                "filename" => $fileNameEncode,
                "size" => $params2["size"],
                "duration" => isset($_POST["duration"])?$_POST["duration"]:0,
                "time" => time());

            $mmsType = explode("/", $params["mime"]);
            $mmsType = strtoupper($mmsType[1]);
            $logger->info("MMS-TYPE:$mmsType,MMS-SIZE:" . $_FILES['file']['size'] . ",MMS-SENDER:" . $mobile . ",MMS-ID:" . $params["id"]);

        }
        $notifyMember = str_replace($user_id,"",str_replace($user_id.",", "", $params["uid"]));   //过滤发送者自己
        //给接收者发送notify
        $accepters = M('User')->getUserInfoByUid($notifyMember);
        // $databaseManager->destoryConn();  //unlink Master DB
        if(!$accepters){
            $logger->error(sprintf("accepter not exists ,uid %s",$notifyMember));
            ErrCode::echoErr(ErrCode::API_ERR_MESSAGE_SEND_ERROR,1);
        }
        $mecManager = new MecManager($user_id,$msgObj,$accepters);
        $mecManager->sendMessage();
        ErrCode::echoJson(1,"发送成功");
    }

    /**
     * 接收文本信息
     */
    public  function  getText(){
        include(ROOT_PATH . "/common/TcpConnection2.php");
        $logger = Logger::getLogger(basename(__FILE__));
        $sessionInfo=$this->user;
        $uer_id= $sessionInfo["user_id"];
        $mec_ip = $sessionInfo["mec_ip"];
        $mec_port = $sessionInfo["mec_port"];
        $mid = $sessionInfo["mid"];

        //更新当前账号最后取得消息的时间
        M('User')->updateLastGetDate($uer_id);

        $tcpConnection = new TcpConnection2($mec_ip,$mec_port);
        if(!$tcpConnection->isConnected()){
            $logger->error(sprintf("Tcp connect fail.",$uer_id));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR);
        }

        //GET MY_MID APPNAME
        $message = "FETCH " . $mid . " \n";
        $result = $tcpConnection->tcpSend($message);
        $resultArr = "";
        $resultFlag = true;
        if($result){
            $resArr = explode("\n", $result);
            $resCode = explode(" ",$resArr[0]);
            if($resCode[0] == "0" || $resCode[0] == "11002"){
                for($i = 1 ; $i < count($resArr) ;$i++){
                    $res = explode("=",$resArr[$i]);
                    if(!isset($res[1])) continue;
                    $resultArr .= $res[0] . "=" . $res[1]."\n";
                }
            }else{
                $resultFlag = false;
            }

        }else{
            $resultFlag = false;
        }

        if(!$resultFlag){
        	ErrCode::echoOkArr("MESSAGE_GET_OK",array());
        } else {
//         	if($uer_id==8){
//         		$logger->error(sprintf("eeeeeeeeeeeeeeeeeeee is %s",v($resultArr)));
//         	}
        	ErrCode::echoOkArr("MESSAGE_GET_OK",$resultArr);
        }
    }
    /**
     * 接收文件信息
     */
    public  function  getFile(){
        include(ROOT_PATH . "/common/MecManager.php");
        include(ROOT_PATH . "/common/MMS_FileManager.php");
        $logger = Logger::getLogger(basename(__FILE__));
        $params = array("ss","id","thumbnail");
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER);
        }
            $session = $params["ss"];
            $sessionInfo=$this->user;
            $fileId = $params["thumbnail"] == "1"?$params["id"] . "-thumb":$params["id"];
            //查找文件ID对应的fastDFS的信息
            $mmsFileManager = new MMS_FileManager();
            $getFileResult = $mmsFileManager->downLoadFile($fileId);
            if(!$getFileResult){
                $logger->error(sprintf("file mms download error.mms_id:".$params["id"]));
                ErrCode::echoErr(ErrCode::API_ERR_MMS_FILE_NO_EXSIT);
            }
            $logger->info(sprintf("GET_MMS,MOBILE:".$sessionInfo["member_name"].",MMS-ID is %s" . $params["id"]));
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: ".strlen($getFileResult));
            Header("Content-Disposition: attachment; filename=" . rand(123456789,999999999));
            echo $getFileResult;

    }

    /**
     * 确定消息
     */
    public  function  sure(){
        include(ROOT_PATH . "/common/MecManager.php");
        include(ROOT_PATH . "/common/MMS_FileManager.php");
        $config = new Config();
        $logger = Logger::getLogger(basename(__FILE__));
        $challengeStep = false;
        $params = array(array("ss",true),array("serial",true));
        $logger->debug(sprintf("Request params is %s",v($_POST)));
        //print_r($_POST);
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("ack params error. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);    //参数错误
        }


        $sessionInfo=$this->user;
        $uer_id= $sessionInfo["user_id"];
        $mec_ip = $sessionInfo["mec_ip"];
        $mec_port = $sessionInfo["mec_port"];
        $mid = $sessionInfo["mid"];

        $tcpConnection = new TcpConnection($mec_ip,$mec_port);
        if(!$tcpConnection->isConnected()){
            $logger->error(sprintf("Connect to MEC server fail.mec ip=%s,port=%s",$mec_ip,$mec_port));
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        //ACK MY_MID SERIAL
        $message = "ACK " . $mid . " " . $params["serial"] . "\n";
        $result = $tcpConnection->tcpSend($message);
        if(!$result){
            $logger->error(sprintf("Send message to MEC fail.message=%s",$message));
            ErrCode::echoErr(ErrCode::API_ERR_MESSAGE_ACK_ERROR,1);
        } else {
            $resArr = explode("\n", $result);
            $resCode = explode(" ",$resArr[0]);
            if($resCode[0] == "11003"){
                ErrCode::echoErr(ErrCode::API_ERR_ACK_HAVE_MESSAGE,1);
            }else{
                ErrCode::echoJson("1",'确定成功');
            }
        }
    }

    /**
     * 发群消息
     */
    public  function  sendGroup(){
        header("content-type:text/html; charset=utf-8");
        include(ROOT_PATH . "/common/MecManager.php");
        include(ROOT_PATH . "/common/MMS_FileManager.php");
        $logger = Logger::getLogger(basename(__FILE__));
        $mime = "";
        $config = new Config();
        $params = array(array("ss",true),array("gt",true),array("mime",true),array("time",true),array("id",true));
        $params = Filter::paramCheckAndRetRes($_POST, $params);
        if(!$params){
            $logger->error(sprintf("params is err. params is %s",v($_POST)));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }
        //获取参数
        $session = $params["ss"];
        $group_type = $params["gt"];
        $group_id = $params["group_id"];
        if(!($group_type == 1 || $group_type == 2)){
            $logger->error(sprintf("group type is error. group type is %s",$group_type));
            ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
        }

//        $databaseManager = new DatabaseManager();
//        $database = $databaseManager->getConn();
//        //数据库链接失败
//        if(!$database){
//            $logger->error("database connect error.");
//            ErrCode::echoErr(ErrCode::SYSTEM_ERR);
//        }
//        //session校验
//        $sessionInfo = $databaseManager->checkSession($session);
//        $databaseManager->destoryConn();   //unlink master DB
//        if(!$sessionInfo){
//            $logger->error(sprintf("Session check is fail. Error session is [%s]",$session));
//            ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION);
//        }
        $sessionInfo=$this->user;
        $user_id = $sessionInfo["user_id"];
        $mobile = $sessionInfo["member_name"];

        $databaseManager=M('Msg');
        //获取group对应的群人员信息
        $attendeeArray = $databaseManager->getMembers($sessionInfo);
        if(count($attendeeArray)==0){
            $logger->error(sprintf("The group([%s]) does not exist.",$group_id));
            ErrCode::echoErr(ErrCode::GROUP_NUMBER_IS_NOT_EXISTS,1);
        }

        //如果发送者不是健康圈
        if(!$databaseManager->checkGroupMember($group_type,$group_id,$user_id)){
            $logger->error(sprintf("The sender isn't in group([%s]).",$group_id));
            ErrCode::echoErr(ErrCode::IS_NOT_GROUP_MEMBER,1);
        }

        if($sessionInfo['user_type_id']==1){
            $groupid=$sessionInfo['member_id'];
        }else{
            $groupid=$sessionInfo['parent_id'];
        }

        if($params["mime"] == "text/plain"){
            $mime = "text";
            if(!isset($_POST["text"])){
                $logger->error("text is null.");
                ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
            }
            $textContent = trim($_POST["text"]);



            $msgObj = array(
                "id" => $params["id"],
                "type" => "MMG",
                "mime" => $params["mime"],
                "gt" => $group_type,
                //"groupid"=>$groupid,
                "srcm" => $mobile,
                "src" => $user_id,
                "text" => rawurlencode($textContent),
                "time" => time());

            $logger->info("MMS-TYPE:TEXT,MMS-LENGTH:" . strlen(trim($_POST["text"])) . ",MMS-SENDER:" . $mobile . ",MMS-CONTENT:" . rawurlencode($_POST["text"]) . ",MMS-ID:" . $params["id"]);
        }else{
            $params2 = Filter::paramCheckAndRetRes($_POST, array(
                "filename",
                "id",
                "size"
            ));
            if(!$params2){
                $logger->error(sprintf("file mms params error. params=%s",v($_POST)));
                ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
            }
            if(strpos($params["mime"],"video") !== false || strpos($params["mime"],"audio") !== false ){
                if(!isset($_POST["duration"]) || trim($_POST["duration"]) == ""){
                    $logger->error(sprintf("file mms duration error."));
                    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
                }
                $params2["duration"] = $_POST["duration"];
            }
            if(strpos($params["mime"],"audio") !== false){
                $mime = "audio";
            }elseif(strpos($params["mime"],"video") !== false){
                $mime = "video";
            }elseif(strpos($params["mime"],"image") !== false){
                $mime = "image";
            }else{
                $mime = "file";
            }
            //处理文件上传
            if(!isset($_FILES['file']) || !isset($_FILES['file']['error']) || $_FILES['file']['error'] != 0){
                $logger->error(sprintf("no file params error."));
                ErrCode::echoErr(ErrCode::API_ERR_MESSAGE_SEND_ERROR,1);
            }
            if($_FILES['file']['size'] != $params2["size"]){
                $logger->error(sprintf("file size is not equal to size param! file size:".$_FILES['file']['size']." size param:".$params2["size"]));
                ErrCode::echoErr(ErrCode::API_ERR_MESSAGE_SEND_ERROR,1);
            }

            $max_file_size = $config->getConfig("max_file_size");
            if($_FILES['file']['size'] > $max_file_size){
                $logger->error(sprintf("Mms file size exceeds the limit.max_file_size:".$max_file_size));
                ErrCode::echoErr(ErrCode::API_ERR_FILE_TOO_LARGE,1);
            }
            /*****************************得到上传文件的信息********************************************/
            $fileTempName = $_FILES['file']['tmp_name'];//得到临时文件
            $mmsFileManager = new MMS_FileManager();
            $saveFileResult = $mmsFileManager->uploadFile($fileTempName, $params2["id"],array_merge($params,$params2));
            if(!$saveFileResult){
                $logger->error(sprintf("file mms save error."));
                ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
            }
            $fileNameEncode = rawurlencode($params2["filename"]);
            //发送消息通知
            $msgObj = array("id" => $params["id"],
                "type" => "MMG",
                "mime" => $params["mime"],
                "gt" => $group_type,
               // "groupid"=>$groupid,
                "srcm" => $mobile,
                "src" => $user_id,
                "filename" => $fileNameEncode,
                "size" => $params2["size"],
                "duration" => isset($_POST["duration"])?$_POST["duration"]:0,
                "time" => time());

            $mmsType = explode("/", $params["mime"]);
            $mmsType = strtoupper($mmsType[1]);
            $logger->info("MMS-TYPE:$mmsType,MMS-SIZE:" . $_FILES['file']['size'] . ",MMS-SENDER:" . $mobile . ",MMS-ID:" . $params["id"]);
        }

        //给接收者发送notify
        foreach ($attendeeArray as $key=>$value)
        {
            if ($value === $user_id)
                unset($attendeeArray[$key]);
        }
        $notifyMembers = implode(",", $attendeeArray);
        $database = $databaseManager->conn;
        //数据库链接失败
        if(!$database){
            $logger->error("database connect error.");
            ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
        }
        $accepters = M('User')->getUserInfoByUid($notifyMembers);


        if(!$accepters){
            $logger->error(sprintf("accepter not exists ,group members is  %s",$notifyMembers));
            ErrCode::echoErr(ErrCode::API_ERR_MESSAGE_SEND_ERROR,1);
        }


        $mecManager = new MecManager($user_id,$msgObj,$accepters);
        $mecManager->sendMessage();
        ErrCode::echoJson("1",'发送成功');
    }

    /**
     * 获取健康圈的人信息
     */
    public  function  getFriends(){
        $mode=M('Msg');
        $user=$this->user;
        $arr=$mode->checkUser($user);
        ErrCode::echoJson('1','获取成功',$arr);
    }

    /**
     * 获取员工信息
     */
   public  function  getWorkerInfo(){
       $user=$this->user;
       $uid=$user['parent_id'];
       check_role($user,1);
       $sql="select a.member_id,a.member_name as mobile,a.member_truename, a.member_birthday,a.member_sex,
               b.thumbnail_image_url,b.image_ver as pav ,b.base_ver as piv ,c.shop_name from mall_member as a,mall_user_version_info as b,mall_shop as c where
                b.user_id=a.member_id and c.shop_id=a.store_id and a.member_id=$uid
                ";
       $mode=M('User')->conn;
       $result=$mode->getRow($sql);
       $result['people_num']=$mode->getOne("select count(*) from mall_member where parent_id=$uid and user_type_id=1")+1;
       ErrCode::echoJson(1,'成功',$result);
   }


}