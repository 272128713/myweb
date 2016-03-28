<?php
//文章处理 
function procedure($arr,$is_index=true){
	if(!$arr){
		return '<h1 style="text-align:center; padding:20px 0; font-weight:400; color:#666; font-size:14px;">暂无内容</h1>';
	}
	$str='';
	foreach ($arr as $v){
		$img=unserialize($v['pic']);
		
		foreach ($img as $key=>$value){
				$img[$key]=C('WEB_URL').$value;
		}
		$times=strtotime($v['examine_time']);
        $aid=$v['id'];
		//是否为投稿
		if(!is_null($v['doctor_id'])){
			$username=M('user_base_info')->where(array('user_id'=>$v['doctor_id']))
			         ->getField('user_name');
			$str.='<li time="'.$times.'" aid ="'.$aid.'" class="mui-table-view-cell mui-media">
		<a href="'.U('detail',array('aid'=>$aid)).'">
			<div class="mui-media-body">
				<p class="mui-ellipsis space">'.$v['title'].'</p>
			</div>
		</a>
		<div class="linav" >
			<div class="logo8">
				<img src="'.__ROOT__.'/Public/Article/images/logo8.png" />
			</div>
			
			<span id="">
				'.$username.'医生原创
			</span>		
			<span id="">
				评论'.$v['eval_num'].'
			</span>		
			<span class="linav3">
				'.transfer_time(strtotime($v['examine_time'])).'
			</span>
		</div>
	</li>';
		}else{
		//1个小图
	   if($v['view_type']==2){
		$str.='<li time="'.$times.'" aid ="'.$aid.'" class="mui-table-view-cell mui-media" >
		<a href="'.U('detail',array('aid'=>$aid)).'">
			<img class="mui-media-object mui-pull-right" src="'.$img[0].'">
			<div class="mui-media-body">
				<p class="mui-ellipsis space" >'.$v['title'].'</p>
			</div>
		</a>';
		//1个大图
		}else if($v['view_type']==1){
			$str.='<li time="'.$times.'" aid ="'.$aid.'" class="mui-table-view-cell mui-media">
		<a href="'.U('detail',array('aid'=>$aid)).'">

			<div class="mui-media-body">
				<p class="mui-ellipsis space">'.$v['title'].'</p>
			</div>
			<img class="bigpic"  src="'.$img[0].'">
		</a>
		';
		}else if($v['view_type']==3){
			$str.='<li time="'.$times.'" aid ="'.$aid.'" class="mui-table-view-cell mui-media">
		<a href="'.U('detail',array('aid'=>$aid)).'">
			<div class="mui-media-body">
				<p class="mui-ellipsis">'.$v['title'].'</p>
			</div>
			<div class="bigpic">
				<div class="pic3">
				<img  src="'.$img[0].'"/>				
				</div>
				<div class="pic3">
				<img  src="'.$img[1].'"/>				
				</div>			<div class="pic3">
				<img  src="'.$img[2].'"/>				
				</div>
			</div>
		</a>
		';
		}else if($v['view_type']==4){
			$str.='<li time="'.$times.'" aid ="'.$aid.'" class="mui-table-view-cell mui-media">
			<a href="'.U('detail',array('aid'=>$aid)).'">
			<div class="mui-media-body">
			<p class="mui-ellipsis space">'.$v['title'].'</p>
			</div>
			</a>
			';
		}
            //右边小图样式
            $str2='<div class="linav" style="position:absolute; bottom: 8px;width: 60%;" >
			<span id="">
				'.$v['article_from'].'
			</span>
			<span id="">
				评论'.$v['eval_num'].'
			</span>
			<span class="linav3">
				'.transfer_time(strtotime($v['examine_time'])).'
			</span>
		</div>
	    </li>';
		if($v['view_type']==2){
            $str.=$str2;
        }else {
            $str .= '<div class="linav" >
			<span id="">
				' . $v['article_from'] . '
			</span>		
			<span id="">
				评论' . $v['eval_num'] . '
			</span>		
			<span class="linav3">
				' . transfer_time(strtotime($v['examine_time'])) . '
			</span>
		    </div>
	        </li>';
        }


		}
	}
	return  $str;
}
/**
 * 时间处理
 * @param unknown $time
 * @return string
 */
function transfer_time($sTime)
{
	//sTime=源时间，cTime=当前时间，dTime=时间差
	$cTime		=	time();
	$dTime		=	$cTime - $sTime;
	$dDay		=	intval(date("Ymd",$cTime)) - intval(date("Ymd",$sTime));
	$dYear		=	intval(date("Y",$cTime)) - intval(date("Y",$sTime));
	if( $dTime < 60 ){
		$dTime =  $dTime."秒前";
	}elseif( $dTime < 3600 ){
		$dTime =  intval($dTime/60)."分钟前";
	}elseif( $dTime >= 3600 && $dDay == 0  ){
		$dTime =  "今天".date("H:i",$sTime);
	}elseif($dYear==0){
		$dTime =  date("m-d H:i",$sTime);
	}else{
		$dTime =  date("Y-m-d H:i",$sTime);
	}
	return $dTime;
}

/**
 * 返回导航
 */
function nav_oprate($c=null){
	$cate=M('article_class')->where(array('state'=>1))->select();
	$str='';
	if(!isset($_GET['cid'])){
		//$str.='<a class="mui-control-item mui-active" href="'.U('index').'">全部</a>';
		foreach ($cate as $keys=>$v){
            if($keys==0){
                $str.='<a  class="mui-control-item mui-active" href="'.U('index',array('cid'=>$v['id'])).'">'.$v['class_name'].'</a>';
            }else{
                $str.='<a  style="border-left: 1px solid #C8C7CC;
			" class="mui-control-item" href="'.U('index',array('cid'=>$v['id'])).'">'.$v['class_name'].'</a>';

            }

		}
		
	}else{
	    //$str.='<a class="mui-control-item" href="'.U('index').'">全部</a>';
		foreach ($cate as $v){
			if($v['id']==$_GET['cid']){
				$str.='<a style="border-left: 1px solid #C8C7CC;
			" class="mui-control-item mui-active" href="'.U('index',array('cid'=>$v['id'])).'">'.$v['class_name'].'</a>';
				
			}else{
				$str.='<a style="border-left: 1px solid #C8C7CC;
			" class="mui-control-item" href="'.U('index',array('cid'=>$v['id'])).'">'.$v['class_name'].'</a>';
				
			}
		}
	}
    if(is_null($c)){
        $str.='<a class="mui-control-item ">&nbsp;</a>';
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
        if ($key!=false) {
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
 * 返回更多我的评论
 */
function get_my_html($arr){
    $str='';
    foreach($arr as $v){
        $str.='	<li class="libo" >
					<div class="mui-pull-left tu">
					</div>
					<div class="wen">
						'.$v['content'].'
						<div class="ltitle">
							'.$v['article_from'].'：'.$v['title'].'
							<div class="">
								<span class="mui-pull-left">
										'.$v['create_time'].'
								</span>

								<div class="mui-pull-right">
									<span class=" iconfont icon-zan blue" style="font-size:14px"></span>('.$v['up_num'].')
									<span class ="delete_evalu" eid="'.$v['id'].'"><span  class=" mui-icon mui-icon-trash blue" style="font-size:16px;"></span>删除</span>
								</div>
							</div>
						</div>
					</div>
		   	</li>';
    }
    return $str;
}

/**
 * 处理评价内容
 */
function oprate_evalute($newcom){
    foreach($newcom as $key=>$v){
        if($v['thumbnail_image_url']==''){
            $newcom[$key]['thumbnail_image_url']=__ROOT__.'/Public/Article/images/MOO/data/default.jpg';
        }else{
            $newcom[$key]['thumbnail_image_url']='http://'.C('IMG_HOST').str_replace('M00','MOO/data',$v['thumbnail_image_url']);
        }
    }
    return $newcom;
}