<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>众筹平台</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="/sanlingyi/Public/Entity/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Entity/css/app.css" />
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Entity/css/common.css" />
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Entity/css/content.css" />

	</head>

	<body>
		<header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="logoutConsultings()"></a>
			<h1 class="mui-title" style="color:#fff" onclick="logoutConsultings()">众筹平台</h1>

			<div class="right" style="height: 45px;">
				<a href="<?php echo U('search');?>"><img class="pl"  src="/sanlingyi/Public/Entity/images/shaixuan.png" /></a>
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
        
        	<div class="bg-img">
        		<img src="/sanlingyi/Public/Entity/images/raw_1434602743.png"/>
        	</div>
			<div class="banner">
				<img src="/sanlingyi/Public/Entity/images/logo.png" class="logo"/>
				<p class="p0">众筹空中诊所&nbsp;&nbsp;共铸健康未来<a href=""><img src="/sanlingyi/Public/Entity/images/wenhao.png" class="wenhao"/></a></p>
				
				<div class="banner-foot">
					<p class="pl left"><img src="/sanlingyi/Public/Entity/images/small.png"/> 在线客服</p>
					<p class="p2 right"><img src="/sanlingyi/Public/Entity/images/tel.png"/> 官方咨询400-669-3636</p>
					<div class="clear"></div>
				</div>
			</div>
			
			<div class="nav">
				<ul>
					<li><a href="<?php echo U('Index/index');?>">综合推荐</a></li>
					<!--<li><a href="">离我最近</a></li>-->
					<li><a href="<?php echo U('index',array('type'=>1));?>">达成最多</a></li>
					<li><a href="<?php echo U('index',array('type'=>2));?>">最新上线</a></li>
				</ul>
				<div class="clear">
					
				</div>
			</div>
			<!--
            	作者：272128713@qq.com
            	时间：2015-07-30
            	描述：诊所box
            -->
        <div>
            <div id="content_list">
                 <?php if(is_array($news)): foreach($news as $key=>$v): ?><div class="zs-box" url="<?php echo U('Index/detail',array('id'=>$v['id']));?>">
				<div class="content">
				<p class="title1"><?php echo ($v["clinic_name"]); ?></p>
				<p class="title2"><?php echo ($v["type"]); ?></p>
				<div class="clear"></div>
				<p class="title3"><?php echo ($v["address"]); ?></p>
				<!--
                	作者：272128713@qq.com
                	时间：2015-07-30
                	描述：医生box
                -->
                <?php if(is_array($v['doc'])): foreach($v['doc'] as $key=>$vo): ?><div class="zs-list">
					<img src="<?php echo ($vo["img_url"]); ?>"/>
					<div class="zs-list-p">
						<p class="p1"><?php echo ($vo["user_name"]); ?></p>
						<p class="p2"><?php echo ($vo["k_name"]); ?></p>
						<p class="p3"><?php echo ($vo["service_num"]); ?>/<?php echo ($vo["sale_num"]); ?></p>
						<div class="clear"></div>
						<div class="p4"><span><?php echo ($vo["recollection_name"]); ?> </span><span> <?php echo ($vo["duty_name"]); ?></span></div>
						<p class="p5"><?php echo ($vo["hospital"]); ?> <?php echo ($vo["h_area"]); ?></p>
					</div>
					<div class="clear"></div>
					<div class="money">
						<div class="money1 left">
							<img src="/sanlingyi/Public/Entity/images/jisuanqi.png"/>
							<span>
								预筹：
							</span>
							<span class="red">
								￥<?php echo ($vo["tmoney"]); ?>
							</span>
						</div>
						<div class="money1 right">
							<img src="/sanlingyi/Public/Entity/images/money.png"/>
							<span>
								达成：
							</span>
							<span class="red">
								￥<?php echo ($vo["nmoney"]); ?>
							</span>
						</div>
						<div class="clear"></div>
					</div>
				</div><?php endforeach; endif; ?>
				<!--
                	作者：272128713@qq.com
                	时间：2015-07-30
                	描述：医生box
                -->				

				<!--
                	作者：272128713@qq.com
                	时间：2015-07-30
                	描述：医生box
                -->				

			</div>
		</div><?php endforeach; endif; ?>
            </div>
            <?php if($nums>=C('PAGE_NUM')){ ?>
            <div id="order_list" onclick="get_more()" style="text-align: center;line-height: 2em; margin:2em 0em 0 0; color: #333; font-size: 1em; background: #DDDDDD">
                点击加载更多
            </div>
            <?php } ?>
        <script src="/sanlingyi/Public/Entity/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="/sanlingyi/Public/Entity/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			$(function(){
				var get_bg_img = $('.banner').height();
				$('.bg-img img').height(get_bg_img);
			});
                $('.zs-box').click(function(){
                    var url=$(this).attr('url');
                    window.location.href=url;
                });


		</script>
            <script>
                //分页
                sanlingyi_page=1;
                function get_more(){
                    //加载文章
                    var article=$('#content_list');
                    var url="<?php echo U('Index/index',array('type'=>$_GET['type'],'svalue'=>$search),'');?>";
                    $.get(
                            url,{"page":sanlingyi_page,"ajax":1},function(date){

                                if(date!=0){
                                    sanlingyi_page+=1;
                                    article.append(date);

                                }else{
                                    $('#order_list').html('暂无更多')
                                }
                            }
                            ,'text' );


                }
            </script>
    </body>

</html>