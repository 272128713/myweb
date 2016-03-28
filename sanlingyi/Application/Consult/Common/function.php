<?php
/**
 * 返回性别
 */
function get_sex($code){
    if($code==0){
        return '未填写';
    }else if($code==1){
        return '男';
    }else{
        return '女';
    }
}

/**
 * 返回问题列表
 */
function get_lists_ask($arr){
    $str='';
    foreach($arr as $k=>$v){
    	if($v['is_see_doctor']==1){
    		$str.='<li class="mui-table-view-cell">
				    <a href="'.U('Index/detail',array("aid"=>$v['question_id'])).'" class="mui-navigate-right">
				      <img src="'.__ROOT__.'/Public/Consult/images/wen.png" class="wen"/>
				      <div class="ti">'.$v['title'].'<span>（'.get_sex($v['sex']).'，'.$v['age'].'岁）</span>
				      	<div class="clear tifoot">'.$v['recollection'].'、'.$v['disease'].'
				      		<div class="right">'.substr($v['createDate'],0,10).'&nbsp;&nbsp;&nbsp;&nbsp;回复 <span class="red">'.$v['answer_num'].'</span></div>
				      	</div>
				      </div>
				    </a>
				  </li>';
    	}else{
    		$str.='<li class="mui-table-view-cell">
				    <a href="'.U('Index/detail',array("aid"=>$v['question_id'])).'" class="mui-navigate-right">
				      <img src="'.__ROOT__.'/Public/Consult/images/wen.png" class="wen"/>
				      <div class="ti">'.$v['title'].'<span>（'.get_sex($v['sex']).'，'.$v['age'].'岁）</span>
				      	<div class="clear tifoot">未就医
				      		<div class="right">'.substr($v['createDate'],0,10).'&nbsp;&nbsp;&nbsp;&nbsp;回复 <span class="red">'.$v['answer_num'].'</span></div>
				      	</div>
				      </div>
				    </a>
				  </li>';
    	}
      
    }
    return $str;
}

/**
* 浏览记录处理
*/
function loop_history($value)
{
    $arr = cookie('history');
    if (isset($arr)) {
        $arr = $arr;
    } else {
        $arr = array();
    }
    //如果存在
    if($value!='') {
        $key = array_search($value, $arr);
        if ($key===false) {
        }else{
            array_remove($arr,$key);
        }
        array_unshift($arr, $value);
    }
    $arr = array_values($arr);
    if(count($arr)>10) {
        array_pop($arr);
    }
    cookie('history',$arr,array('expire'=>C('HISTORY_EMPIRE')));
    return 1;
}

function array_remove(&$arr, $offset)
{
    array_splice($arr, $offset, 1);
}

/**
 * 无内容
 */
function echo_empty(){
    echo '<h1 style="text-align:center; width: 100%; position: absolute; left: 0; top:8em; padding:20px 0; font-weight:400; color:#666; font-size:14px;">暂无内容</h1>';
    die();
}
/**
 * 就医
 */
function see_doc($see,$desea){
    if($see==1){
        return '已经就医确诊为'.$desea;
    }else{
        return '未就医';
    }
}

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
 * 此问题当前医生是否解答
 */
function is_asked($id=null){
    if(is_null($id)){
        $id=$_GET['aid'];
    }
    $is_doc=M('doctor_answer_info')->where(array('doctor_id'=> session('yixin_user'),'question_id'=>$id))->getField('answer_id');
    if($is_doc){
        return true;
    }else{
        return false;
    }
}

function echo_title(){
    $is_doc=is_doc();
    if($is_doc){
        echo '在线义诊';
    }else{
        echo '在线咨询';
    }
}
