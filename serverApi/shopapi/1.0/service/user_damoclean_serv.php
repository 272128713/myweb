<?php
/**
 * Created by PhpStorm.
 * User: cnx7
 * Date: 2015/10/28
 * Time: 17:17
 */

/**
 * @param $uid 需要查询的用户id
 * @return mixed|null 电话号码
 * @throws Exception
 */
function getTel($uid)
{
    $dbObj = new DatabaseManager();
    $dbSso = $dbObj->getSsoConn();
    $sql = <<<T_ECHO
SELECT mobile FROM sky_user_data_master.user_base_info WHERE user_id= "{$uid}";
T_ECHO;
    if ($tel = $dbSso->getOne($sql)) return $tel;
    return null;
}

/**
 * 查询SSO主表是否存在某用户
 * @param $uid 用户ID
 * @return bool|mixed 存在返回true否则返回false
 */
function isExistsUser($uid)
{
    $dbObj = new DatabaseManager();
    $dbSso = $dbObj->getSsoConn();
    $sql = <<<T_ECHO
SELECT user_id FROM sky_user_data_master.user_base_info WHERE user_id= "{$uid}";
T_ECHO;
    return $dbSso->getOne($sql) ? true : false;
}

/**
 * 查询是否存在好友
 * @param $uid 用户ID
 * @return bool|mixed 存在返回true否则返回false
 */
function isExistsFriend($uid, $linkman)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
    $sql = <<<T_ECHO
SELECT friend_id FROM `sky_first_aid`.`user_friend_list` WHERE user_id= "{$uid}" and friend_id in ({$linkman}) and is_friend=1;
T_ECHO;

    return $db->getOne($sql) ? true : false;

}

/**
 * 获取用户是否拥有对某人的失联查看权限  有权限返回关系id
 * @param $loginuid 当前用户ID
 * @param $uid 被监护的用户id
 * @return bool|mixed 权限表ID或者返回false无效
 * @throws Exception
 */
function isAuthorized($loginuid, $uid)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $sql = <<<T_ECHO
SELECT s.id FROM `sky_first_aid`.`user_supervise_info` AS s JOIN `sky_first_aid`.user_privilege_list AS p ON s.prey_id=p.user_id WHERE s.prey_id="{$uid}" AND s.hunter_id="{$loginuid}" AND s.TYPE=1 AND s.state=1
T_ECHO;

    if (!$s_id = $db->getOne($sql)) {
        return false;
    } else {
        return $s_id;
    }
}

/**
 * 查询用户是否已存在主动监护人
 * @param $uid 被查询的用户ID
 * @return bool 是否已有主动监护人
 * @throws Exception
 */
function hasPosLinkMan($uid)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $sql = <<<T_ECHO
select hunter_id from `sky_first_aid`.`user_supervise_info` where prey_id="{$uid}" and type=1 and state=1;
T_ECHO;

    return $db->getOne($sql) ? true : false;
}

/**
 * 查询用户是否已发送过监控请求
 * @param $uid 当前用户id
 * @param $linkman 被查询的用户ID
 * @return bool 是否已有主动监护人
 * @throws Exception
 */
function hasSendRequest($uid, $linkman)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $sql = <<<T_ECHO
select hunter_id from `sky_first_aid`.`user_supervise_info` where prey_id="{$linkman}" and hunter_id="{$uid}" and type=1 and state=0;
T_ECHO;

    return $db->getOne($sql) ? true : false;
}

/**
 * 设置失联联系人时 过滤已存在的
 * @param $loginuser 被监护人
 * @param $linkman 失联联系人列表
 * @return mixed 过滤后的联系人列表
 */
function filterLinkMan($loginuser, $linkman)
{

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $linkman_sql = implode(',', $linkman);

    $sql = <<<T_ECHO
SELECT hunter_id,state FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id = '{$loginuser}' AND hunter_id IN ({$linkman_sql});
T_ECHO;

    if ($res = $db->getAll($sql)) {
        foreach ($res as $k => $v) {
            if (in_array($v['hunter_id'], $linkman)) {
                //删除主动监控邀请 Last:2016-1-7 18:34:44
                if($v['state']){
                    /** 如果已经存在主动监护关系 则从列表中删除 */
                    foreach ($linkman as $lk => $lv) {
                        if ($lv == $v['hunter_id']) unset($linkman[$lk]);
                    }
                }else{
                    /** 如果仅存在请求 则保留 并使请求失效 */
                    clearLinkManRequest($loginuser,$v['hunter_id']);
                }
            }
        }
    }

    return !empty($linkman) ? $linkman : ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_LAWLESS, 1);
}

/**
 * 是否被动监控某人
 * @param $loginuser
 * @param $linkman 被监控的人
 * @return bool
 * @throws Exception
 */
function isLink($loginuser, $linkman)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $sql = <<<T_ECHO
select hunter_id from `sky_first_aid`.`user_supervise_info` where prey_id="{$linkman}" and hunter_id="{$loginuser}" and type=0;
T_ECHO;

    return $db->getOne($sql) ? true : false;
}

/**
 * 用户设置或删除救命稻草(被动监护人)
 * @param $loginuser 当前登录用户ID（被监护人）
 * @param $linkman 救命稻草（监护人）
 * @param $flag 为真时删除操作
 * @return bool
 */
function setLinkMan($loginuid, $linkman, $type, $flag = false)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    if ($flag) {
        /** 用户删除救命稻草   */
        if (!empty($linkman[0])) {
            if ($type) {
                /** 删除围栏数据 */
                $sql = <<<T_ECHO
SELECT id FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id='{$linkman}' AND hunter_id='{$loginuid}';
T_ECHO;
                if ($s_id = $db->getOne($sql)) {
                    $sql = <<<T_ECHO
DELETE FROM `sky_first_aid`.`user_activity_range` WHERE usid={$s_id};
T_ECHO;
                    $db->execute($sql);
                }


                //删除自己主动监护的人 单条
                $sql = <<<T_ECHO
DELETE FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$linkman}" AND hunter_id="{$loginuid}";
T_ECHO;
            } else {
                //删除监护自己的人  单条
                $sql = <<<T_ECHO
DELETE FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$loginuid}" AND hunter_id="{$linkman}";
T_ECHO;
            }

        } else {
            /** 清空自己的被动监护人 */
            $sql = <<<T_ECHO
DELETE FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$loginuid}" AND type=0;
T_ECHO;
        }
    } else {
        /** 用户添加救命稻草  */
        $time = date('Y-m-d H:i:s', time());

        if ($type) {//主动监护 默认是未通过 (登陆用户主动设置其他用户)
            $sql = <<<T_ECHO
INSERT INTO `sky_first_aid`.`user_supervise_info`(prey_id,hunter_id,type,createDate,state,on_off,on_time) VALUES("{$linkman}","{$loginuid}",1,"{$time}",0,1,"{$time}");
T_ECHO;
        } else {//被动监护直接设置
            $str = "";
            foreach ($linkman as $l) {
                $str .= <<<T_ECHO
("{$loginuid}","{$l}",0,"{$time}","1",1,"{$time}"),
T_ECHO;
            }

            $sql = <<<T_ECHO
INSERT INTO `sky_first_aid`.`user_supervise_info`(prey_id,hunter_id,type,createDate,state,on_off,on_time) VALUES{$str}
T_ECHO;
            $sql = substr($sql, 0, -1);
        }
    }

    return $db->execute($sql) ? true : false;

}

/**
 * 获取已设置的失联联系人（被监护人获取正在监护的人列表）
 * @param $loginuid 被监护人id
 * @return array|bool 正在监护我的人列表|空
 * @throws Exception
 *
 * UPDATE：只获取被动监护的人员列表
 * TIME:2015-10-29 16:54:45
 */
function getLinkMan($loginuid)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
//    $sql=<<<T_ECHO
//    SELECT hunter_id FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$loginuid}" AND (TYPE=0 OR (TYPE=1 AND state=1 AND on_off=1));
//T_ECHO;
    $sql = <<<T_ECHO
SELECT s.hunter_id,v.`image_ver` as pav,v.`base_ver` as piv FROM `sky_first_aid`.`user_supervise_info` s LEFT JOIN `sky_user_data_master`.`user_version_info` v ON s.hunter_id=v.user_id WHERE s.prey_id="{$loginuid}" AND s.TYPE=0;
T_ECHO;
    if ($res = $db->getAll($sql)) {
        $rkMongo = new RKMongo();
        $rkMongo->connect();
        //GPS信息
        for ($i = 0; $i < count($res); $i++) {
            $temp = $rkMongo->getGPSAddress(1, $res[$i]['hunter_id']);
            $res[$i]['gps'] = empty($temp['gps']) ? array() : $temp['gps'];
            $res[$i]['address'] = empty($temp['address']) ? "" : $temp['address'];
//            $res[$i]['gps']=$rkMongo->getMyLbs(1,$res[$i]['user_id']);
        }
        return $res;
    } else {
        return null;
    }

}

/**
 * 获取失联人信息（用户所监控的所有人员信息）
 * @param $loginuid 用户id
 * @return array|null 所监控的人员详情列表
 * @throws Exception
 * Last:2015-12-22 09:51:10 加入参数type 1为查询主动监护  0为被动监护
 * Last:2016-1-6 11:06:53 增加gps的时间 addresstime
 * Last:2016-1-11 15:26:16 时间格式修改
 * Last:2016-01-12 15:17:26 增加字段 disappear_time 如果超过30分钟没有上传位置信息 则返回时间差
 */
function getDamoclean($loginuid,$type)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $sql = <<<T_ECHO
SELECT u.user_id,u.user_name AS name,u.mobile,v.thumbnail_image_url AS img,v.image_ver AS pav,v.base_ver AS piv,p.linkman_flag,s.`type`,s.on_off,s.on_time
FROM `sky_first_aid`.`user_supervise_info` AS s
LEFT JOIN `sky_user_data_master`.`user_base_info` AS u ON s.prey_id=u.user_id
LEFT JOIN `sky_user_data_master`.`user_version_info` AS v ON s.prey_id=v.user_id
LEFT JOIN `sky_first_aid`.`user_privilege_list` AS p ON s.prey_id=p.user_id
WHERE s.hunter_id={$loginuid} and TYPE={$type} AND state=1
T_ECHO;

    if ($res = $db->getAll($sql)) {
        $rkMongo = new RKMongo();
        $rkMongo->connect();
        //GPS信息
        for ($i = 0; $i < count($res); $i++) {
            $temp = $rkMongo->getGPSAddress(1, $res[$i]['user_id']);
            $res[$i]['gps'] = empty($temp['gps']) ? array() : $temp['gps'];
            $res[$i]['address'] = empty($temp['address']) ? "" : $temp['address'];
            $res[$i]['addresstime'] = empty($temp['dt']) ? "" : date('m-d H:i',$temp['dt']->sec);

            if(!$temp['dt']){ //取不到时间 返回失联
                $res[$i]['disappear_time']='';
                continue;
            }

            $stime=time()-$temp['dt']->sec;
            $time=intval($stime/3600/24)>0?intval($stime/3600/24)."天":(gmstrftime('%H', $stime)>0?intval(gmstrftime('%H', $stime))."小时":(intval(gmstrftime('%M',$stime))>30?intval(gmstrftime('%M', $stime))."分钟":"ok"));

            $res[$i]['disappear_time'] = $time;

        }

        return $res;
    } else {
        return null;
    }
}

/**
 * 监控人开启监控开关
 * @param $loginuid 监控人
 * @param $uid 被监控人
 * @param $flag 开关状态 1：开启 2：关闭
 * @return bool
 * @throws Exception
 */
function setOnOff($loginuid, $uid, $flag)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $sql = <<<T_ECHO
UPDATE `sky_first_aid`.`user_supervise_info` SET on_off="{$flag}" WHERE prey_id="{$uid}" AND hunter_id="{$loginuid}";
T_ECHO;

    return $db->execute($sql) ? true : false;

}

/** 获取监控我的请求列表 */
function getLinkManRequest($loginuid)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $sql = <<<T_ECHO
SELECT hunter_id FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$loginuid}" AND TYPE=1 AND state=1;
T_ECHO;

    /** 如果已经存在监护人 则抛出错误 */
    if ($db->getOne($sql)) ErrCode::echoErr(ErrCode::API_ERR_LINKMAN_EXISTS, 1);

    $sql = <<<T_ECHO
SELECT hunter_id,createDate FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$loginuid}" AND TYPE=1 AND state=0;
T_ECHO;

    if ($res = $db->getAll($sql)) {
        return $res;
    } else {
        return null;
    }
}

/**
 * 同意或者拒绝监控请求
 * @param $loginuid 被监护人ID
 * @param $uid 监护人ID
 * @param $flag 状态 1为同意 0关闭（无操作
 * @return bool
 */
function repLinkManRequest($loginuid, $uid, $flag)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $time = date('Y-m-d H:i:s', time());


    if ($flag==1) {
        /** @var $sql 同意监控请求 */
        $sql = <<<T_ECHO
UPDATE `sky_first_aid`.`user_supervise_info` SET state={$flag},on_time="{$time}" WHERE prey_id="{$loginuid}" AND hunter_id="{$uid}";
T_ECHO;

    } else {

        /** @var $sql 删除监控请求 */
        $sql = <<<T_ECHO
DELETE FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$loginuid}" AND hunter_id="{$uid}";
T_ECHO;
    }

    return $db->execute($sql) ? true : false;
}

/**
 * 删除所有未通过的请求（在同意主动监控请求后调用）
 * @param $uid 要清理的用户id
 * @param $hunter_id 监护人ID
 * @return bool
 * @throws Exception
 * Last: 2016-1-7 18:35:53 允许多人主动监控同一人 添加字段 $hunter_id
 */
function clearLinkManRequest($uid,$hunter_id)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $sql = <<<T_ECHO
DELETE FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$uid}" and hunter_id="$hunter_id" AND type=1 AND state=0;
T_ECHO;
    return $db->execute($sql) ? true : false;
}

/** 获取好友列表 添加主动监控使用 */
//Last: 2015-12-22 11:37:45 允许多人主动监控
function getLinkmanFriendList($uid)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    /** 所有好友列表 */
    $sql = <<<T_ECHO
SELECT friend_id,image_ver pav,base_ver piv FROM `sky_user_data_master`.`user_base_info` b
LEFT JOIN `sky_first_aid`.`user_friend_list` f ON f.friend_id=b.user_id
LEFT JOIN `sky_user_data_master`.`user_version_info` v ON b.user_id=v.user_id
WHERE f.user_id={$uid} and f.is_friend=1;
T_ECHO;

    if ($friends = $db->getAll($sql)) { /** $friends 所有好友 */

        foreach ($friends as $friend) {
            $arr[] = $friend['friend_id'];
        }
        $arr_str = implode(',', $arr);

        /** 查出已经发送申请的好友 */
        $sql = <<<T_ECHO
SELECT * FROM `sky_first_aid`.`user_supervise_info` WHERE hunter_id="{$uid}" AND prey_id IN ({$arr_str}) and type=1 and state=0;
T_ECHO;

        $is_sent_friends = array(); /** 已经发送申请的好友 */

        /** 标记已经发送申请的好友 放到$is_sent_friends 其他好友在$friends中 */
        if ($allSentFriend = $db->getAll($sql)) {
            for ($i = 0; $i < count($friends); $i++) {
                foreach ($allSentFriend as $sFriend) {
                    if ($friends[$i]['friend_id'] == $sFriend['prey_id']) {
                        $friends[$i]['wait'] = '1';
                        array_unshift($is_sent_friends, $friends[$i]);
                        unset($friends[$i]);
                    }
                }
            }
        }
        $friends = array_values($friends);

        /** 查询所有正在被我监护的好友 */
        if ($BeMonFriends = GetBeMonFriend($uid)) {
            for ($i = 0; $i < count($friends); $i++) {
                foreach ($BeMonFriends as $bmFriend) {
                    if ($friends[$i]['friend_id'] == $bmFriend['id']) {
                        unset($friends[$i]); /** 过滤掉被我监护的好友 */
                    }
                }
            }
        }

        return array_merge_recursive($is_sent_friends, $friends); /** 合并好友返回 */
    }
    return array();
}

/** 获取好友列表 添加救命稻草使用 */
function getLinkmanFriendListJM($uid,$flag)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    /** 所有好友列表 */
    $sql = <<<T_ECHO
SELECT friend_id uid,image_ver pav,base_ver piv FROM `sky_user_data_master`.`user_base_info` b
LEFT JOIN `sky_first_aid`.`user_friend_list` f ON f.friend_id=b.user_id
LEFT JOIN `sky_user_data_master`.`user_version_info` v ON b.user_id=v.user_id
WHERE f.user_id={$uid} and f.is_friend=1;
T_ECHO;

    if ($res = $db->getAll($sql)) {
        if($flag==0){
            /** 查询出所有监控我的人 */
        $sql = <<<T_ECHO
SELECT hunter_id as friend_id FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$uid}"
T_ECHO;
        }else{
            /** 查询出所有我监控的人 */
        $sql = <<<T_ECHO
SELECT prey_id as friend_id FROM `sky_first_aid`.`user_supervise_info` WHERE hunter_id="{$uid}"
T_ECHO;
        }
        /** 把监控我的人从列表中过滤 */
        if ($res3 = $db->getAll($sql)) {
            for ($i = 0; $i < count($res); $i++) {
                foreach ($res3 as $c) {
                    if ($res[$i]['uid'] == $c['friend_id']) {
                        unset($res[$i]);
                    }
                }
            }
        }
        $res = array_values($res);

        $rkMongo = new RKMongo();
        $rkMongo->connect();
        for ($i = 0; $i < count($res); $i++) {
            $temp = $rkMongo->getGPSAddress(1, $res[$i]['uid']);
            $res[$i]['gps'] = empty($temp['gps']) ? array() : $temp['gps'];
            $res[$i]['address'] = empty($temp['address']) ? "" : $temp['address'];
        }

        return $res;
    }
    return array();
}


function getLinkmanFriendListJM_new($uid)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    /** 所有好友列表 */
    $sql = <<<T_ECHO
SELECT f.friend_id uid,v.image_ver pav,v.base_ver piv,0 as is_hunter
FROM `sky_user_data_master`.`user_base_info` b,`sky_first_aid`.`user_friend_list` f,`sky_user_data_master`.`user_version_info` v
WHERE f.friend_id=b.user_id AND b.user_id=v.user_id AND f.user_id={$uid} AND f.is_friend=1 AND f.friend_id NOT IN
(SELECT hunter_id as friend_id FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id={$uid} AND type=1 AND state=1);
T_ECHO;

    if ($res_all = $db->getAll($sql)) {
            /** 查询出所有监控我的人 */
            $sql = <<<T_ECHO
SELECT hunter_id as friend_id FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id="{$uid}"
T_ECHO;

        /** 把监控我的人从列表中过滤 */
        $_temp_is_hunter = array();
        if ($res3 = $db->getAll($sql)) {
            foreach ($res3 as $c) {
                for ($i = 0; $i < count($res_all); $i++) {
                    if ($res_all[$i]['uid'] == $c['friend_id']) {
                        $res_all[$i]["is_hunter"]='1';
                        $_temp_is_hunter[]=$res_all[$i];
                        array_splice($res_all,$i,1);
                    }
                }
            }
        }
        $res = array_merge($_temp_is_hunter,$res_all);
        $res = array_values($res);

        $rkMongo = new RKMongo();
        $rkMongo->connect();
        for ($i = 0; $i < count($res); $i++) {
            $temp = $rkMongo->getGPSAddress(1, $res[$i]['uid']);
            $res[$i]['gps'] = empty($temp['gps']) ? array() : $temp['gps'];
            $res[$i]['address'] = empty($temp['address']) ? "" : $temp['address'];
        }

        return $res;
    }
    return array();
}

/** 被监护人发送报警推送 */
function sendWarnInfo($uid, $flag = 0,$longitude,$latitude)
{

    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $msgObj = array(
        "type" => "FAH",
        "ot" => !empty($flag) ? 6 : 5,
        "src" => $uid,
        "srcm" => getTel($uid),
        "time" => time(),
        "longitude"=>$longitude,
        "latitude"=>$latitude,
        "pav"=> $dbObj->getUserPav($uid)
    );

    if ($flag) {
        /** 走出电子围栏 发送单条推送 */
        $sql = <<<T_ECHO
select hunter_id from `sky_first_aid`.`user_supervise_info` where prey_id="{$uid}" and type=1 and state=1 and on_off=1;
T_ECHO;
        $linkman = $db->getOne($sql);

    } else {
        /** 长时间未移动 发送多条推送 */
        /** 更新：2015-11-16 10:31:02 走出电子围栏也发送多条推送   --DEL 2015-12-4 17:05:19 */
        $sql = <<<T_ECHO
    select hunter_id from `sky_first_aid`.`user_supervise_info` where prey_id="{$uid}" and (type=0 or ( type=1 and state=1)) and on_off=1;
T_ECHO;
        if ($res = $db->getAll($sql)) {
            $linkman = array();
            foreach ($res as $r) {
                $linkman[] = $r['hunter_id'];
            }
        }
    }

    if (!empty($linkman)) {
        $mecManager = new MecManager($uid, $msgObj, $dbObj->getUserInfoByUid($linkman));
        $mecManager->sendMessage();
    }

}

/** 清空失效的围栏信息 */
/** 未使用 */
function delActivityRange()
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $sql = <<<T_ECHO
DELETE FROM `sky_first_aid`.`user_activity_range` WHERE usid NOT IN (SELECT id FROM `sky_first_aid`.`user_supervise_info`)
T_ECHO;

    $db->execute($sql);
}

/** 获取用户的电子围栏信息 */
function getUserActivity_range($s_id)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
    $sql = <<<T_ECHO
SELECT range_info FROM `sky_first_aid`.`user_activity_range` WHERE usid={$s_id};
T_ECHO;

    return $db->getOne($sql);

}

/** 获取用户的主动监护人的监护id(已开启监控的) */
function getUserLinkManA($uid)
{
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();

    $sql = <<<T_ECHO
select id from `sky_first_aid`.`user_supervise_info` where prey_id="{$uid}" and type=1 and state=1 and on_off=1;
T_ECHO;

    return $db->getOne($sql);
}

/**
 *求两个已知经纬度之间的距离,单位为米
 * @param lng1 ,lng2 经度
 * @param lat1 ,lat2 纬度
 * @return float 距离，单位米
 **/
function getDistance($lng1, $lat1, $lng2, $lat2)
{
    //将角度转为狐度
    $radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
    $radLat2 = deg2rad($lat2);
    $radLng1 = deg2rad($lng1);
    $radLng2 = deg2rad($lng2);
    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;

    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;

    return $s;

}

/** 获取好友的坐标信息 */
/** Last:2015-12-22 10:14:25 增加是否是我的监护人的标记 */
/** Last:2016-1-7 10:14:03 增加显示5公里内的好友数量 */
function getMyFreindLbs($uid){
    $dbObj = new DatabaseManager();
    $db = $dbObj->getConn();
    $rkMongo = new RKMongo();
    $rkMongo->connect();

    //所有好友列表
    $sql=<<<T_ECHO
SELECT friend_id as uid FROM `sky_first_aid`.`user_friend_list` WHERE user_id={$uid} and is_friend=1
T_ECHO;

    if ($res=$db->getAll($sql)) {
        //所有监控我的人
        $sql=<<<T_ECHO
SELECT hunter_id FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id={$uid} AND (TYPE=0 OR (TYPE=1 AND state=1))
T_ECHO;
        $jklist=$db->getAll($sql);

        /** @var $mylocation 我的坐标信息 */
        $mylocation=$rkMongo->getGPSAddress(1,$uid);
        $num=0;/** 五公里以内的好友数量 */

        for ($i = 0; $i < count($res); $i++) {
            $res[$i]['flag']=0;
            if(!empty($jklist)){
                //遍历所有监控我的人 标记到我的好友列表中
                foreach($jklist as $jk){
                    if($res[$i]['uid']==$jk['hunter_id']){
                        $res[$i]['flag']=1;
                    }
                }
            }

            //获取GPS信息
            $temp = $rkMongo->getGPSAddress(1, $res[$i]['uid']);
            $res[$i]['gps'] = empty($temp['gps']) ? array() : $temp['gps'];

            //是否在5000米以内 +1
            if (getDistance($mylocation['gps'][0],$mylocation['gps'][1],$temp['gps'][0],$temp['gps'][1])<5000) {
                $num++;
            }

        }

        /**
         * 统计监护我的人，我主动监护的人，我被动监护的人
         */
        $my_hunter = "SELECT COUNT(*) as a FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id=$uid AND type=0 AND state=1";
        $my_prey = "SELECT COUNT(*) as a FROM `sky_first_aid`.`user_supervise_info` WHERE hunter_id=$uid AND type=0 AND state=1";
        $my_prey_active = "SELECT COUNT(*) as a FROM `sky_first_aid`.`user_supervise_info` WHERE hunter_id=$uid AND type=1 AND state=1";

        $hunter_num = $db->getRow($my_hunter);
        $prey_num = $db->getRow($my_prey);
        $prey_active_num = $db->getRow($my_prey_active);

        $retarr=array();
        $retarr['code']='1';
        $retarr['msg']='请求成功';
        $retarr['fnum']=$num;
        $retarr['hunter_num']=$hunter_num['a'];
        $retarr['prey_num']=$prey_num['a'];
        $retarr['prey_active_num']=$prey_active_num['a'];
        $retarr['result']=$res;

        die(json_encode($retarr)."\n");
    }
    $retarr=array();
    $retarr['code']='1';
    $retarr['msg']='请求成功';
    $retarr['result']=array();
    $retarr['fnum']=0;

    die(json_encode($retarr)."\n");
}

//检查有无监控人失联  update_my_location
function CheckSLState($uid){
    $rkMongo = new RKMongo();
    $rkMongo->connect();

    $res["warning"]=0;
    if ($friends=GetBeMonFriend($uid)) { //获取所有被我监控的好友
        foreach($friends as $friend){
            $GPSAddress = $rkMongo->getGPSAddress(1, $friend['id']);
            $stime=time()-$GPSAddress['dt']->sec;

            if ($stime>1800) {
                $res["warning"]=1;
                break;
            }
        }
    }

    return $res;
}

//获取所有被我监控的好友
function GetBeMonFriend($uid){
    $dbObj=new DatabaseManager();
    $db=$dbObj->getConn();

    $sql = <<<T_ECHO
SELECT prey_id as id FROM `sky_first_aid`.`user_supervise_info` WHERE hunter_id='{$uid}' AND (TYPE=0 OR (TYPE=1 and state=1))
T_ECHO;

    if($res=$db->getAll($sql)) return $res;
    return array();
}

function CheckSLStateByFlag($uid){
    $rkMongo = new RKMongo();
    $rkMongo->connect();

    $res["warning"]=0;
    if ($friends=GetBeMonFriendByFlag($uid,0)) { //获取所有被我监控的好友
        foreach($friends as $friend){
            $GPSAddress = $rkMongo->getGPSAddress(1, $friend['id']);
            $stime=time()-$GPSAddress['dt']->sec;

            if ($stime>1800) {
                $res["warning"]=$res["warning"]+1;
                break;
            }
        }
    }
    if ($friends=GetBeMonFriendByFlag($uid,1)) { //获取所有被我监控的好友
        foreach($friends as $friend){
            $GPSAddress = $rkMongo->getGPSAddress(1, $friend['id']);
            $stime=time()-$GPSAddress['dt']->sec;

            if ($stime>1800) {
                $res["warning"]=$res["warning"]+2;
                break;
            }
        }
    }
    return $res;
}

//获取所有被我监控的好友
function GetBeMonFriendByFlag($uid,$flag){
    $dbObj=new DatabaseManager();
    $db=$dbObj->getConn();

    $sql = <<<T_ECHO
SELECT prey_id as id FROM `sky_first_aid`.`user_supervise_info` WHERE hunter_id='{$uid}' AND type={$flag} AND state=1
T_ECHO;

    if($res=$db->getAll($sql)) return $res;
    return array();
}
//删除我的被动监护人
function delMyHunter($uid){
    $dbObj=new DatabaseManager();
    $db=$dbObj->getConn();
    $sql = "DELETE FROM `sky_first_aid`.`user_supervise_info` WHERE prey_id=$uid AND type=0 ";
    return $db->execute($sql);
}