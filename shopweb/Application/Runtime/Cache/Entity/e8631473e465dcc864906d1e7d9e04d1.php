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
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Entity/css/chooseDoctor.css" />
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Entity/css/orderConfirm.css" />
	</head>

	<body>
		<div class="container">
		<header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
			<h1 class="mui-title" style="color:#fff" onclick="history.go(-1)">认购</h1>
		</header>
		
			<div class="textarea">
        		<div class="text">
                    <p class="title1"><?php echo ($v["clinic_name"]); ?></p>
                    <p class="title2"><?php echo ($v["type"]); ?></p>
                    <p class="title3"><?php echo ($v["address"]); ?></p>
				</div>
			</div>
			<div class="intro">
				<div class="intro-rectangle"></div>
				<p class="intro-text">请选择为您服务的医生</p>
				<div class="clear"></div>
			</div>
			<ul class="doctor-list">
                <?php if(is_array($v['doc'])): foreach($v['doc'] as $key=>$vo): ?><li class="content" url="<?php echo U('sure',array('id'=>$_GET['id'],'uid'=>$vo['doctor_id']));?>" style="border-bottom:1px solid #dfdfdf;">
				<div class="content-inner" >
					<img src="<?php echo ($vo["img_url"]); ?>" style="border-radius:3px;" />
					<div class="doctor_info">
						<p class="xingming"><?php echo ($vo["user_name"]); ?>
							<span class="orange"><?php echo ($vo["k_name"]); ?></span>
							<span class="red">¥<?php echo ($vo["money"]); ?><span class="red" style="margin-left:1em"><?php echo ($vo["service_num"]); ?>/<?php echo ($vo["sale_num"]); ?></span></span>
						</p>
						<p class="keshi"><?php echo ($vo["recollection_name"]); ?><span class="yishi"><?php echo ($vo["duty_name"]); ?></span></p>
						<div class="clear"></div>
						<p class="address"><?php echo ($vo["hospital"]); ?> <?php echo ($vo["h_area"]); ?></p>
					</div>
					<div class="clear"></div>
				</div>
			    </li><?php endforeach; endif; ?>
			</ul>
		</div>
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

            $('.doctor-list li').click(function(){
                var url=$(this).attr('url');
                window.location.href=url;
            });
		</script>
	</body>

</html>