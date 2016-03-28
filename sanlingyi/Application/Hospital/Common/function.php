<?php
/**
 * 获取分类信息
 */
function get_ret(){
    $ret=M('dim_recollection_code',null)->select();
    $new=array();
    foreach($ret as $k=>$v){
            if($v['level']==1){
                $nt=array();
                $new[$k]=$v;
                foreach($ret as $key=>$vo){
                        if($vo['pid']==$v['recollection_id']){
                            $new[$k]['child'][]=$vo;
                        }
                }


            }
    }
    return $new;
}
function array_remove(&$arr, $offset)
{
array_splice($arr, $offset, 1);
}
/**
 * 返回科室导航
 */
function get_ret_nav(){
    if(!session('?san_cate')){
      session('san_cate',C('DEFAULT_CATE'));
    }
 //有栏目id
//     if(isset($_GET['rid'])){
//         $str=session('san_cate');
//         $arr=explode(',',$str);
//         $key = array_search($_GET['rid'], $arr);
//         if ($key===false) {
//         }else{
//             array_remove($arr,$key);
//         }
//         array_unshift($arr, $_GET['rid']);
//         $arr = array_values($arr);
//         if(count($arr)>4) {
//             array_pop($arr);
//         }
//         $str=implode(',',$arr);
//         session('san_cate',$str);

//     }
    $condition= session('san_cate');
    $newarr= $arr=explode(',',$condition);
    $str='';
    $ret=M()->query("select * from dim_recollection_code where recollection_id IN ($condition)");
    $new_result=array();
    foreach($newarr as $k=>$v ){
            foreach($ret as $key=>$value){
                if($value['recollection_id']==$v){
                    $new_result[$k]=$value;
                }
            }
    }
    $ret=$new_result;
    if(!isset($_GET['rid'])){
        $ac='<li class="active"><a href="'.U('Index/lists',array('cid'=>$_GET['cid'])).'" >全部</a></li>';

    }else{
        $ac='<li><a href="'.U('Index/lists',array('cid'=>$_GET['cid'])).'" >全部</a></li>';
    }
    $str.=$ac;
    foreach($ret as $k=>$v){
            $cate[]=$v;
            if($k<4){
                if($v['recollection_id']==$_GET['rid']){
                    $str.='<li class="active"><a  href="'.U('Index/lists',array('rid'=>$v['recollection_id'],'cid'=>$_GET['cid'])).'">'.$v['name'].'</a></li>';
                }else{
                    $str.='<li><a href="'.U('Index/lists',array('rid'=>$v['recollection_id'],'cid'=>$_GET['cid'])).'">'.$v['name'].'</a></li>';

                }
            }
    }

    return $str;
}

/**
 * 返回科室二级导航
 */
function get_ret_sub(){
    $url=U('Index/lists',array('rid'=>$_GET['rid']));
    $str='<p class="">';
    $ret=get_ret();
    foreach($ret as $k=>$v){
            if(($k+1)%3==0 && $k!=17){
                    $str.='<a href="'.U('Index/lists',array('rid'=>$v['id'])).'">【'.$v['name'].'】</a></p>
				<p class="">';
            }else
            {
                $str.='<a href="'.U('Index/lists',array('rid'=>$v['id'])).'">【'.$v['name'].'】</a>';
            }
    }
    $str.='</p>';
    echo $str;
}

/**
 * 栏目条件
 */
function get_cate_condition(){
    if(isset($_GET['rid']) && isset($_GET['cid'])){
        //判断是否为一级栏目
        //$rid=$_GET['rid'];
        $l= M('dim_recollection_code')->where(array('recollection_id'=>$_GET['rid']))->getField('level');
        $sql="SELECT recollection_id from dim_recollection_code where pid=".$_GET['rid'];
        $result=M()->query($sql);
        if($result){
        	$status=true;
        }else{
        	$status=false;
        }
        if($l==1 && $result==true){
            //查询子类
            $condition=' and a.recollection_id IN (SELECT recollection_id from dim_recollection_code where pid='.$_GET['rid'].') and a.columns= '.$_GET['cid'];
        }else{
            $condition=' and a.recollection_id = '.$_GET['rid'].' and a.columns= '.$_GET['cid'];
        }

        return $condition;

    }
    if(isset($_GET['rid']) && !isset($_GET['cid'])){
        $l= M('dim_recollection_code')->where(array('recollection_id'=>$_GET['rid']))->getField('level');
        if($l==1 && $result==true) {
            $condition = ' and a.recollection_id IN (SELECT recollection_id from dim_recollection_code where pid='.$_GET['rid'].')';
        }else{
            $condition = ' and a.recollection_id= ' . $_GET['rid'];
        }
        return $condition;

    }
    if(!isset($_GET['rid']) && isset($_GET['cid'])){
        $condition=' and a.columns= '.$_GET['cid'];
        return $condition;

    }
}

/**
 * 根据id返回栏目名字
 */
function get_name($in){
    $index=array('学术交流','有问必答','病例分析','行业观察');
    return $index[$in-1];
}

/**
 * 返回列表
 */
function get_lists_up($arr){
    $str='';
    foreach($arr as $k=>$v) {
        $str .= ' <div class="list-box">
                        <img src="' . $v['img_url'] . '"/>
                        <div class="cont-li">
                            <div class="li-title">
                                <div class="p1">
                                    ' . $v['user_name'] . '
                                </div>
                                <p class="p2">' . $v['createDate'] . '</p>
                                <div class="clear"></div>
                            </div>
                            <p class="p3">' . $v['title'] . '</p>
                        </div>
                        <div class="clear"></div>
                    </div>';
    }
    return $str;
}

function get_my_info(){

}

function make_com_str($arr){
    $str='';
    foreach($arr as $k=>$vo) {
        $str .= '<div class="chat-list">
            <img src="__IMG__/circle.png" class="img-c"/>
            <div class="chat-list-li">
                <p class="p1">' . $vo['content'] . '</p>
                <p class="p2">' . $vo['title'] . '</p>
                <div class="time">
                    <p class="p3">' . $vo['createData'] . '</p>
                    <div class="right">
                        <p class="p4"><img src="__IMG__/zan.png"/>' . $vo['up_num'] . '</p>
                        <p class="p5 delete_evalu" eid="' . $vo['id'] . '" aid="' . $vo['articel_id'] . '">
                        <img src="__IMG__/iconfont-shanchu.png"/>删除</p>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>';
    }
        return $str;
}
//function get_articel_str()
//{
//    $str = '<div class="art-box-list">
//            <p class="p1">{$v.title}</p>
//            <div class="p2">
//                <p class="p3"><span class="blue">【<php>echo get_name($v['columns']);</php>】</span>{$v.name}</p>
//                <php>';
//
//    if($v['report_flag']!=0){
//    $str.='
//                </php>
//                <p class="p4"><span class="blue">【医讯头条】</span>心血管</p>
//                <php>
//                    }
//                </php>
//                <div class="clear"></div>
//            </div>
//            <div class="time">
//                <p class="ptime">{$v.createDate}</p>
//                <div class="jh">
//                    <php>';
//     if($v['report_flag']==0){
//                          $model=D('Article');
//                 		   $img=$model->getImg($v['id']);
//                  		   $v['img_url']= $img[0]['source_image_url'];
//                  		   $v['img_id']= $img[0]['article_image_id'];
//                   			 $data=json_encode($v);
//                    		$name='data'.$key;
// $str.='
//                    <script>
//                        var '. $name.'='. $data.';
//</script>';
// $str.='<div class="bj" onclick="'.$name.')">
//<img src="__IMG__/iconfont-bianji.png" />编辑
//</div>
//<div class="sc delete_article"  aid="{$v.id}">
//<img  src="__IMG__/iconfont-shanchu.png"/>删除
//</div>';
//<php>
//}else{
//</php>
//    <div class="sh">
//    审批中
//    </div>
//    <div class="sc">
//    <img src="__IMG__/iconfont-shanchu.png"/>删除
//    </div>
//    <php>
//}
//</php>
//
//</div>
//<div class="clear"></div>
//</div>
//</div>';
//}

/**
 * 是否是医生
 */
function is_doc(){
    $is_doc=M('user_base_info',null)->where(array('user_id'=> session('yixin_user')))->getField('user_type_id');
    if($is_doc==2){
        return true;
    }else{
        return false;
    }
}

/**
 * 审核状态
 */
function get_status($i){
    $arr=array('1'=>'审核中','2'=>'通过','3'=>'未通过');
    return $arr[$i];
}

/**
 * 文章格式化
 * @param unknown $a
 */
function article_change($a){
	$a['content']=str_replace(chr(32), '&nbsp;', $a['content']);
	$a['content']=str_replace("<","&lt;",$a['content']);
	$a['content']=str_replace(">","&gt;",$a['content']);
	$a['content']=str_replace("\n","<br>",$a['content']);
	$a['content']=str_replace("\r","<br>",$a['content']);
	return  $a;
}

