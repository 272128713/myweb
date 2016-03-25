<?php
/*
 * 空中急救模块API
 * 1.3.1. get_user_question.php (获取用户提问问题列表)
 */
include(dirname(__FILE__) . "/common/inc.php");
error_reporting(0);
$config = new Config();
$logger = Logger::getLogger(basename(__FILE__));
$params = array(array("ss",true),array("flag",true),array("page",false));

//print_r($_POST);
$params = Filter::paramCheckAndRetRes($_POST, $params);
if(!$params){
	$logger->error(sprintf("params error. params is %s",v($_POST)));
	ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

//获取参数
$ss = $params["ss"];     //session
$flag = $params["flag"];	//信息筛选标志 0 未解决 1 已解决 2所有未解决，排除自己 3所有自己解决的（未解决） 4所有已经解决的（必填）
if(!isset($params["page"])||$params["page"]==""){
	$page = 1;
}else {
	$page = $params["page"];
}

$databaseManager = new DatabaseManager();
$dbMaster = $databaseManager->getConn(); //连接sky_first_aid

//数据库链接失败

if(!$dbMaster){
	$logger->error(sprintf("Database sky_first_aid connect fail."));
	ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}

if(!$dbSso = $databaseManager->getSsoConn()){
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
$username = $sessionArr['rn'];
$sql_img_version = "select image_ver from user_version_info where user_id = '$userId'";
$imgVersion = $dbSso->getOne($sql_img_version);	//获取用户头像信息版本号
$dbSso->disConnect();//关闭dbSso
//获取用户提问sql


$page_num = $config->getConfig(PAGE_NUM);
$page_start = ($page-1)*$page_num;
if($flag==0||$flag==1){
	//自己未解决，已解决
	$sql_list = "SELECT id,user_id,content,createDate,isSolve,isEnd
			FROM user_question_info  
			WHERE isEnd = $flag and user_id = '$userId'
			ORDER BY answer_time DESC
			LIMIT $page_start,$page_num
			";
}elseif ($flag==2){
	//所有未解决的问题，排除自己未解决的
	$sql_list="SELECT id,user_id,content,createDate,isSolve,isRead,isEnd
			FROM user_question_info 
			where isEnd=0 and user_id != '$userId'
			and id not in (select question_id from doctor_answer_info where doctor_id = '$userId' GROUP BY question_id)
			ORDER BY createDate DESC
			LIMIT $page_start,$page_num
	
	";
}elseif ($flag==3){
	//所有自己回答过的问题(未解决)
	$sql_list="select u.id,user_id,u.content,u.createDate,isSolve,isEnd
				from user_question_info as u 
				left join doctor_answer_info as d on u.id = d.question_id 
				where d.doctor_id = '$userId' and u.isEnd = 0 group by u.id
				ORDER BY u.answer_time DESC
				LIMIT $page_start,$page_num
	
	";
}elseif ($flag==4){
	//所有已经解决的不包括自己已经解决的
	$sql_list="select id,user_id,content,createDate,isSolve,isEnd
			   from user_question_info 
			   where isEnd=1 and user_id != '$userId'
				ORDER BY answer_time DESC
				LIMIT $page_start,$page_num
	
	";
}elseif ($flag==5){
	//自己的咨询记录(未解决，已解决)
	$sql_list = "SELECT id,user_id,content,createDate,isSolve,isRead,isEnd
				FROM user_question_info  
				WHERE user_id = '$userId'
				ORDER BY answer_time DESC
				LIMIT $page_start,$page_num
				";
}elseif ($flag==6){
	//自己的追问被回复记录(新消息,已读的不显示)(废弃)
	
// 	$sql_list = "SELECT u.id,u.user_id,u.content,u.createDate,u.isSolve,fa.isRead,u.isEnd
// 				FROM user_question_info as u
// 				left join doctor_answer_info as an
// 				on an.question_id = u.id
// 				left join user_question_for_answer_info as fa
// 				on fa.answer_id = an.id
// 				WHERE an.doctor_id = '$userId' and fa.isRead = 0
// 				GROUP BY u.id
// 				ORDER BY fa.createDate DESC
// 				LIMIT $page_start,$page_num
// 				";

	//已参与的
	$sql_list = "SELECT * ,IFNULL(fadate,andate) as maxdate from (
				SELECT u.id,u.user_id,u.content,u.createDate,u.isSolve,IFNULL(min(fa.isRead),1) as isRead,u.isEnd,max(fa.createDate) as fadate ,max(an.createDate) as andate
				FROM user_question_info as u
				left join doctor_answer_info as an on an.question_id = u.id
				left join user_question_for_answer_info as fa on fa.answer_id = an.id
				WHERE an.doctor_id = '$userId'
				GROUP BY u.id
				DESC LIMIT $page_start,$page_num ) as a
				ORDER BY maxdate desc
				";
}elseif ($flag==7){
    //所有咨询
    $sql_list = "SELECT u.id,u.user_id,u.content,u.isSolve,u.isRead,u.isEnd,u.createDate,IFNULL(max(an.createDate),0) as anDate,
                IFNULL((select max(faq.createDate) as uqid
                from user_question_info AS uq
                LEFT JOIN doctor_answer_info as anq on anq.question_id = uq.id and anq.doctor_id = '$userId'
                left join user_question_for_answer_info as faq on faq.answer_id = anq.id
                where uq.id = u.id),0) as faDate,
                GREATEST(u.createDate,IFNULL(max(an.createDate),0),IFNULL((select max(faq.createDate) as uqid
                from user_question_info AS uq
                LEFT JOIN doctor_answer_info as anq on anq.question_id = uq.id and anq.doctor_id = '$userId'
                left join user_question_for_answer_info as faq on faq.answer_id = anq.id
                where uq.id = u.id),0)) as greate
                FROM user_question_info AS u
                left join doctor_answer_info as an on an.question_id = u.id and u.user_id = '$userId'
                left join user_question_for_answer_info as fa on fa.answer_id = an.id
                GROUP BY u.id
                ORDER BY greate DESC
				LIMIT $page_start,$page_num
				";

}
$result = $dbMaster->getAll($sql_list);
$result_arr = array();
$filearr = array();
foreach ($result as $k=>$v){

	$imgsign = 0;
	$audiosign = 0;
    $isMe = 0;
	$vid = $v['id'];
	$sql_file = "select fileid,filetype,createDate from user_question_info_files where uqid = '$vid'";
	$filearr = $dbMaster->getAll($sql_file);
	$imgarr = array();
	$audioarr = array();
	$i= 0;
	foreach ($filearr as $kf=>$vf){
		if($vf["filetype"] == 'image'){
			//输出img id type数组
			$imgsign = 1;
			$imgarr[$kf]['fileid']=$vf['fileid']; 
		}elseif($vf["filetype"] == 'audio'){
			$audiosign = 1;
			$audioarr[$i]['fileid']=$vf['fileid']; 
			$i++;
		}
	}
//	if($v['content']==""&&$audiosign==1){
//		$v['content']="该用户发来一段语音咨询";
//	}
    if($v['user_id']==$userId){
        //是自己的问题(我的问题)
        $isMe = 1;
    }
	if($flag==2){
		$v['isRead']=1;
	}elseif($flag==7){
        if($isMe!=1){
            //不是自己的问题，已读状态置为已读
            $v['isRead']=1;
        }
        //回答和追问时间标记位
        if($v['faDate']!=0){
            $timeAdd = "追问";
        }elseif($v['anDate']!=0){
            $timeAdd = "回复";
        }else{
            $timeAdd = "提问";
        }
        //处理时间
        $v['createDate'] = time_tranx($v['greate']).$timeAdd;
    }

	$result_arr[$k] = array(
			"uid" => $v['user_id'],
			"upid" => $v['id'],
// 			"username" => $username,
// 			"imgversion" => $imgVersion,
			"content" => $v['content'],
			"createDate" => $v['createDate'],
			"nums" => $v['isSolve'],
			"isRead" => $v['isRead'],
			"isEnd" => $v['isEnd'],
			"imgarr" => $imgarr,
			"audioarr" => $audioarr,
			"imgsign" => $imgsign,
			"audiosign" => $audiosign,
            "isMe" => $isMe,
	);
}


	//关闭数据库，输出数据
	$dbMaster->disConnect();
	ErrCode::echoOkArr('1',"执行成功",$result_arr);








//格式化时间
function time_tranx($the_time){
    $now_time = date("Y-m-d H:i:s",time());
    $now_time = strtotime($now_time);
    $show_time = strtotime($the_time);
    $dur = $now_time - $show_time;
    if($dur < 0){
        return $the_time;
    }else{
        if($dur < 60){
            return $dur.'秒前';
        }else{
            if($dur < 3600){
                return floor($dur/60).'分钟前';
            }else{
                if($dur < 86400){
                    return floor($dur/3600).'小时前';
                }else{
                    if($dur < 259200){ //3天内
                        return floor($dur/86400).'天前';
                    }else{
                        return $the_time;
                    }
                }
            }
        }
    }
}
?>































