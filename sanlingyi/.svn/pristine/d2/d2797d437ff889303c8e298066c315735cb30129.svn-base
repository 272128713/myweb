<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>医苑天地</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
        
<link rel="stylesheet" href="/sanlingyi/Public/Hospital/css/mui.min.css">
<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Hospital/css/app.css" />
<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Hospital/css/common.css" />
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Hospital/css/doctorindex.css" />

	</head>

	<body>
    <header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="logoutConsultings()"></a>
        <h1 class="mui-title" style="color:#fff">医苑天地</h1>

        <div class="right">
            <span style="color: #fff;display: block;height: 44px;width:3em;text-align:center;line-height:44px;font-size:16px;"><img class="xiala" src="/sanlingyi/Public/Hospital/images/xiala.png"  /></span>
        </div>
    </header>
    <script>
        /**
         * 返回app
         */
        function logoutConsultings(){

            if(navigator.userAgent.match('iPhone')){
                logoutConsulting();
            }
            if(navigator.userAgent.match('Android')){
                Android.logoutConsulting();
            }
        }
    </script>
		<!--
        	时间：2015-06-29
        	描述：主要内容
        -->
		<div class="navi">
    <div class="bg"></div>
    <p><a href="<?php echo U('Index/cate');?>"><img src="/sanlingyi/Public/Hospital/images/xb (2).png"/>热点话题</a></p>
    <p><a href="<?php echo U('Index/lists',array('cid'=>1));?>"><img src="/sanlingyi/Public/Hospital/images/xb (3).png"/>学术交流</a></p>
    <p><a href="<?php echo U('Index/lists',array('cid'=>2));?>"><img src="/sanlingyi/Public/Hospital/images/xb (4).png"/>有问必答</a></p>
    <p><a href="<?php echo U('Index/lists',array('cid'=>3));?>"><img src="/sanlingyi/Public/Hospital/images/xb (5).png"/>病例分析</a></p>
    <p><a href="<?php echo U('Index/lists',array('cid'=>4));?>"><img src="/sanlingyi/Public/Hospital/images/xb (6).png"/>行业观察</a></p>
    <p><a href="<?php echo U('MyChat/index');?>"><img src="/sanlingyi/Public/Hospital/images/xb (7).png"/>我的记录</a></p>
    <p class="p_btm" onclick="publishs('')"><a>
        <img src="/sanlingyi/Public/Hospital/images/xb (1).png"/>
        <br />
        发表
    </a>
    </p>
</div>
<script>
function publishs(aid){
    if(navigator.userAgent.match('iPhone')){
        // alert();
        publish(aid);
    }
    if(navigator.userAgent.match('Android')){
        //alert($uid);
        Android.publish(aid);
    }
}

</script>
		<div class="mui-content">
			<div class="hot">
				<div class="left"><img src="/sanlingyi/Public/Hospital/images/hot.png"/>热点话题</div>
				<div onclick="skip()" class="mui-icon mui-icon-arrowright right arrow" ></div>
				<div class="clear"></div>
			</div>
            <script>
                function skip(){
                    window.location.href="<?php echo U('Index/cate');?>";
                }
            </script>
			<div class="hot-box">
                <a href="<?php echo U('Index/detail',array('aid'=>$at['id']));?>">
                <?php if(is_array($img)): foreach($img as $key=>$vo): ?><img src="<?php echo ($vo["source_image_url"]); ?>"/><?php endforeach; endif; ?>

				<p class="hot-title"><?php echo ($at["title"]); ?></p>
				<p class="hot-content"><?php echo ($at["intro"]); ?></p>
				<p class="canyu"><img src="/sanlingyi/Public/Hospital/images/canyu.png"/>已参与<span class="red"><?php echo ($at["recommend_num"]); ?></span>人次 <span class="right"><?php echo ($at["createDate"]); ?></span></p>
                </a>
			</div>
				<div class="newat">最新动态<a  href="<?php echo U('Index/lists');?>" style="float:right; padding-right: 1em">更多</a></div>
				<ul class="new-list">
                    <?php if(is_array($news)): foreach($news as $key=>$vo): ?><li>
                            <a href="<?php echo U('Index/detail',array('aid'=>$vo['id']));?>">
                            <img src="<?php echo ($vo["img_url"]); ?>"/>
                            <div class="list-box">
                                <p class="p1 ">
                                     <?php echo ($vo["user_name"]); ?>
                                    <p class="p2"><?php echo ($vo["createDate"]); ?></p>
                                    <div class="clear"></div>
                                </p>

                                <p class="p3"><span class="blue">【<?php echo get_name($vo['columns']); ?>】</span><?php echo ($vo["title"]); ?></p>
                            </div>
                            <div class="clear"></div>
                            </a>
                        </li><?php endforeach; endif; ?>
				</ul>
		</div>
    <script src="/sanlingyi/Public/Hospital/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/sanlingyi/Public/Hospital/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">
            $(function(){
                var get_navi = $('.navi');
                $(".xiala").click(function(){
                    get_navi.slideToggle('fast');
                });
                $('.mui-content').click(function(){
                    if(get_navi.css('display')=="block"){
                        get_navi.slideUp('fast');
                    }
                });
                $('.new-list li').hover(function(){
                    $(this).css('background','#EFEFEF');
                },function(){
                    $(this).css('background','#fff');
                });
            });
        </script>
	</body>

</html>