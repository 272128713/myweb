<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 15-10-26
 * Time: 下午4:44
 */

include_once dirname(__FILE__) . "/common/inc.php";
include_once dirname(__FILE__) . "/common/database/DatabaseManager.php";
//include_once dirname(__FILE__) . "/common/TcpConnection.php";
include_once dirname(__FILE__) . "/common/RKMongo.php";
include_once dirname(__FILE__) . "/service/user_damoclean_serv.php";
include_once dirname(__FILE__) . "/service/tools.php";
include_once dirname(__FILE__) . "/common/MecManager.php";

//获取参数
$params = array(
    array("ss",true),array("longitude",true),array("latitude",true),array("address",false),array("state",false)
);

$params = Filter::paramCheckAndRetRes($_POST, $params);

if(!$params){
    logger()->error(sprintf("params error. params is %s",v($_POST)));
    ErrCode::echoErr(ErrCode::API_ERR_MISSED_PARAMATER,1);
}

$config = new Config();
$databaseManager = new DatabaseManager();
$rkMongo = new RKMongo();
$rkMongo->connect();


//数据库链接
if (!$db = $databaseManager->getConn()) {
    logger()->error(sprintf("Database connect fail."));
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);
}
if(!$rkMongo->connect()){
    logger()->error("mongoDB connect error.");
    ErrCode::echoErr(ErrCode::SYSTEM_ERR,1);

}



//session处理
$sessionArr = $databaseManager->checkSession(trim($params["ss"]));


if(!$sessionArr){
    $databaseManager->destoryConn();
    logger()->error(sprintf("Session check is fail. Error session is [%s]",trim($params["ss"])));
    Errcode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
}

$userId = (int)$sessionArr['user_id'];
$address = empty($params['address'])?"地址未获取":trim($params['address']);
$state=empty($params['state'])?true:false; //如果为true 则上传失联人位置信息

//相关业务处理
$gps = array((float)$params['longitude'],(float)$params['latitude']);
$longitude=$params['longitude'];
$latitude=$params['latitude'];

/** 检查我监护的人的状态，返回失联状态 */
$slres=CheckSLStateByFlag($userId);


/**
 * 临时处理 IOS多次请求抛弃 By王秀泽 2016-1-6 15:22:18
 */
#region 抛弃1分钟内的重复请求
$res=$rkMongo->getLinkmanLbsNow($userId,null,1,1);
$res=array_values($res);

if(time()-$res[0]['dt']<60) ErrCode::echoJson('1','success',$slres);
#endregion


#region 判断自己所监控的人里是否有失联好友 发送消息通知
/** Last:2016-01-14 09:59:50 暂未定义失联消息类型 */
//if ($friends=GetBeMonFriend($userId)) {
//    foreach($friends as $friend){
//        //获取到好友的最新GPS信息
//        if($gpsInfo=$rkMongo->getGPSAddress(1,$friend['id'])){
//            //如果超过30分钟没有上传位置 直接判定为失联
//            if(time()-$gpsInfo['dt']->sec>1800){
//                //发送通知推送
//                //sendWarnInfo($friend['id'],0,'','');
//            }else{
//                //获取到好友的历史gps信息 3条
//                if ($gps=$rkMongo->getLinkmanLbsNow($friend['id'],null,1,3)) {
//                    $gps=array_values($gps);
//
//                    if(count($gps)==3){
//                        //如果最近的三条位置信息 123  1-2小于5米  2-3小于5米  则判定为失联
//                        if(getDistance($gps[0]['gps'][0],$gps[0]['gps'][1],$gps[1]['gps'][0],$gps[1]['gps'][1])<5 && getDistance($gps[1]['gps'][0],$gps[1]['gps'][1],$gps[2]['gps'][0],$gps[2]['gps'][1])<5){
//                            /** 长时间未移动推送消息 */
//                            //sendWarnInfo($friend['id'],0,'','');
//                        }
//                    }
//                }
//            }
//        }
//    }
//}
#endregion


#region 上传失联人位置信息
if($state){
    if ($rkMongo->setLinkmanLbs($userId,time(),array($longitude,$latitude))) {
        /** 如果用户存在主动监护人并在监控中 返回id */
        if ($linkid=getUserLinkManA($userId)) {
            /** 如果用户存在电子围栏 返回围栏数据 */
            if ($res=getUserActivity_range($linkid)) {
                //分隔围栏数据   1212,1121-3212,13551-3212,324
                $tempsj=$res;
                $res=explode('-',$res);
                //遍历围栏数据 分配数组
                for($i=0;$i<count($res);$i++){
                    //分隔围栏数据
                    $res[$i]=explode(',',$res[$i]);

                    $res[$i]['latitude']=$res[$i][0];
                    $res[$i]['longitude']=$res[$i][1];
                }
                /** 如果用户不在围栏内 */
                if(!is_point_in_polygon(array('longitude'=>$longitude,'latitude'=>$latitude),$res)){
                    /** 发送推送消息 走出电子围栏 */
                    sendWarnInfo($userId,1,$longitude,$latitude);
                }
            }
        }
    }
}
#endregion

//上传个人位置
$result = $rkMongo->setLbsForRescueScene(1,$userId,$gps,$address);



if(!$result){
    Errcode::echoErr(ErrCode::SYSTEM_ERR,1);
}

ErrCode::echoJson('1','success',$slres);

function logger()
{
    return Logger::getLogger(basename(__FILE__));
}