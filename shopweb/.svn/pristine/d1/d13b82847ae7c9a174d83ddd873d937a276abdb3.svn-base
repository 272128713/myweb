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
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Entity/css/detail.css" />

	</head>

	<body>
        <?php if(!isset($_GET['wxfx'])){ ?>
		<header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
            <?php if(!isset($_GET['type'])){ ?>
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
			<h1 class="mui-title" style="color:#fff" onclick="history.go(-1)">众筹平台</h1>
            <?php }else{ ?>
            <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="logoutConsultings()"></a>
            <h1 class="mui-title" style="color:#fff" onclick="logoutConsultings()">众筹平台</h1>
            <?php } ?>
            <div class="right">

                <?php if($is_save){ ?>
                     <a style="float:left;color: #EC971F;display: block;height: 44px;width:3em;text-align:center;line-height:44px;font-size:16px;" id="add_favorite">收藏</a>
                <?php }else{ ?>
				<a style="float:left;color: #fff;display: block;height: 44px;width:3em;text-align:center;line-height:44px;font-size:16px;" id="add_favorite">收藏</a>
				<?php } ?>
                <?php if(!isset($_GET['type'])){ ?>
                <a style="float:left;color: #fff;display: block;height: 44px;width:3em;text-align:center;line-height:44px;font-size:16px;" onclick="sh_evoke_shares()">分享</a>
                <?php } ?>
			</div>
		</header>
        <?php } ?>
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
        <div class="textarea"
             <?php if(isset($_GET['wxfx'])){ ?>
             style="margin-top: 0;"
            <?php } ?>
                >
        		<div class="text">
                    <p class="title1"><?php echo ($v["clinic_name"]); ?></p>
                    <p class="title2"><?php echo ($v["type"]); ?></p>
                    <p class="title3"><?php echo ($v["address"]); ?></p>
				</div>
			</div>
			<!--
            	作者：272128713@qq.com
            	时间：2015-07-31
            	描述：list
            -->
			<div class="content">
                <?php $people=0; $bpeople=0; $dnmoney=0; $dtmoney=0; ?>
                <?php if(is_array($v['doc'])): foreach($v['doc'] as $key=>$vo): $people+=$vo['sale_num']; $bpeople+=$vo['service_num']; $dnmoney+=$vo['dnmoney']; $dtmoney+=$vo['dtmoney']; ?>
				<div class="content-list">
					<img src="<?php echo ($vo["img_url"]); ?>" style="border-radius:3px;" />
					<div class="doctor_info">
						<p class="xingming"><?php echo ($vo["user_name"]); ?>
							<span class="orange"><?php echo ($vo["k_name"]); ?></span>
							<span class="red"style="margin-left:1em"><?php echo ($vo["service_num"]); ?>/<?php echo ($vo["sale_num"]); ?></span>
						</p>
						<p class="keshi"><?php echo ($vo["recollection_name"]); ?><span class="yishi"><?php echo ($vo["duty_name"]); ?></span></p>
						<p class="address"><?php echo ($vo["hospital"]); ?> <?php echo ($vo["h_area"]); ?></p>
					</div>
					<div class="clear"></div>
				</div><?php endforeach; endif; ?>
			</div>
			<!--
            	作者：272128713@qq.com
            	时间：2015-07-31
            	描述：
            -->
            <div class="lan-line"></div>
            <!--
            	作者：272128713@qq.com
            	时间：2015-07-31
            	描述：project
            -->
            <div class="project">
            	<p class="p1">
                    <?php $unix=strtotime($v['manage_time']); echo date('Y年m月d日',$unix); ?>
                    发起项目</p>
            	<p class="p2">
            		<div class="p-left">
            			<img src="/sanlingyi/Public/Entity/images/p-person.png"/>名&nbsp;&nbsp;&nbsp;&nbsp;额：<span class="red"><?php echo $people; ?></span>
            		</div>
            		<div class="p-left">
            			<img src="/sanlingyi/Public/Entity/images/p-mask.png"/>预&nbsp;&nbsp;&nbsp;&nbsp;筹：<span class="red">￥<?php echo $dtmoney/10000; ?>万</span>
            		</div>
            	</p>
            	<p class="p2">
            		<div class="p-left">
            			<img src="/sanlingyi/Public/Entity/images/p-dui.png"/>已参与：<span class="red"><?php echo $bpeople; ?></span>
            		</div>
            		<div class="p-left">
            			<img src="/sanlingyi/Public/Entity/images/p-money.png"/>已筹得：<span class="red">￥<?php echo $dnmoney/10000; ?>万</span>
            		</div>
            	</p>
            	<div class="clear"></div>
            </div>
			<div class="img-hb">
				<img src="/sanlingyi/Public/Entity/images/huibao.png" id="hb" style="width:50%;">
			</div>
			<div class="service">
				<div class="serv">
					<p class="sd">标准化K服务</p>
				<div class="know">
					<a href="">
						<img src="/sanlingyi/Public/Entity/images/icon.png"/>
						<span>了解K服务</span>
					</a>
				</div>
				<div class="clear"></div>
				</div>
			</div>
			<div style="width:100%;height:20px;background:#e8e8e8">
			</div>
			<div class="service-other">
				<p class="serv">其他医疗服务</p>
			</div>
			<div class="jieshao">
				<p class="wenzi"><?php echo ($v["other_service_content"]); ?></p>
			</div>
        <!--
			<div class="service-other">
				<div class="red-line"></div>
				<p class="serv">评价内容</p>
			</div>
            <?php if(is_array($evalute)): foreach($evalute as $key=>$va): ?><div class="content-list-p">
                <img src="<?php echo ($va["img_url"]); ?>" style="border-radius:100px;" />
                <div class="doctor_info">
                    <p class="xingming"><?php echo ($va["user_name"]); ?>
                        <span style="margin-left:1em;float: right;"><?php echo ($va["create_time"]); ?></span>
                    </p>
                    <p class="address"><?php echo ($va["content"]); ?></p>
                </div>

            <div class="clear"></div>
            </div><?php endforeach; endif; ?>
			<div class="more" onclick="location.href=''">
				查看更多评价 >>
			</div>
			-->
			<div class="service-other">
				<div class="red-line"></div>
				<p class="serv">诊所介绍</p>
			</div>
			<div class="detail-list">
                <?php echo ($v["clinic_introduce"]); ?>
			</div>
             <?php if(!isset($_GET['wxfx'])){ ?>
			<div class="btn">

				<div class="btn1">

					<!--<a class="canyu" href="<?php echo U('order');?>">购买健康险参与</a>-->

					<a class="buy" href="<?php echo U('part',array('id'=>$_GET['id']));?>">我要参与</a>

					
				</div>
				<div class="clear"></div>
			</div>
            <?php } ?>
		<script src="/sanlingyi/Public/Entity/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/sanlingyi/Public/Entity/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
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
        <script>
            //分享
            function sh_evoke_shares(){

                var url="<?php echo C('DOMAIN').U('Entity/Index/detail',array('id'=>$_GET['id'],'wxfx'=>1)) ?>";
                var imgurl="<?php echo C('DOMAIN')?>"+"/sanlingyi/Public/Entity/images/czfx.jpg";
                var title="众筹建诊所免费得私人医生";
                var content="空中医院大力推动私人医生服务，发起众筹助梦医生开设连锁诊所，即日起参与众筹，免费获得私人医生服务...";
                if(navigator.userAgent.match('iPhone')){
                    //alert(1);
                    return sh_evoke_share(url,title,imgurl,content);
                }
                if(navigator.userAgent.match('Android')){
                    return  Android.sh_evoke_share(url,title,imgurl,content);
                }
            }
            //增加收藏
            $('#add_favorite').click(function(){

                var snaliyi_artid="<?php echo ($_GET['id']); ?>"
                var save_url="<?php echo U('MyChat/saveFavorite');?>";
                var is_save=$(this).attr("is_save");
                var isLogin=1;
                //var isLogin=1;
                if(isLogin!=1){
                    mui.alert('用户未登录', '消息', function() {

                    });
                }else{
                    $.post(save_url,{'id':snaliyi_artid,'is_save':is_save},function(data){
                        if(data==1){
                           $('#add_favorite').css('color','#EC971F');
                        }
                    },'text');
                }
            });
        </script>
	</body>

</html>