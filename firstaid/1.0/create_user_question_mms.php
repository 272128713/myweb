<?php
/*
 * 空中急救模块API
 * 1.3.2. create_user_question_mms.php (发布咨询音频图片问题)
 */
	header("content-type:text/html; charset=utf-8");
	include(dirname(__FILE__) . "/common/inc.php");
	include (dirname(__FILE__) . "/common/MMSFileManager.php");
	
	$logger = Logger::getLogger(basename(__FILE__));
	$params = array(array("ss",true),array("cc",false),array("file",false),array("audiuname",false));
	//print_r($_POST);
	$params = Filter::paramCheckAndRetRes($_POST, $params);

	
	if(!$params){
		$logger->error(sprintf("params error. params is %s",v($_POST)));
		ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
	}

	$databaseManager = new DatabaseManager();
	$database = $databaseManager->getConn();
	//DB连接失败
	if(!$database){
		$logger->error(sprintf("Database connect fail."));
		ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
	}
	$session = $params["ss"];
	$sessionCheck = $databaseManager->checkSession($session);
	if(!$sessionCheck){
		$logger->error(sprintf("Session check is fail. Error session is [%s]",$session));
		$databaseManager->destoryConn();
	    ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
	}
	
	
    if(isset($params["cc"])){
    	$cc = $params["cc"];
    }else{
    	$cc = "";
    }
	if(isset($params["audiuname"])){
    	$audiuname = $params["audiuname"];	//get audiuname
	}else{
		$audiuname = "";
	}
	$user_id = $sessionCheck["user_id"]; //get user_id by session

	//存储进问题表
	$sql_list = "insert  into user_question_info
	(user_id, content, createDate, isEnd, isSolve,answer_time,isRead)
	VALUES
	('$user_id','$cc',NOW(),'0','0',NOW(),1)
	";
//	$logger->error(v($sql_list));
	$result = $database->execute($sql_list);
	$sql_select = "select last_insert_id() from user_question_info where user_id = '$user_id'";
	$result_id = $database->getOne($sql_select);

	$databaseManager->destoryConn();  //主动断开DB链接
	//get ziped file,unzip file ,and get image files 
	$file_params = array();//(imagefilename,)
	$file_params['user_id'] = $sessionCheck["user_id"];
	$file_params['cc'] = $cc;
		if(isset($_FILES['file'])){
	        //print_r($_FILES['file']);

			$logger->error(v($_FILES['file']));
	        //echo $_FILES['file']['error'];
	        if(isset($_FILES['file']['error'])){
	            if($_FILES['file']['error'] == 0){
	                /*****************************得到上传文件的信?********************************************/
	                $fileName = $_FILES['file']['name'];//取得上传文件
	                $fileTempName = $_FILES['file']['tmp_name'];//得到临时文件
	                $fileSize = $_FILES['file']['size'];//已上传文件的大小，单位为字节
	               // print_r($_FILES['file']);
	                $dirName = date("Ymd");
	                $User_id_Dir = $user_id . getRandomID(3,3);
	                $tFileName = "temp/" . $User_id_Dir; //这个是压缩包存放的目录
	                //将http获得到的临时文件移动到指定目录
	                if(!move_uploaded_file($fileTempName, $tFileName)){
	                    //$log->write($fileTempName . "   " . $fileDestName);
	                    $logger->error(sprintf("New Content move file error.fileTempName=%s , tFileName=%s",$fileTempName,$tFileName));
	                    ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
	                } else {
	                    $zip = new ZipArchive;
	                    $res = $zip->open($tFileName);
	                    $trueFileName = "temp/realfile/" . $User_id_Dir;//这个是压缩包解压后，解压的文件所存放目录
	                    $file_params['file_path'] = $trueFileName;//解压文件所在目录
	                    $img_name_arr = array();
	                    if($res == "TRUE"){
	                    	//获得zip解压后的文件名称，多个
	                    	for($i = 0; $i < $zip->numFiles; $i++) {
								$name_index = $zip->getNameIndex($i);
								if($name_index!=$audiuname){
									$img_name_arr[$i] = $name_index;
								}else{
									$audiu_name_info = $name_index;
								}
	                        }
							//解压文件
	                        $extractResult = $zip->extractTo($trueFileName);
	                        if(!$extractResult){
	                        	$logger->error(sprintf("Unzip contact file error. filename=%s",$trueFileName));
	                        	ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
	                        }
	                        $file_params['file_name'] = $img_name_arr;//解压文件名 数组集合,图片
	                        $file_params['audiu_name'] = $audiu_name_info; //解压audiu文件
	//                         print_r($file_params['file_name']);
	//                         die();
	                    } else {
	                    	$logger->error(sprintf("Open contact zip file error. filename=%s",$tFileName));
	                    	ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);
	                    }
	                    $zip->close();
	                    //$params["file"] = file_get_contents($trueFileName . "/test.txt");
	                    @unlink($tFileName);//删除zip文件
	                    @unlink($trueFileName  . "/*");//删除文件
	                    @rmdir($trueFileName);//删除文件夹
	                }
	
	                $fileManager = new MMSFileManager();
	                //$cc=addslashes($cc);
	                $ret = $fileManager->AddDidiContent($user_id, $cc, $file_params, $result_id, 'image'); //传图片
	                $retaudio = $fileManager->AddDidiContent($user_id, $cc, $file_params, $result_id, 'audio'); //传声音
	                if(!$ret&&!$retaudio){
	                	$logger->error(sprintf("传输失败"));

                        $ressql = deleteDidi($result_id);
                        $logger->error(v($ressql));
	                	ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);

	                }
	                
	                
	            }else{
                    $logger->error(sprintf("传输失败"));
                    $ressql = deleteDidi($result_id);
                    $logger->error(v($ressql));
                    ErrCode::echoErr(ErrCode::FILE_SEND_ERR,1);

                }
	        }
	
	    }
        function deleteDidi($newId){

            $databaseManager = new DatabaseManager();
            $database = $databaseManager->getConn();
            $sql = "delete from user_question_info WHERE id = '$newId'";
            $database->execute($sql);
            return $sql;
        }
   	ErrCode::echoOk("SUCCESS",1);



?>